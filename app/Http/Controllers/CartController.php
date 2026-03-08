<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Mail\OrderConfirmation;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $items = [];
        $subtotal = 0;
        foreach ($cart as $key => $item) {
            $price = (float)($item['price'] ?? 0);
            $qty = (int)($item['quantity'] ?? 1);
            $lineTotal = ($item['price_is_total'] ?? false) ? $price : $price * $qty;
            $subtotal += $lineTotal;
            $items[] = array_merge($item, ['cart_key' => $key, 'line_total' => $lineTotal]);
        }
        $discount = (float)session('promo_discount', 0);
        $promoCode = session('promo_code', '');
        $delivery = 0;
        $vat = round(($subtotal - $discount) * 0.20, 2);
        $total = $subtotal - $discount + $delivery + $vat;
        return view('pages.basket', compact('items', 'subtotal', 'delivery', 'vat', 'total', 'discount', 'promoCode'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);
        $cart = session('cart', []);
        $product = DB::selectOne("SELECT id,name,slug,image1,base_price FROM products WHERE id=? LIMIT 1", [$request->product_id]);
        if (!$product) return response()->json(['success' => false, 'message' => 'Product not found'], 404);

        // SERVER-SIDE price calculation
        $price = (float)$product->base_price;
        $rawOptions = $request->input('options', []);
        if (is_string($rawOptions)) $rawOptions = json_decode($rawOptions, true) ?? [];

        foreach ($rawOptions as $optKey => $optVal) {
            $cleanVal = preg_replace('/\s*\(\+£[\d.]+\)\s*$/', '', $optVal);
            $extra = DB::selectOne(
                "SELECT ov.extra_price FROM option_values ov JOIN product_options po ON po.id=ov.option_id WHERE po.product_id=? AND TRIM(ov.value_label)=?",
                [$product->id, trim($cleanVal)]
            );
            if ($extra) $price += (float)$extra->extra_price;
        }

        // Variation pricing verification
        $priceIsTotal = false;
        if ($request->has('variation_price') && $request->variation_price > 0) {
            $vp = (float)$request->variation_price;
            $verify = DB::selectOne(
                "SELECT price FROM product_variation_pricing WHERE variation_id IN (SELECT id FROM product_variations WHERE product_id=?) AND price=? LIMIT 1",
                [$product->id, $vp]
            );
            if ($verify) { $price = $vp; $priceIsTotal = true; }
        }

        // Turnaround pricing verification
        if ($request->has('turnaround_price') && $request->turnaround_price > 0) {
            $tp = (float)$request->turnaround_price;
            $verify = DB::selectOne(
                "SELECT price FROM product_pricing WHERE turnaround_id IN (SELECT id FROM product_turnarounds WHERE product_id=?) AND price=? LIMIT 1",
                [$product->id, $tp]
            );
            if ($verify) { $price = $tp; $priceIsTotal = true; }
        }

        $optionsStr = http_build_query($rawOptions);
        $cartKey = 'item_' . $request->product_id . '_' . md5($optionsStr . $request->quantity . $price);

        // Safety: if price ended up as 0, use base_price
        if ($price <= 0) {
            $price = (float)$product->base_price;
        }

        $artworkUrl = $request->input('artwork_url', null);
        if ($request->hasFile('artwork_file')) {
            $request->validate(['artwork_file' => 'file|max:102400|mimes:pdf,jpg,jpeg,png,ai,eps,tiff,tif']);
            $file = $request->file('artwork_file');
            // FIX: Check file is valid before moving
            if ($file && $file->isValid()) {
                try {
                    $storagePath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'artwork');
                    if (!file_exists($storagePath)) mkdir($storagePath, 0755, true);
                    $ext      = strtolower($file->getClientOriginalExtension());
                    $filename = time() . '_' . uniqid() . '.' . $ext;
                    $file->move($storagePath, $filename);
                    // Verify file saved
                    if (file_exists($storagePath . DIRECTORY_SEPARATOR . $filename)) {
                        $artworkUrl = 'storage/artwork/' . $filename;
                    } else {
                        Log::error('Cart artwork move failed — file missing after move');
                    }
                } catch (\Exception $e) {
                    Log::error('Cart artwork upload failed: ' . $e->getMessage());
                }
            } else {
                Log::warning('Cart artwork file invalid', ['error' => $file ? $file->getError() : 'null']);
            }
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += (int)$request->quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id, 'product_name' => $product->name,
                'product_slug' => $product->slug, 'image' => $product->image1,
                'quantity' => (int)$request->quantity, 'price' => $price,
                'options' => $rawOptions, 'artwork_url' => $artworkUrl,
                'price_is_total' => $priceIsTotal,
            ];
        }
        session(['cart' => $cart]);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $product->name . ' added to basket!', 'cart_count' => $cartCount]);
        }
        return redirect()->route('basket')->with('success', $product->name . ' added to basket!');
    }

    public function remove(Request $request, string $cartKey)
    {
        $cart = session('cart', []);
        unset($cart[$cartKey]);
        session(['cart' => $cart]);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'cart_count' => array_sum(array_column($cart, 'quantity'))]);
        }
        return redirect()->route('basket')->with('success', 'Item removed.');
    }

    public function update(Request $request, string $cartKey)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart = session('cart', []);
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = (int)$request->quantity;
            session(['cart' => $cart]);
        }
        return redirect()->route('basket');
    }

    public function uploadArtwork(Request $request, string $cartKey)
    {
        $request->validate([
            'artwork_file' => 'required|file|max:102400|mimes:pdf,jpg,jpeg,png,ai,eps,tiff,tif',
        ]);

        $cart = session('cart', []);
        if (!isset($cart[$cartKey])) {
            return redirect()->route('basket')->with('error', 'Cart item not found.');
        }

        $file = $request->file('artwork_file');
        if ($file && $file->isValid()) {
            try {
                $storagePath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'artwork');
                if (!file_exists($storagePath)) mkdir($storagePath, 0755, true);
                $ext = strtolower($file->getClientOriginalExtension());
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $file->move($storagePath, $filename);
                if (file_exists($storagePath . DIRECTORY_SEPARATOR . $filename)) {
                    $cart[$cartKey]['artwork_url'] = 'storage/artwork/' . $filename;
                    session(['cart' => $cart]);
                    return redirect()->route('basket')->with('success', 'Artwork uploaded!');
                }
            } catch (\Exception $e) {
                Log::error('Cart artwork upload: ' . $e->getMessage());
            }
        }

        return redirect()->route('basket')->with('error', 'Upload failed. Check file type and size.');
    }

    public function count()
    {
        return response()->json(['count' => array_sum(array_column(session('cart', []), 'quantity'))]);
    }

    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('basket')->with('error', 'Your basket is empty.');

        $items = []; $subtotal = 0;
        foreach ($cart as $key => $item) {
            $price = (float)($item['price'] ?? 0);
            $qty = (int)($item['quantity'] ?? 1);
            $lineTotal = ($item['price_is_total'] ?? false) ? $price : $price * $qty;
            $subtotal += $lineTotal;
            $items[] = array_merge($item, ['cart_key' => $key, 'line_total' => $lineTotal]);
        }

        $discount = (float)session('promo_discount', 0);
        $promoCode = session('promo_code', '');
        $delivery = 0;
        $vat = round(($subtotal - $discount) * 0.20, 2);
        $total = $subtotal - $discount + $delivery + $vat;

        // Load dynamic payment methods
        $paymentMethods = collect([]);
        try { $paymentMethods = DB::table('payment_methods')->where('is_active', true)->orderBy('sort_order')->get(); } catch (\Exception $e) {}

        // Load delivery methods
        $deliveryMethods = collect([]);
        try { $deliveryMethods = DB::table('delivery_methods')->where('is_active', true)->orderBy('sort_order')->get(); } catch (\Exception $e) {}

        // Stripe public key
        $stripePublicKey = '';
        $stripeMethod = $paymentMethods->firstWhere('provider', 'stripe');
        if ($stripeMethod) {
            $config = json_decode($stripeMethod->config, true);
            $stripePublicKey = $config['public_key'] ?? '';
        }

        // PayPal config
        $paypalClientId = ''; $paypalCurrency = 'GBP';
        $paypalMethod = $paymentMethods->firstWhere('provider', 'paypal');
        if ($paypalMethod) {
            $config = json_decode($paypalMethod->config, true);
            $paypalClientId = $config['client_id'] ?? '';
            $paypalCurrency = $config['currency'] ?? 'GBP';
        }

        // Fallback: also load old-style payment settings for backward compat
        $paySettings = [];
        try {
            $rows = DB::select("SELECT `key`,`value` FROM site_settings WHERE `group`='payment'");
            foreach ($rows as $r) $paySettings[$r->key] = $r->value;
        } catch (\Exception $e) {}

        return view('pages.checkout', compact(
            'items', 'subtotal', 'delivery', 'vat', 'total', 'discount', 'promoCode',
            'paymentMethods', 'deliveryMethods', 'stripePublicKey', 'paypalClientId', 'paypalCurrency', 'paySettings'
        ));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100', 'last_name' => 'required|string|max:100',
            'email' => 'required|email', 'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255', 'city' => 'required|string|max:100',
            'postcode' => 'required|string|max:10',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('basket');

        $subtotal = 0; $itemsForDB = [];
        foreach ($cart as $item) {
            $price = (float)($item['price'] ?? 0);
            $qty = (int)($item['quantity'] ?? 1);
            $lineTotal = ($item['price_is_total'] ?? false) ? $price : $price * $qty;
            $subtotal += $lineTotal;
            $itemsForDB[] = [
                'product_id' => $item['product_id'] ?? null, 'product_name' => $item['product_name'],
                'quantity' => $qty, 'unit_price' => $price, 'line_total' => $lineTotal,
                'options' => json_encode($item['options'] ?? []), 'artwork_url' => $item['artwork_url'] ?? null,
            ];
        }

        $deliverySlug = $request->delivery_method ?? 'next_day_free';
        $delivery = 0;
        try {
            $dm = DB::table('delivery_methods')->where('slug', $deliverySlug)->first();
            if ($dm) $delivery = (float)$dm->price;
        } catch (\Exception $e) {
            $delivery = $deliverySlug === 'same_day' ? 19.99 : 0;
        }

        $promoCode = session('promo_code');
        $discount = 0;
        if ($promoCode) {
            try { $discount = PaymentController::calculateDiscount($promoCode, $subtotal); } catch (\Exception $e) {}
        }

        $vat = round(($subtotal - $discount) * 0.20, 2);
        $total = $subtotal - $discount + $delivery + $vat;

        $paymentMethod = $request->payment_method ?? 'stripe_card';
        $paymentStatus = 'pending';
        $stripePI = $request->input('stripe_payment_intent') ?? session('stripe_payment_intent');
        $paypalOID = session('paypal_capture_id') ?? session('paypal_order_id');

        // Verify payments
        if ($paymentMethod === 'stripe_card' && $stripePI) {
            try {
                $method = DB::table('payment_methods')->where('slug', 'stripe_card')->first();
                if ($method) {
                    $config = json_decode($method->config, true);
                    $secret = $config['secret_key'] ?? '';
                    if ($secret) {
                        $ch = curl_init("https://api.stripe.com/v1/payment_intents/{$stripePI}");
                        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_USERPWD => $secret . ':']);
                        $resp = json_decode(curl_exec($ch), true);
                        curl_close($ch);
                        if (($resp['status'] ?? '') === 'succeeded') $paymentStatus = 'paid';
                    }
                }
            } catch (\Exception $e) { Log::error('Stripe verify: ' . $e->getMessage()); }
        } elseif ($paymentMethod === 'paypal' && session('paypal_captured')) {
            $paymentStatus = 'paid';
        } elseif (in_array($paymentMethod, ['bank_transfer'])) {
            $paymentStatus = 'awaiting_payment';
        } elseif ($paymentMethod === 'invoice') {
            $paymentStatus = 'invoice';
        }

        $orderId = null;
        try {
            $orderData = [
                'user_id' => auth()->id(), 'order_ref' => 'LIP-' . strtoupper(Str::random(8)),
                'first_name' => $request->first_name, 'last_name' => $request->last_name,
                'email' => $request->email, 'phone' => $request->phone, 'company' => $request->company,
                'address_line1' => $request->address_line1, 'address_line2' => $request->address_line2,
                'city' => $request->city, 'postcode' => $request->postcode,
                'country' => $request->country ?? 'GB', 'delivery_notes' => $request->delivery_notes,
                'delivery_method' => $deliverySlug, 'payment_method' => $paymentMethod,
                'subtotal' => $subtotal, 'vat' => $vat,
                'delivery_cost' => $delivery, 'total' => $total,
                'status' => ($paymentStatus === 'paid') ? 'confirmed' : 'pending',
                'created_at' => now(), 'updated_at' => now(),
            ];
            // Try adding new columns — they may not exist if migration not run
            try {
                $orderData['stripe_payment_intent'] = $stripePI;
                $orderData['paypal_order_id'] = $paypalOID;
                $orderData['payment_status'] = $paymentStatus;
                $orderData['promo_code'] = $promoCode ?: null;
                $orderData['discount'] = $discount;
                $orderId = DB::table('orders')->insertGetId($orderData);
            } catch (\Exception $colErr) {
                // Remove new columns and retry
                unset($orderData['stripe_payment_intent'], $orderData['paypal_order_id'], $orderData['payment_status'], $orderData['promo_code'], $orderData['discount']);
                $orderId = DB::table('orders')->insertGetId($orderData);
            }

            foreach ($itemsForDB as $item) {
                DB::table('order_items')->insert(array_merge($item, ['order_id' => $orderId, 'created_at' => now(), 'updated_at' => now()]));
            }

            // Log transaction
            try {
                DB::table('payment_transactions')->insert([
                    'order_id' => $orderId, 'payment_method' => $paymentMethod,
                    'transaction_id' => $stripePI ?: $paypalOID, 'status' => $paymentStatus,
                    'amount' => $total, 'currency' => 'GBP', 'created_at' => now(), 'updated_at' => now(),
                ]);
            } catch (\Exception $e) {}

            if ($promoCode) {
                try { DB::table('promo_codes')->where('code', $promoCode)->increment('used_count'); } catch (\Exception $e) {}
            }

            $order = DB::selectOne("SELECT * FROM orders WHERE id=?", [$orderId]);
            $items = DB::select("SELECT * FROM order_items WHERE order_id=?", [$orderId]);
            try { Mail::to($request->email)->send(new OrderConfirmation($order, $items)); } catch (\Exception $e) { Log::error('Email: ' . $e->getMessage()); }

        } catch (\Exception $e) {
            Log::error('Order error: ' . $e->getMessage());
            return back()->with('error', 'Error placing order. Please try again.')->withInput();
        }

        session()->forget(['cart', 'promo_code', 'promo_discount', 'stripe_payment_intent', 'paypal_order_id', 'paypal_captured', 'paypal_capture_id', 'order_total_calculated']);

        if ($orderId) return redirect()->route('user.order', $orderId)->with('order_success', 'Thank you! Order placed. Confirmation sent to ' . $request->email);
        return redirect()->route('user.dashboard')->with('order_success', 'Thank you! Your order has been placed.');
    }
}
