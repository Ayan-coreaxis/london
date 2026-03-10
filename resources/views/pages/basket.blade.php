{{-- resources/views/pages/basket.blade.php --}}
@extends('layouts.app')

@section('title', 'Your Basket – London InstantPrint')
@section('meta_description', 'Review your order items and proceed to checkout.')

@section('styles')
<style>
/* ══════════════════════════════════════
   BASKET PAGE
══════════════════════════════════════ */
.basket-wrap {
    max-width: 1160px;
    margin: 0 auto;
    padding: 32px 24px 80px;
    font-family: 'Open Sans', sans-serif;
}

/* Breadcrumb */
.basket-breadcrumb {
    font-size: 13px;
    color: #888;
    margin-bottom: 24px;
}
.basket-breadcrumb a { color: #888; text-decoration: none; }
.basket-breadcrumb a:hover { color: #1e3a6e; }
.basket-breadcrumb span { margin: 0 6px; }

/* Steps bar */
.basket-steps {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 36px;
    background: #f7f7f7;
    border-radius: 10px;
    padding: 16px 28px;
}
.basket-step {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #aaa;
    font-family: 'Montserrat', sans-serif;
    flex: 1;
    position: relative;
}
.basket-step.active { color: #1e3a6e; }
.basket-step.done   { color: #3c9c3c; }
.step-num {
    width: 28px; height: 28px;
    border-radius: 50%;
    border: 2px solid #ccc;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700;
    flex-shrink: 0;
    background: #fff;
}
.basket-step.active .step-num { border-color: #1e3a6e; color: #1e3a6e; }
.basket-step.done   .step-num { border-color: #3c9c3c; background: #3c9c3c; color: #fff; }
.step-connector {
    flex: 1;
    height: 2px;
    background: #e0e0e0;
    margin: 0 12px;
}
.step-connector.done { background: #3c9c3c; }

/* Main grid */
.basket-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 28px;
    align-items: start;
}

/* Left – items */
.basket-items-panel {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 10px;
    overflow: hidden;
}
.basket-panel-hdr {
    padding: 18px 24px;
    background: #f9f9f9;
    border-bottom: 1px solid #e8e8e8;
    font-family: 'Montserrat', sans-serif;
    font-size: 15px;
    font-weight: 700;
    color: #222;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.basket-panel-hdr .item-count {
    font-size: 12px;
    font-weight: 600;
    color: #888;
    background: #eee;
    padding: 3px 10px;
    border-radius: 20px;
}

/* Each item row */
.basket-item {
    padding: 20px 24px;
    border-bottom: 1px solid #f0f0f0;
    display: grid;
    grid-template-columns: 88px 1fr auto;
    gap: 16px;
    align-items: center;
}
.basket-item:last-child { border-bottom: none; }
.basket-item-thumb {
    width: 88px; height: 66px;
    border-radius: 7px;
    overflow: hidden;
    background: #eef3ff;
    flex-shrink: 0;
}
.basket-item-thumb img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
}
.basket-item-thumb .thumb-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    color: #1e3a6e;
    font-size: 28px;
}
.basket-item-name {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: #1e3a6e;
    margin-bottom: 4px;
}
.basket-item-opts {
    font-size: 12px;
    color: #777;
    margin-bottom: 8px;
    line-height: 1.5;
}
.basket-item-opts span {
    display: inline-block;
    background: #f3f3f3;
    padding: 2px 8px;
    border-radius: 4px;
    margin-right: 4px;
    margin-bottom: 2px;
}
.basket-item-qty-row {
    display: flex;
    align-items: center;
    gap: 12px;
}
.qty-ctrl {
    display: flex;
    align-items: center;
    border: 1.5px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
}
.qty-ctrl button {
    width: 30px; height: 30px;
    border: none;
    background: #f7f7f7;
    font-size: 16px;
    font-weight: 700;
    color: #555;
    cursor: pointer;
    transition: background 0.15s;
    line-height: 1;
}
.qty-ctrl button:hover { background: #e0e0e0; }
.qty-ctrl input {
    width: 40px; height: 30px;
    border: none;
    border-left: 1.5px solid #ddd;
    border-right: 1.5px solid #ddd;
    text-align: center;
    font-size: 13px;
    font-weight: 700;
    font-family: 'Open Sans', sans-serif;
    outline: none;
}
.basket-remove-btn {
    font-size: 12px;
    color: #e8352a;
    background: none;
    border: none;
    cursor: pointer;
    font-family: 'Open Sans', sans-serif;
    padding: 0;
    text-decoration: underline;
    transition: color 0.2s;
}
.basket-remove-btn:hover { color: #b01a10; }
.basket-item-price {
    text-align: right;
}
.basket-item-price .unit {
    font-size: 11px;
    color: #aaa;
    margin-bottom: 2px;
}
.basket-item-price .line-total {
    font-family: 'Montserrat', sans-serif;
    font-size: 18px;
    font-weight: 800;
    color: #1e3a6e;
}
.basket-item-price .per-unit {
    font-size: 11px;
    color: #888;
}

/* Artwork badge */
.artwork-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    color: #3c9c3c;
    background: #f0faf0;
    padding: 3px 8px;
    border-radius: 4px;
    border: 1px solid #c6e8c6;
    margin-top: 4px;
}
.artwork-badge-pending {
    color: #d08800;
    background: #fffbf0;
    border-color: #ffe099;
}

/* Empty state */
.basket-empty {
    padding: 60px 24px;
    text-align: center;
    color: #888;
}
.basket-empty svg { margin-bottom: 16px; color: #ccc; }
.basket-empty h3 {
    font-family: 'Montserrat', sans-serif;
    font-size: 18px;
    font-weight: 700;
    color: #555;
    margin-bottom: 8px;
}
.basket-empty a {
    display: inline-block;
    margin-top: 16px;
    padding: 11px 28px;
    background: #1e3a6e;
    color: #fff;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 700;
    font-size: 14px;
    font-family: 'Montserrat', sans-serif;
    transition: background 0.2s;
}
.basket-empty a:hover { background: #e8352a; }

/* Promo code */
.promo-row {
    padding: 16px 24px;
    border-top: 1px solid #e8e8e8;
    display: flex;
    gap: 10px;
    background: #fafafa;
}
.promo-row input {
    flex: 1;
    height: 40px;
    border: 1.5px solid #ddd;
    border-radius: 6px;
    padding: 0 12px;
    font-size: 13px;
    font-family: 'Open Sans', sans-serif;
    outline: none;
    transition: border-color 0.2s;
}
.promo-row input:focus { border-color: #1e3a6e; }
.promo-row button {
    height: 40px;
    padding: 0 18px;
    background: #1e3a6e;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Montserrat', sans-serif;
    transition: background 0.2s;
    white-space: nowrap;
}
.promo-row button:hover { background: #162d56; }

/* ── Right panel – Summary ── */
.basket-summary {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 10px;
    overflow: hidden;
    position: sticky;
    top: 90px;
}
.summary-hdr {
    padding: 18px 22px;
    background: #1e3a6e;
    color: #fff;
    font-family: 'Montserrat', sans-serif;
    font-size: 15px;
    font-weight: 700;
}
.summary-body { padding: 20px 22px; }
.summary-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    color: #555;
    margin-bottom: 10px;
}
.summary-line .label { color: #666; }
.summary-line .val   { font-weight: 600; color: #222; }
.summary-line.free   .val { color: #3c9c3c; font-weight: 700; }
.summary-divider { height: 1px; background: #eee; margin: 12px 0; }
.summary-line.total .label {
    font-family: 'Montserrat', sans-serif;
    font-size: 15px; font-weight: 800; color: #1e3a6e;
}
.summary-line.total .val {
    font-family: 'Montserrat', sans-serif;
    font-size: 22px; font-weight: 900; color: #1e3a6e;
}
.summary-vat-note {
    font-size: 11px;
    color: #aaa;
    text-align: right;
    margin-bottom: 18px;
}

.btn-checkout {
    display: block;
    width: 100%;
    padding: 15px;
    background: #f5c800;
    color: #1a1a1a;
    border: none;
    border-radius: 7px;
    font-family: 'Montserrat', sans-serif;
    font-size: 15px;
    font-weight: 900;
    text-align: center;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s;
    letter-spacing: 0.3px;
}
.btn-checkout:hover {
    background: #e0b400;
    transform: translateY(-1px);
}
.btn-checkout:active { transform: translateY(0); }

.btn-continue-shopping {
    display: block;
    width: 100%;
    margin-top: 10px;
    padding: 12px;
    background: none;
    border: 2px solid #ddd;
    border-radius: 7px;
    font-family: 'Montserrat', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: #555;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: border-color 0.2s, color 0.2s;
}
.btn-continue-shopping:hover { border-color: #1e3a6e; color: #1e3a6e; }

/* Trust badges */
.trust-badges {
    margin-top: 18px;
    padding-top: 18px;
    border-top: 1px solid #f0f0f0;
}
.trust-badge {
    display: flex;
    align-items: center;
    gap: 9px;
    font-size: 12px;
    color: #555;
    margin-bottom: 9px;
}
.trust-badge svg { color: #3c9c3c; flex-shrink: 0; }
.trust-badge:last-child { margin-bottom: 0; }

/* Delivery info banner */
.delivery-banner {
    background: #f0faf0;
    border: 1px solid #c6e8c6;
    border-radius: 8px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #2a7a2a;
    font-weight: 600;
    margin-bottom: 20px;
}
.delivery-banner svg { flex-shrink: 0; }

/* Flash message */
.flash-success {
    background: #f0faf0;
    border: 1px solid #c6e8c6;
    border-radius: 8px;
    padding: 12px 18px;
    color: #2a7a2a;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

@media (max-width: 900px) {
    .basket-grid { grid-template-columns: 1fr; }
    .basket-summary { position: static; }
}
@media (max-width: 600px) {
    .basket-item { grid-template-columns: 72px 1fr; }
    .basket-item-price { grid-column: 2; }
    .basket-steps { padding: 12px 16px; }
    .basket-step span.step-label { display: none; }
}
</style>
@endsection

@section('content')
<div class="basket-wrap">

    {{-- Breadcrumb --}}
    <div class="basket-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>›</span>
        <strong>Basket</strong>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="flash-success">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Steps bar --}}
    <div class="basket-steps">
        <div class="basket-step active">
            <div class="step-num">1</div>
            <span class="step-label">Basket</span>
        </div>
        <div class="step-connector"></div>
        <div class="basket-step">
            <div class="step-num">2</div>
            <span class="step-label">Login / Register</span>
        </div>
        <div class="step-connector"></div>
        <div class="basket-step">
            <div class="step-num">3</div>
            <span class="step-label">Checkout</span>
        </div>
        <div class="step-connector"></div>
        <div class="basket-step">
            <div class="step-num">4</div>
            <span class="step-label">Confirmation</span>
        </div>
    </div>

    @if(empty($items))
        {{-- Empty basket --}}
        <div class="basket-items-panel">
            <div class="basket-panel-hdr">Your Basket</div>
            <div class="basket-empty">
                <svg width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 01-8 0"/>
                </svg>
                <h3>Your basket is empty</h3>
                <p>Add some print products to get started.</p>
                <a href="{{ route('products') }}">Browse All Products</a>
            </div>
        </div>

    @else
        {{-- Delivery Banner --}}
        <div class="delivery-banner">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11a2 2 0 012 2v3"/>
                <rect x="9" y="11" width="14" height="10" rx="2"/>
                <circle cx="12" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
            </svg>
            🎉 Free Next Day Delivery on all orders!
        </div>

        <div class="basket-grid">

            {{-- LEFT: Items --}}
            <div>
                <div class="basket-items-panel">
                    <div class="basket-panel-hdr">
                        Your Basket
                        <span class="item-count">{{ count($items) }} {{ count($items) == 1 ? 'item' : 'items' }}</span>
                    </div>

                    @foreach($items as $item)
                    <div class="basket-item" id="basket-row-{{ $loop->index }}">
                        {{-- Thumbnail --}}
                        <div class="basket-item-thumb">
                            @if(!empty($item['image']))
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['product_name'] }}">
                            @else
                                <div class="thumb-placeholder">🖨️</div>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div>
                            <div class="basket-item-name">
                                <a href="{{ route('product.show', $item['product_slug']) }}" style="color:inherit;text-decoration:none;">
                                    {{ $item['product_name'] }}
                                </a>
                            </div>

                            @if(!empty($item['options']))
                            <div class="basket-item-opts">
                                @foreach($item['options'] as $optName => $optVal)
                                    <span>{{ ucfirst($optName) }}: {{ $optVal }}</span>
                                @endforeach
                            </div>
                            @endif

                            @if(!empty($item['artwork_url']))
                                <div class="artwork-badge">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Artwork Uploaded:
                                    <a href="{{ asset($item['artwork_url']) }}" target="_blank" style="color:#2a7a2a;font-weight:700;margin-left:4px;">
                                        View File
                                    </a>
                                    {{-- Replace artwork --}}
                                    <form method="POST" action="{{ route('cart.artwork.upload', $item['cart_key']) }}" enctype="multipart/form-data" style="display:inline;margin-left:8px;">
                                        @csrf
                                        <label style="color:#1e3a6e;font-weight:700;cursor:pointer;font-size:12px;">
                                            Replace
                                            <input type="file" name="artwork_file" accept=".pdf,.jpg,.jpeg,.png,.ai,.eps,.tiff" style="display:none;" onchange="this.closest('form').submit();">
                                        </label>
                                    </form>
                                </div>
                            @else
                                <div class="artwork-badge artwork-badge-pending">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                                    Artwork Pending —
                                    <form method="POST" action="{{ route('cart.artwork.upload', $item['cart_key']) }}" enctype="multipart/form-data" style="display:inline;margin-left:4px;">
                                        @csrf
                                        <label style="color:#c87600;font-weight:700;cursor:pointer;text-decoration:underline;">
                                            Upload Now
                                            <input type="file" name="artwork_file" accept=".pdf,.jpg,.jpeg,.png,.ai,.eps,.tiff" style="display:none;" onchange="this.closest('form').submit();">
                                        </label>
                                    </form>
                                </div>
                            @endif

                            {{-- Qty controls --}}
                            <form method="POST" action="{{ route('cart.update', $item['cart_key']) }}" class="basket-item-qty-row" style="margin-top:10px;">
                                @csrf
                                <div class="qty-ctrl">
                                    <button type="button" onclick="changeQty(this, -1)">−</button>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99999" class="qty-input">
                                    <button type="button" onclick="changeQty(this, 1)">+</button>
                                </div>
                                <button type="submit" style="font-size:12px;color:#888;background:none;border:none;cursor:pointer;text-decoration:underline;">Update</button>
                            </form>

                            {{-- Remove --}}
                            <form method="POST" action="{{ route('cart.remove', $item['cart_key']) }}" style="margin-top:6px;" onsubmit="return confirm('Remove this item?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="basket-remove-btn">Remove item</button>
                            </form>
                        </div>

                        {{-- Price --}}
                        <div class="basket-item-price">
                            <div class="unit">Unit price</div>
                            <div class="per-unit">£{{ number_format($item['price'], 2) }}</div>
                            <div style="height:8px;"></div>
                            <div class="unit">Total</div>
                            <div class="line-total">£{{ number_format($item['line_total'], 2) }}</div>
                        </div>
                    </div>
                    @endforeach

                    {{-- Promo code --}}
                    <div class="promo-row">
                        <input type="text" placeholder="Enter discount / promo code" id="promoInput">
                        <button onclick="applyPromo()">Apply Code</button>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Summary --}}
            <div>
                <div class="basket-summary">
                    <div class="summary-hdr">Order Summary</div>
                    <div class="summary-body">

                        <div class="summary-line">
                            <span class="label">Subtotal (ex. VAT)</span>
                            <span class="val">£{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="summary-line free">
                            <span class="label">Delivery</span>
                            <span class="val">FREE</span>
                        </div>
                        <div class="summary-line">
                            <span class="label">VAT (20%)</span>
                            <span class="val">£{{ number_format($vat, 2) }}</span>
                        </div>

                        <div class="summary-divider"></div>

                        <div class="summary-line total">
                            <span class="label">Total (inc. VAT)</span>
                            <span class="val">£{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="summary-vat-note">* All prices include 20% UK VAT</div>

                        {{-- Checkout Button - goes to login/register if guest --}}
                        @auth
                            <a href="{{ route('checkout') }}" class="btn-checkout">
                                Proceed to Checkout →
                            </a>
                        @else
                            <a href="{{ route('checkout.login') }}" class="btn-checkout">
                                Proceed to Checkout →
                            </a>
                        @endauth

                        <a href="{{ route('products') }}" class="btn-continue-shopping">
                            ← Continue Shopping
                        </a>

                        {{-- Trust badges --}}
                        <div class="trust-badges">
                            <div class="trust-badge">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Free next day delivery
                            </div>
                            <div class="trust-badge">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Secure SSL checkout
                            </div>
                            <div class="trust-badge">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Trade customers only
                            </div>
                            <div class="trust-badge">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                100% satisfaction guarantee
                            </div>
                        </div>

                        {{-- Payment logos --}}
                        <div style="margin-top:14px; padding-top:14px; border-top:1px solid #f0f0f0; text-align:center;">
                            <div style="font-size:11px; color:#aaa; margin-bottom:8px;">We accept</div>
                            <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/40px-Mastercard-logo.svg.png" alt="Mastercard" height="22">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/60px-Visa_Inc._logo.svg.png" alt="Visa" height="22">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/80px-PayPal.svg.png" alt="PayPal" height="22">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- end grid --}}
    @endif

</div>
@endsection

@section('scripts')
<script>
function changeQty(btn, delta) {
    const input = btn.parentElement.querySelector('.qty-input');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    input.value = val;
}

function applyPromo() {
    const code = document.getElementById('promoInput').value.trim();
    if (!code) { alert('Please enter a promo code.'); return; }
    // Future: AJAX call to validate promo
    alert('Promo code "' + code + '" applied! (Feature coming soon)');
}
</script>
@endsection