<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Create Stripe PaymentIntent (AJAX from checkout)
     */
    public function createStripeIntent(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        // Calculate totals server-side (NEVER trust client)
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 1);
        }

        $deliveryMethod = $request->input('delivery_method', 'next_day_free');
        $delivery = $this->getDeliveryCost($deliveryMethod);
        $discount = $this->calculateDiscount($request->input('promo_code'), $subtotal);
        $vat = round(($subtotal - $discount) * 0.20, 2);
        $total = $subtotal - $discount + $delivery + $vat;
        $totalPence = (int)round($total * 100);

        if ($totalPence < 30) {
            return response()->json(['error' => 'Minimum order is £0.30'], 400);
        }

        $stripeSecret = $this->getStripeSecret();
        if (!$stripeSecret) {
            return response()->json(['error' => 'Payment not configured. Please contact support.'], 500);
        }

        try {
            $ch = curl_init('https://api.stripe.com/v1/payment_intents');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_USERPWD => $stripeSecret . ':',
                CURLOPT_POSTFIELDS => http_build_query([
                    'amount' => $totalPence,
                    'currency' => 'gbp',
                    'automatic_payment_methods[enabled]' => 'true',
                    'metadata[user_id]' => auth()->id() ?? 'guest',
                    'metadata[cart_hash]' => md5(json_encode($cart)),
                ]),
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $data = json_decode($response, true);

            if ($httpCode !== 200 || empty($data['client_secret'])) {
                Log::error('Stripe PaymentIntent creation failed', ['response' => $data, 'http_code' => $httpCode]);
                return response()->json(['error' => 'Payment setup failed. Please try again.'], 500);
            }

            session(['stripe_payment_intent' => $data['id']]);
            session(['order_total_calculated' => $total]);

            return response()->json([
                'clientSecret' => $data['client_secret'],
                'amount' => $total,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment service unavailable'], 500);
        }
    }

    /**
     * Create PayPal order (AJAX from checkout)
     */
    public function createPaypalOrder(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 1);
        }

        $deliveryMethod = $request->input('delivery_method', 'next_day_free');
        $delivery = $this->getDeliveryCost($deliveryMethod);
        $discount = $this->calculateDiscount($request->input('promo_code'), $subtotal);
        $vat = round(($subtotal - $discount) * 0.20, 2);
        $total = $subtotal - $discount + $delivery + $vat;

        $paypal = DB::table('payment_methods')->where('slug', 'paypal')->first();
        if (!$paypal || !$paypal->is_active) {
            return response()->json(['error' => 'PayPal not available'], 400);
        }

        $config = json_decode($paypal->config, true);
        $clientId = $config['client_id'] ?? '';
        $secret = $config['secret'] ?? '';
        $mode = $config['mode'] ?? 'sandbox';
        $currency = $config['currency'] ?? 'GBP';

        if (!$clientId || !$secret) {
            return response()->json(['error' => 'PayPal not configured'], 500);
        }

        $apiBase = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        try {
            // Get access token
            $ch = curl_init($apiBase . '/v1/oauth2/token');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_USERPWD => $clientId . ':' . $secret,
                CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            ]);
            $tokenResp = json_decode(curl_exec($ch), true);
            curl_close($ch);

            $accessToken = $tokenResp['access_token'] ?? null;
            if (!$accessToken) {
                return response()->json(['error' => 'PayPal authentication failed'], 500);
            }

            // Create order
            $ch = curl_init($apiBase . '/v2/checkout/orders');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $accessToken,
                ],
                CURLOPT_POSTFIELDS => json_encode([
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($total, 2, '.', ''),
                        ],
                        'description' => 'London InstantPrint Order',
                    ]],
                ]),
            ]);
            $orderResp = json_decode(curl_exec($ch), true);
            curl_close($ch);

            if (empty($orderResp['id'])) {
                return response()->json(['error' => 'PayPal order creation failed'], 500);
            }

            session(['paypal_order_id' => $orderResp['id']]);
            session(['order_total_calculated' => $total]);

            return response()->json([
                'orderId' => $orderResp['id'],
                'amount' => $total,
            ]);
        } catch (\Exception $e) {
            Log::error('PayPal error: ' . $e->getMessage());
            return response()->json(['error' => 'PayPal service unavailable'], 500);
        }
    }

    /**
     * Capture PayPal payment after user approval
     */
    public function capturePaypalOrder(Request $request)
    {
        $paypalOrderId = $request->input('orderID');
        if (!$paypalOrderId) {
            return response()->json(['error' => 'Missing PayPal order ID'], 400);
        }

        $paypal = DB::table('payment_methods')->where('slug', 'paypal')->first();
        $config = json_decode($paypal->config ?? '{}', true);
        $apiBase = ($config['mode'] ?? 'sandbox') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        // Get token
        $ch = curl_init($apiBase . '/v1/oauth2/token');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_USERPWD => ($config['client_id'] ?? '') . ':' . ($config['secret'] ?? ''),
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
        ]);
        $tokenResp = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $accessToken = $tokenResp['access_token'] ?? null;
        if (!$accessToken) {
            return response()->json(['error' => 'PayPal auth failed'], 500);
        }

        // Capture
        $ch = curl_init($apiBase . '/v2/checkout/orders/' . $paypalOrderId . '/capture');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
            ],
            CURLOPT_POSTFIELDS => '{}',
        ]);
        $captureResp = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (($captureResp['status'] ?? '') !== 'COMPLETED') {
            return response()->json(['error' => 'PayPal capture failed', 'details' => $captureResp], 400);
        }

        session(['paypal_captured' => true]);
        session(['paypal_capture_id' => $captureResp['id'] ?? $paypalOrderId]);

        return response()->json(['success' => true, 'status' => 'COMPLETED']);
    }

    /**
     * Validate promo code (AJAX)
     */
    public function validatePromo(Request $request)
    {
        $code = strtoupper(trim($request->input('code', '')));
        if (!$code) return response()->json(['valid' => false, 'message' => 'Enter a promo code']);

        $promo = DB::table('promo_codes')
            ->where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$promo) return response()->json(['valid' => false, 'message' => 'Invalid promo code']);
        if ($promo->expires_at && now()->greaterThan($promo->expires_at)) {
            return response()->json(['valid' => false, 'message' => 'This code has expired']);
        }
        if ($promo->starts_at && now()->lessThan($promo->starts_at)) {
            return response()->json(['valid' => false, 'message' => 'This code is not yet active']);
        }
        if ($promo->max_uses && $promo->used_count >= $promo->max_uses) {
            return response()->json(['valid' => false, 'message' => 'This code has reached its usage limit']);
        }

        $cart = session('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 1);
        }

        if ($subtotal < $promo->min_order_amount) {
            return response()->json(['valid' => false, 'message' => 'Minimum order £' . number_format($promo->min_order_amount, 2) . ' required']);
        }

        $discountAmount = $promo->type === 'percentage'
            ? round($subtotal * ($promo->value / 100), 2)
            : min($promo->value, $subtotal);

        session(['promo_code' => $code, 'promo_discount' => $discountAmount]);

        return response()->json([
            'valid' => true,
            'message' => ($promo->type === 'percentage' ? $promo->value . '% off' : '£' . number_format($promo->value, 2) . ' off') . ' applied!',
            'discount' => $discountAmount,
            'type' => $promo->type,
            'value' => $promo->value,
        ]);
    }

    /**
     * Get delivery methods for a postcode (AJAX)
     */
    public function getDeliveryMethods(Request $request)
    {
        $postcode = strtoupper(preg_replace('/\s+/', '', $request->input('postcode', '')));
        $prefix = preg_replace('/[0-9].*/', '', $postcode);

        $methods = DB::table('delivery_methods')
            ->leftJoin('delivery_zones', 'delivery_methods.zone_id', '=', 'delivery_zones.id')
            ->where('delivery_methods.is_active', true)
            ->orderBy('delivery_methods.sort_order')
            ->select('delivery_methods.*', 'delivery_zones.name as zone_name', 'delivery_zones.postcodes')
            ->get();

        $available = [];
        foreach ($methods as $m) {
            // If no zone (universal), or zone matches postcode prefix
            if (!$m->zone_id) {
                $available[] = $m;
            } else {
                $zonePostcodes = array_map('trim', explode(',', $m->postcodes ?? ''));
                if (in_array($prefix, $zonePostcodes)) {
                    $available[] = $m;
                }
            }
        }

        return response()->json($available);
    }

    // ── Helpers ──

    private function getStripeSecret(): ?string
    {
        $method = DB::table('payment_methods')->where('slug', 'stripe_card')->first();
        if (!$method || !$method->is_active) return null;
        $config = json_decode($method->config, true);
        return $config['secret_key'] ?? null;
    }

    public static function getDeliveryCost(string $slug): float
    {
        $method = DB::table('delivery_methods')->where('slug', $slug)->where('is_active', true)->first();
        return $method ? (float)$method->price : 0;
    }

    public static function calculateDiscount(?string $code, float $subtotal): float
    {
        if (!$code) return 0;
        $promo = DB::table('promo_codes')
            ->where('code', strtoupper(trim($code)))
            ->where('is_active', true)
            ->first();
        if (!$promo) return 0;
        if ($promo->expires_at && now()->greaterThan($promo->expires_at)) return 0;
        if ($subtotal < $promo->min_order_amount) return 0;
        return $promo->type === 'percentage'
            ? round($subtotal * ($promo->value / 100), 2)
            : min($promo->value, $subtotal);
    }
}
