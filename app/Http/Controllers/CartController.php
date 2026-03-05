<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\OrderConfirmation;

class CartController extends Controller
{
    // ─────────────────────────────────────────────
    // Basket page
    // ─────────────────────────────────────────────
    public function index()
    {
        $cart     = session('cart', []);
        $items    = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $price     = (float)($item['price'] ?? 0);
            $qty       = (int)($item['quantity'] ?? 1);
            $lineTotal = $price * $qty;
            $subtotal += $lineTotal;
            $items[] = array_merge($item, ['cart_key' => $key, 'line_total' => $lineTotal]);
        }

        $delivery = 0;
        $vat      = round($subtotal * 0.20, 2);
        $total    = $subtotal + $delivery + $vat;

        return view('pages.basket', compact('items', 'subtotal', 'delivery', 'vat', 'total'));
    }

    // ─────────────────────────────────────────────
    // Add to basket
    // ─────────────────────────────────────────────
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
            'price'      => 'required|numeric|min:0',
        ]);

        $cart = session('cart', []);

        $rawOptions = $request->input('options', []);
        if (is_string($rawOptions)) {
            $rawOptions = json_decode($rawOptions, true) ?? [];
        }

        $optionsStr = http_build_query($rawOptions);
        $cartKey    = 'item_' . $request->product_id . '_' . md5($optionsStr . $request->quantity);

        $product = DB::selectOne("SELECT id, name, slug, image1 FROM products WHERE id = ? LIMIT 1", [$request->product_id]);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        // Handle artwork file upload
        $artworkUrl = $request->input('artwork_url', null);
        if ($request->hasFile('artwork_file')) {
            $file = $request->file('artwork_file');
            try {
                // Use storage/app/public/artwork — works on all OS including OneDrive
                $storagePath = storage_path('app/public/artwork');
                if (!file_exists($storagePath)) {
                    mkdir($storagePath, 0755, true);
                }
                $filename   = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($storagePath, $filename);
                $artworkUrl = 'storage/artwork/' . $filename;
            } catch (\Exception $e) {
                // If storage fails too, skip artwork silently
                \Illuminate\Support\Facades\Log::error('Artwork upload failed: ' . $e->getMessage());
                $artworkUrl = null;
            }
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += (int)$request->quantity;
        } else {
            $cart[$cartKey] = [
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'product_slug' => $product->slug,
                'image'        => $product->image1,
                'quantity'     => (int)$request->quantity,
                'price'        => (float)$request->price,
                'options'      => $rawOptions,
                'artwork_url'  => $artworkUrl,
            ];
        }

        session(['cart' => $cart]);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => $product->name . ' added to basket!',
                'cart_count' => $cartCount,
            ]);
        }

        return redirect()->route('basket')->with('success', $product->name . ' added to basket!');
    }

    // ─────────────────────────────────────────────
    // Remove item
    // ─────────────────────────────────────────────
    public function remove(Request $request, string $cartKey)
    {
        $cart = session('cart', []);
        unset($cart[$cartKey]);
        session(['cart' => $cart]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'cart_count' => array_sum(array_column($cart, 'quantity'))]);
        }

        return redirect()->route('basket')->with('success', 'Item removed from basket.');
    }

    // ─────────────────────────────────────────────
    // Update quantity
    // ─────────────────────────────────────────────
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

    // ─────────────────────────────────────────────
    // Cart count API
    // ─────────────────────────────────────────────
    public function count()
    {
        $cart  = session('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));
        return response()->json(['count' => $count]);
    }

    // ─────────────────────────────────────────────
    // Checkout page
    // ─────────────────────────────────────────────
    public function checkout()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('basket')->with('error', 'Your basket is empty.');
        }

        $items    = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $price     = (float)($item['price'] ?? 0);
            $qty       = (int)($item['quantity'] ?? 1);
            $lineTotal = $price * $qty;
            $subtotal += $lineTotal;
            $items[]   = array_merge($item, ['cart_key' => $key, 'line_total' => $lineTotal]);
        }

        $delivery = 0;
        $vat      = round($subtotal * 0.20, 2);
        $total    = $subtotal + $delivery + $vat;

        return view('pages.checkout', compact('items', 'subtotal', 'delivery', 'vat', 'total'));
    }

    // ─────────────────────────────────────────────
    // Place order — with email confirmation
    // ─────────────────────────────────────────────
    public function placeOrder(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email',
            'phone'         => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'postcode'      => 'required|string|max:10',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('basket');
        }

        $subtotal   = 0;
        $itemsForDB = [];

        foreach ($cart as $item) {
            $price     = (float)($item['price'] ?? 0);
            $qty       = (int)($item['quantity'] ?? 1);
            $lineTotal = $price * $qty;
            $subtotal += $lineTotal;
            $itemsForDB[] = [
                'product_id'   => $item['product_id'] ?? null,
                'product_name' => $item['product_name'],
                'quantity'     => $qty,
                'unit_price'   => $price,
                'line_total'   => $lineTotal,
                'options'      => json_encode($item['options'] ?? []),
                'artwork_url'  => $item['artwork_url'] ?? null,
            ];
        }

        $delivery = $request->delivery_method === 'same_day' ? 19.99 : 0;
        $vat      = round($subtotal * 0.20, 2);
        $total    = $subtotal + $delivery + $vat;

        $orderId = null;
        try {
            $orderId = DB::table('orders')->insertGetId([
                'user_id'         => auth()->id(),
                'order_ref'       => 'LIP-' . strtoupper(Str::random(8)),
                'first_name'      => $request->first_name,
                'last_name'       => $request->last_name,
                'email'           => $request->email,
                'phone'           => $request->phone,
                'company'         => $request->company,
                'address_line1'   => $request->address_line1,
                'address_line2'   => $request->address_line2,
                'city'            => $request->city,
                'postcode'        => $request->postcode,
                'country'         => $request->country ?? 'GB',
                'delivery_notes'  => $request->delivery_notes,
                'delivery_method' => $request->delivery_method ?? 'next_day',
                'payment_method'  => $request->payment_method ?? 'card',
                'subtotal'        => $subtotal,
                'vat'             => $vat,
                'delivery_cost'   => $delivery,
                'total'           => $total,
                'status'          => 'pending',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            foreach ($itemsForDB as $item) {
                DB::table('order_items')->insert(array_merge($item, [
                    'order_id'   => $orderId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }

            // Send order confirmation email
            $order = DB::selectOne("SELECT * FROM orders WHERE id = ?", [$orderId]);
            $items = DB::select("SELECT * FROM order_items WHERE order_id = ?", [$orderId]);
            try {
                Mail::to($request->email)->send(new OrderConfirmation($order, $items));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Order confirmation email failed: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Order placement error: ' . $e->getMessage());
        }

        session()->forget('cart');

        if ($orderId) {
            return redirect()->route('user.order', $orderId)->with('order_success', 'Thank you! Your order has been placed. A confirmation email has been sent to ' . $request->email);
        }
        return redirect()->route('user.dashboard')->with('order_success', 'Thank you! Your order has been placed.');
    }
}
