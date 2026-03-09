{{-- resources/views/pages/checkout.blade.php --}}
@extends('layouts.app')

@section('title', 'Checkout – London InstantPrint')
@section('meta_description', 'Complete your order securely with London InstantPrint.')

@section('styles')
<style>
/* ══════════════════════════════════════
   CHECKOUT PAGE
══════════════════════════════════════ */
.checkout-wrap {
    max-width: 1160px;
    margin: 0 auto;
    padding: 32px 24px 80px;
    font-family: 'Open Sans', sans-serif;
}

/* Breadcrumb */
.checkout-breadcrumb {
    font-size: 13px; color: #888; margin-bottom: 24px;
}
.checkout-breadcrumb a { color: #888; text-decoration: none; }
.checkout-breadcrumb a:hover { color: #1e3a6e; }
.checkout-breadcrumb span { margin: 0 6px; }

/* Steps bar */
.checkout-steps {
    display: flex; align-items: center;
    background: #f7f7f7; border-radius: 10px;
    padding: 16px 28px; margin-bottom: 36px;
}
.checkout-step {
    display: flex; align-items: center; gap: 10px;
    font-size: 13px; font-weight: 600;
    color: #aaa; font-family: 'Montserrat', sans-serif;
    flex: 1;
}
.checkout-step.done   { color: #3c9c3c; }
.checkout-step.active { color: #1e3a6e; }
.c-step-num {
    width: 28px; height: 28px; border-radius: 50%;
    border: 2px solid #ccc; display: flex;
    align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; background: #fff; flex-shrink: 0;
}
.checkout-step.done   .c-step-num { border-color:#3c9c3c; background:#3c9c3c; color:#fff; }
.checkout-step.active .c-step-num { border-color:#1e3a6e; color:#1e3a6e; }
.c-step-conn {
    flex: 1; height: 2px; background: #e0e0e0; margin: 0 12px;
}
.c-step-conn.done { background: #3c9c3c; }

/* Grid */
.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 28px;
    align-items: start;
}

/* Form panels */
.co-panel {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 10px;
    margin-bottom: 20px;
    overflow: hidden;
}
.co-panel-hdr {
    padding: 16px 24px;
    background: #f9f9f9;
    border-bottom: 1px solid #e8e8e8;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px; font-weight: 700; color: #1e3a6e;
    display: flex; align-items: center; gap: 8px;
}
.co-panel-hdr svg { color: #1e3a6e; flex-shrink: 0; }
.co-panel-body { padding: 22px 24px; }

/* Form fields */
.co-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 14px;
}
.co-row.single { grid-template-columns: 1fr; }
.co-field label {
    display: block;
    font-size: 12px; font-weight: 700;
    color: #555; margin-bottom: 5px;
    font-family: 'Open Sans', sans-serif;
    text-transform: uppercase; letter-spacing: 0.4px;
}
.co-field label .req { color: #e8352a; margin-left: 2px; }
.co-field input,
.co-field select,
.co-field textarea {
    width: 100%;
    height: 44px;
    border: 1.5px solid #ddd;
    border-radius: 7px;
    padding: 0 13px;
    font-size: 14px;
    font-family: 'Open Sans', sans-serif;
    color: #222;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    background: #fafafa;
    box-sizing: border-box;
}
.co-field textarea {
    height: 80px;
    padding: 12px 13px;
    resize: vertical;
}
.co-field input:focus,
.co-field select:focus,
.co-field textarea:focus {
    border-color: #1e3a6e;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(30,58,110,0.08);
}
.co-field input.error { border-color: #e8352a; }
.field-error { font-size: 11px; color: #e8352a; margin-top: 3px; }

/* Delivery method toggle */
.delivery-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 6px;
}
.delivery-opt {
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 12px 14px;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.delivery-opt.selected {
    border-color: #1e3a6e;
    background: #f0f4ff;
}
.delivery-opt input[type="radio"] {
    width: auto; height: auto;
    margin: 2px 0 0;
    flex-shrink: 0;
}
.delivery-opt-label {
    font-size: 13px; font-weight: 700;
    color: #222; font-family: 'Montserrat', sans-serif;
    display: block; margin-bottom: 2px;
}
.delivery-opt-sub {
    font-size: 11px; color: #888;
}
.delivery-opt-price {
    font-size: 13px; font-weight: 800;
    color: #3c9c3c;
}

/* Payment method */
.payment-methods { display: flex; flex-direction: column; gap: 10px; }
.payment-opt {
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 13px 16px;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    display: flex;
    align-items: center;
    gap: 12px;
}
.payment-opt.selected { border-color: #1e3a6e; background: #f0f4ff; }
.payment-opt input[type="radio"] { width:auto; height:auto; flex-shrink:0; }
.payment-opt-label { font-size: 13px; font-weight: 700; font-family: 'Montserrat', sans-serif; color: #222; }
.payment-logos { display: flex; gap: 6px; align-items: center; margin-left: auto; }
.payment-logos img { height: 20px; }

/* Card fields */
.card-fields {
    margin-top: 14px;
    display: none;
    background: #f9f9f9;
    border-radius: 8px;
    padding: 16px;
    border: 1px solid #eee;
}
.card-fields.show { display: block; }

/* Right: Order Summary */
.co-summary {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 10px;
    overflow: hidden;
    position: sticky;
    top: 90px;
}
.co-summary-hdr {
    padding: 16px 22px;
    background: #1e3a6e;
    color: #fff;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px; font-weight: 700;
}
.co-summary-body { padding: 18px 22px; }
.co-order-items { margin-bottom: 16px; }
.co-order-item {
    display: flex; gap: 12px; align-items: center;
    padding: 10px 0; border-bottom: 1px solid #f3f3f3;
}
.co-order-item:last-child { border-bottom: none; }
.co-item-thumb {
    width: 52px; height: 40px;
    border-radius: 5px; overflow: hidden;
    background: #eef3ff; flex-shrink: 0;
}
.co-item-thumb img { width:100%; height:100%; object-fit:cover; }
.co-item-info { flex: 1; min-width: 0; }
.co-item-name {
    font-size: 12px; font-weight: 700;
    color: #333; font-family: 'Montserrat', sans-serif;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.co-item-qty { font-size: 11px; color: #888; }
.co-item-price {
    font-size: 13px; font-weight: 800;
    color: #1e3a6e; font-family: 'Montserrat', sans-serif;
    white-space: nowrap;
}
.co-summary-line {
    display: flex; justify-content: space-between;
    font-size: 13px; color: #666; margin-bottom: 8px;
}
.co-summary-line .v { font-weight: 600; color: #333; }
.co-summary-line.free .v { color: #3c9c3c; font-weight: 700; }
.co-sum-divider { height:1px; background:#eee; margin:10px 0; }
.co-summary-line.total .k {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px; font-weight: 800; color: #1e3a6e;
}
.co-summary-line.total .v {
    font-family: 'Montserrat', sans-serif;
    font-size: 20px; font-weight: 900; color: #1e3a6e;
}

/* Place order button */
.btn-place-order {
    display: block; width: 100%;
    padding: 15px;
    background: #f5c800;
    color: #1a1a1a;
    border: none; border-radius: 7px;
    font-family: 'Montserrat', sans-serif;
    font-size: 15px; font-weight: 900;
    text-align: center; cursor: pointer;
    transition: background 0.2s, transform 0.15s;
    letter-spacing: 0.3px;
}
.btn-place-order:hover { background: #e0b400; transform: translateY(-1px); }
.btn-place-order:active { transform: translateY(0); }

.co-lock-note {
    display: flex; align-items: center; gap: 6px;
    font-size: 11px; color: #888;
    justify-content: center;
    margin-top: 10px;
}

/* Trust badges */
.co-trust { margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0f0f0; }
.co-trust-item {
    display: flex; align-items: center; gap: 8px;
    font-size: 11px; color: #666; margin-bottom: 7px;
}
.co-trust-item svg { color: #3c9c3c; flex-shrink: 0; }

@media (max-width: 900px) {
    .checkout-grid { grid-template-columns: 1fr; }
    .co-summary { position: static; }
}
@media (max-width: 600px) {
    .co-row { grid-template-columns: 1fr; }
    .checkout-steps { padding: 12px 16px; }
    .checkout-step span.step-label { display: none; }
    .delivery-options { grid-template-columns: 1fr; }
}
</style>
@endsection

@section('content')
<div class="checkout-wrap">

    {{-- Breadcrumb --}}
    <div class="checkout-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>›</span>
        <a href="{{ route('basket') }}">Basket</a>
        <span>›</span>
        <strong>Checkout</strong>
    </div>

    {{-- Steps --}}
    <div class="checkout-steps">
        <div class="checkout-step done">
            <div class="c-step-num">✓</div>
            <span class="step-label">Basket</span>
        </div>
        <div class="c-step-conn done"></div>
        <div class="checkout-step done">
            <div class="c-step-num">✓</div>
            <span class="step-label">Login</span>
        </div>
        <div class="c-step-conn done"></div>
        <div class="checkout-step active">
            <div class="c-step-num">3</div>
            <span class="step-label">Checkout</span>
        </div>
        <div class="c-step-conn"></div>
        <div class="checkout-step">
            <div class="c-step-num">4</div>
            <span class="step-label">Confirmation</span>
        </div>
    </div>

    <form method="POST" action="{{ route('order.place') }}" id="checkoutForm">
        @csrf
        <div class="checkout-grid">

            {{-- LEFT: Forms --}}
            <div>

                {{-- Contact Details --}}
                <div class="co-panel">
                    <div class="co-panel-hdr">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Contact Details
                    </div>
                    <div class="co-panel-body">
                        <div class="co-row">
                            <div class="co-field">
                                <label>First Name <span class="req">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', auth()->user()->name ?? '') }}" placeholder="John" required>
                                @error('first_name')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="co-field">
                                <label>Last Name <span class="req">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Smith" required>
                                @error('last_name')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="co-row">
                            <div class="co-field">
                                <label>Email Address <span class="req">*</span></label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="john@example.com" required>
                                @error('email')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="co-field">
                                <label>Phone Number <span class="req">*</span></label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+44 7700 000000" required>
                                @error('phone')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="co-row single">
                            <div class="co-field">
                                <label>Company Name <span style="color:#aaa;font-weight:400;">(optional)</span></label>
                                <input type="text" name="company" value="{{ old('company') }}" placeholder="Your Company Ltd">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Delivery Address --}}
                <div class="co-panel">
                    <div class="co-panel-hdr">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Delivery Address
                    </div>
                    <div class="co-panel-body">
                        <div class="co-row single">
                            <div class="co-field">
                                <label>Address Line 1 <span class="req">*</span></label>
                                <input type="text" name="address_line1" value="{{ old('address_line1') }}" placeholder="123 High Street" required>
                                @error('address_line1')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="co-row single">
                            <div class="co-field">
                                <label>Address Line 2 <span style="color:#aaa;font-weight:400;">(optional)</span></label>
                                <input type="text" name="address_line2" value="{{ old('address_line2') }}" placeholder="Flat, suite, unit, etc.">
                            </div>
                        </div>
                        <div class="co-row">
                            <div class="co-field">
                                <label>City / Town <span class="req">*</span></label>
                                <input type="text" name="city" value="{{ old('city', 'London') }}" placeholder="London" required>
                                @error('city')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="co-field">
                                <label>Postcode <span class="req">*</span></label>
                                <input type="text" name="postcode" value="{{ old('postcode') }}" placeholder="EC1A 1BB" required>
                                @error('postcode')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="co-row single">
                            <div class="co-field">
                                <label>Country</label>
                                <select name="country">
                                    <option value="GB" selected>United Kingdom</option>
                                    <option value="IE">Ireland</option>
                                </select>
                            </div>
                        </div>
                        <div class="co-row single">
                            <div class="co-field">
                                <label>Delivery Notes <span style="color:#aaa;font-weight:400;">(optional)</span></label>
                                <textarea name="delivery_notes" placeholder="Any special delivery instructions...">{{ old('delivery_notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Delivery Method --}}
                <div class="co-panel">
                    <div class="co-panel-hdr">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11a2 2 0 012 2v3"/><rect x="9" y="11" width="14" height="10" rx="2"/><circle cx="12" cy="21" r="1"/><circle cx="20" cy="21" r="1"/></svg>
                        Delivery Method
                    </div>
                    <div class="co-panel-body">
                        <div class="delivery-options">
                            @if(isset($deliveryMethods) && $deliveryMethods->count() > 0)
                                @foreach($deliveryMethods as $di => $dm)
                                <label class="delivery-opt {{ $di === 0 ? 'selected' : '' }}" onclick="selectDelivery(this, {{ $dm->price }})">
                                    <input type="radio" name="delivery_method" value="{{ $dm->slug }}" {{ $di === 0 ? 'checked' : '' }}>
                                    <div>
                                        <span class="delivery-opt-label">{{ $dm->name }}</span>
                                        <span class="delivery-opt-sub">{{ $dm->description }}</span>
                                        <span class="delivery-opt-price">{{ $dm->price > 0 ? '£'.number_format($dm->price,2) : 'FREE' }}</span>
                                    </div>
                                </label>
                                @endforeach
                            @else
                            <label class="delivery-opt selected" onclick="selectDelivery(this, 0)">
                                <input type="radio" name="delivery_method" value="next_day" checked>
                                <div>
                                    <span class="delivery-opt-label">Next Day Delivery</span>
                                    <span class="delivery-opt-sub">Order before 12pm today</span>
                                    <span class="delivery-opt-price">FREE</span>
                                </div>
                            </label>
                            <label class="delivery-opt" onclick="selectDelivery(this, 19.99)">
                                <input type="radio" name="delivery_method" value="same_day">
                                <div>
                                    <span class="delivery-opt-label">Same Day Rush</span>
                                    <span class="delivery-opt-sub">London postcodes only</span>
                                    <span class="delivery-opt-price">£19.99</span>
                                </div>
                            </label>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="co-panel">
                    <div class="co-panel-hdr">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        Payment Method
                    </div>
                    <div class="co-panel-body">
                        <div class="payment-methods">
                            @php
                                $activePayments = $paymentMethods ?? collect([]);
                                $firstPm = $activePayments->first();
                            @endphp

                            @forelse($activePayments as $pi => $pm)
                            @php $isFirst = $pi === 0; @endphp

                            <label class="payment-opt {{ $isFirst ? 'selected' : '' }}" onclick="selectPayment(this, '{{ $pm->provider }}', '{{ $pm->slug }}')">
                                <input type="radio" name="payment_method" value="{{ $pm->slug }}" {{ $isFirst ? 'checked' : '' }}>
                                <span class="payment-opt-label">{{ $pm->name }}</span>
                                @if($pm->provider === 'stripe')
                                <div class="payment-logos">
                                    <span style="display:inline-flex;align-items:center;background:#eb001b;color:#fff;font-size:9px;font-weight:900;padding:2px 5px;border-radius:3px;letter-spacing:.5px;height:18px">MC</span>
                                    <span style="display:inline-flex;align-items:center;background:#1a1f71;color:#fff;font-size:9px;font-weight:900;padding:2px 6px;border-radius:3px;letter-spacing:1px;height:18px">VISA</span>
                                </div>
                                @elseif($pm->provider === 'paypal')
                                <div class="payment-logos">
                                    <span style="display:inline-flex;align-items:center;background:#003087;color:#fff;font-size:9px;font-weight:900;padding:2px 6px;border-radius:3px;letter-spacing:.5px;height:18px">Pay<span style="color:#009cde">Pal</span></span>
                                </div>
                                @endif
                            </label>

                            @if($pm->provider === 'stripe')
                            {{-- Real Stripe Card Element --}}
                            <div class="card-fields {{ $isFirst ? 'show' : '' }}" id="stripeCardFields" style="padding:16px;">
                                @if(!empty($stripePublicKey))
                                <div id="stripe-card-element" style="padding:10px;border:1.5px solid #ddd;border-radius:7px;background:#fafafa;"></div>
                                <div id="stripe-errors" style="color:#e8352a;font-size:12px;margin-top:6px;"></div>
                                @else
                                <div style="padding:12px;border:1.5px solid #ffc107;border-radius:7px;background:#fffbea;color:#856404;font-size:13px;">
                                    <i class="fas fa-exclamation-triangle"></i> Stripe is not configured yet. Please contact support.
                                </div>
                                @endif
                            </div>
                            @elseif($pm->provider === 'paypal')
                            {{-- PayPal Buttons --}}
                            <div id="paypal-button-container" style="display:none;margin:10px 0;padding:0 4px;"></div>
                            @elseif($pm->slug === 'bank_transfer')
                            {{-- Bank Transfer Details --}}
                            @php $bankCfg = json_decode($pm->config ?? '{}', true) ?? []; @endphp
                            <div class="card-fields" id="bankFields" style="display:none;padding:14px;">
                                <div style="font-size:13px;color:#333;line-height:1.8;">
                                    @if(!empty($bankCfg['bank_name']))<div><strong>Bank:</strong> {{ $bankCfg['bank_name'] }}</div>@endif
                                    @if(!empty($bankCfg['account_name']))<div><strong>Account Name:</strong> {{ $bankCfg['account_name'] }}</div>@endif
                                    @if(!empty($bankCfg['sort_code']))<div><strong>Sort Code:</strong> {{ $bankCfg['sort_code'] }}</div>@endif
                                    @if(!empty($bankCfg['account_number']))<div><strong>Account Number:</strong> {{ $bankCfg['account_number'] }}</div>@endif
                                    <div style="margin-top:8px;font-size:12px;color:#888;">Please use your order reference as payment reference.</div>
                                </div>
                            </div>
                            @endif

                            @empty
                            {{-- Fallback if no payment methods in DB --}}
                            <label class="payment-opt selected" onclick="selectPayment(this, 'stripe', 'stripe_card')">
                                <input type="radio" name="payment_method" value="stripe_card" checked>
                                <span class="payment-opt-label">Credit / Debit Card</span>
                            </label>
                            <div class="card-fields show" id="stripeCardFields" style="padding:16px;">
                                @if(!empty($stripePublicKey))
                                <div id="stripe-card-element" style="padding:10px;border:1.5px solid #ddd;border-radius:7px;background:#fafafa;"></div>
                                <div id="stripe-errors" style="color:#e8352a;font-size:12px;margin-top:6px;"></div>
                                @else
                                <div style="padding:12px;border:1.5px solid #ffc107;border-radius:7px;background:#fffbea;color:#856404;font-size:13px;">
                                    <i class="fas fa-exclamation-triangle"></i> Stripe is not configured yet. Please contact support.
                                </div>
                                @endif
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT: Order Summary --}}
            <div>
                <div class="co-summary">
                    <div class="co-summary-hdr">Order Summary</div>
                    <div class="co-summary-body">

                        {{-- Items --}}
                        <div class="co-order-items">
                            @foreach($items as $item)
                            <div class="co-order-item">
                                <div class="co-item-thumb">
                                    @if(!empty($item['image']))
                                        <img src="{{ asset($item['image']) }}" alt="{{ $item['product_name'] }}">
                                    @else
                                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:18px;">🖨️</div>
                                    @endif
                                </div>
                                <div class="co-item-info">
                                    <div class="co-item-name">{{ $item['product_name'] }}</div>
                                    <div class="co-item-qty">Qty: {{ $item['quantity'] }}</div>
                                </div>
                                <div class="co-item-price">£{{ number_format($item['line_total'], 2) }}</div>
                            </div>
                            @endforeach
                        </div>

                        <div class="co-summary-line">
                            <span class="k">Subtotal</span>
                            <span class="v">£{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="co-summary-line free">
                            <span class="k">Delivery</span>
                            <span class="v" id="coDeliveryVal">FREE</span>
                        </div>
                        <div class="co-summary-line">
                            <span class="k">VAT (20%)</span>
                            <span class="v">£{{ number_format($vat, 2) }}</span>
                        </div>
                        <div class="co-sum-divider"></div>
                        <div class="co-summary-line total">
                            <span class="k">Total</span>
                            <span class="v" id="coTotalVal">£{{ number_format($total, 2) }}</span>
                        </div>

                        <div style="height:16px;"></div>

                        {{-- Discount line (shown when promo applied) --}}
                        <div class="co-summary-line" id="coDiscountRow" style="display:none;color:#3c9c3c;">
                            <span class="k">Discount</span>
                            <span class="v" id="coDiscountVal" style="color:#3c9c3c;">-£0.00</span>
                        </div>

                        {{-- Promo Code --}}
                        <div style="margin-bottom:12px;">
                            <div style="display:flex;gap:0;">
                                <input type="text" id="promoCodeInput" placeholder="Promo / discount code" style="flex:1;border:1.5px solid #ddd;border-right:none;border-radius:7px 0 0 7px;padding:9px 12px;font-size:13px;font-family:'Open Sans',sans-serif;outline:none;background:#fafafa;">
                                <button type="button" onclick="applyPromo()" style="background:#1e3a6e;color:#fff;padding:9px 16px;border-radius:0 7px 7px 0;font-size:13px;font-weight:700;border:none;cursor:pointer;font-family:'Montserrat',sans-serif;white-space:nowrap;">Apply</button>
                            </div>
                            <div id="promoMsg" style="font-size:12px;margin-top:4px;"></div>
                        </div>

                        <button type="submit" id="placeOrderBtn" class="btn-place-order">
                            🔒 Place Order – £{{ number_format($total, 2) }}
                        </button>

                        <div class="co-lock-note">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                            Secured with 256-bit SSL encryption
                        </div>

                        {{-- Trust --}}
                        <div class="co-trust">
                            <div class="co-trust-item">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Free next day delivery
                            </div>
                            <div class="co-trust-item">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                100% satisfaction guarantee
                            </div>
                            <div class="co-trust-item">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Dedicated account manager
                            </div>
                        </div>

                        <div style="margin-top:12px;text-align:center;">
                            <a href="{{ route('basket') }}" style="font-size:12px;color:#888;text-decoration:underline;">← Edit Basket</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection

@section('scripts')
{{-- Load Stripe.js --}}
@if(!empty($stripePublicKey))
<script src="https://js.stripe.com/v3/"></script>
@endif
<script>
var checkoutSubtotal = {{ $subtotal }};
var checkoutDiscount = 0;
var checkoutDelivery = 0;
var stripeCardElement = null;
var stripeInstance = null;

// ─── STRIPE SETUP ──────────────────────────────────────
@if(!empty($stripePublicKey))
(function() {
    if (typeof Stripe === 'undefined') return;
    stripeInstance = Stripe('{{ $stripePublicKey }}');
    var elements = stripeInstance.elements();
    stripeCardElement = elements.create('card', {
        style: {
            base: { fontSize: '15px', fontFamily: "'Open Sans', sans-serif", color: '#222', '::placeholder': { color: '#aaa' } },
            invalid: { color: '#e8352a' }
        }
    });
    var mountEl = document.getElementById('stripe-card-element');
    if (mountEl) {
        stripeCardElement.mount('#stripe-card-element');
        stripeCardElement.on('change', function(e) {
            var errEl = document.getElementById('stripe-errors');
            if (errEl) errEl.textContent = e.error ? e.error.message : '';
        });
    }
})();
@endif

// ─── PAYPAL SETUP ──────────────────────────────────────
@if(!empty($paypalClientId))
(function() {
    var ppScript = document.createElement('script');
    ppScript.src = 'https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency={{ $paypalCurrency ?? "GBP" }}&intent=capture';
    ppScript.onload = function() {
        if (typeof paypal === 'undefined') return;
        paypal.Buttons({
            style: { layout: 'vertical', color: 'gold', shape: 'rect', label: 'pay' },
            createOrder: async function() {
                var resp = await fetch('{{ route("api.payment.paypal.create") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        delivery_method: document.querySelector('[name="delivery_method"]:checked')?.value,
                        promo_code: document.getElementById('promoCodeInput')?.value || ''
                    })
                });
                var data = await resp.json();
                if (data.error) { alert(data.error); throw new Error(data.error); }
                return data.orderId;
            },
            onApprove: async function(ppData) {
                var resp = await fetch('{{ route("api.payment.paypal.capture") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ orderID: ppData.orderID })
                });
                var result = await resp.json();
                if (result.success) {
                    // Add payment method to form and submit
                    setHidden('payment_method', 'paypal');
                    document.getElementById('checkoutForm').submit();
                } else {
                    alert(result.error || 'PayPal payment failed. Please try again.');
                }
            },
            onError: function(err) { console.error('PayPal error', err); alert('PayPal error. Please try another payment method.'); }
        }).render('#paypal-button-container');
    };
    document.head.appendChild(ppScript);
})();
@endif

// ─── FORM SUBMIT HANDLER ───────────────────────────────
document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    var method = document.querySelector('[name="payment_method"]:checked')?.value || '';
    var btn = document.getElementById('placeOrderBtn');
    if (btn) { btn.disabled = true; btn.textContent = 'Processing...'; }

    try {
        if (method === 'stripe_card' || method === 'stripe') {
            // ── Stripe payment ──
            if (!stripeInstance || !stripeCardElement) {
                alert('Card payment is not configured yet.\n\nPlease use Bank Transfer or contact admin to set up Stripe API keys.');
                resetBtn(btn); return;
            }
            // Create PaymentIntent server-side
            var intentResp = await fetch('{{ route("api.payment.stripe") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    delivery_method: document.querySelector('[name="delivery_method"]:checked')?.value || 'next_day_free',
                    promo_code: document.getElementById('promoCodeInput')?.value || ''
                })
            });
            var intentData = await intentResp.json();
            if (intentData.error) { alert(intentData.error); resetBtn(btn); return; }

            // Confirm card payment
            var billingDetails = {
                name: (document.querySelector('[name="first_name"]')?.value || '') + ' ' + (document.querySelector('[name="last_name"]')?.value || ''),
                email: document.querySelector('[name="email"]')?.value || '',
                phone: document.querySelector('[name="phone"]')?.value || '',
                address: {
                    line1: document.querySelector('[name="address_line1"]')?.value || '',
                    city: document.querySelector('[name="city"]')?.value || '',
                    postal_code: document.querySelector('[name="postcode"]')?.value || '',
                    country: document.querySelector('[name="country"]')?.value || 'GB'
                }
            };
            var result = await stripeInstance.confirmCardPayment(intentData.clientSecret, {
                payment_method: { card: stripeCardElement, billing_details: billingDetails }
            });
            if (result.error) {
                var errEl = document.getElementById('stripe-errors');
                if (errEl) errEl.textContent = result.error.message;
                resetBtn(btn); return;
            }
            setHidden('stripe_payment_intent', result.paymentIntent.id);
            this.submit();

        } else if (method === 'paypal') {
            // PayPal is handled by PayPal SDK buttons — just show message
            alert('Please use the PayPal button to complete payment.');
            resetBtn(btn);

        } else {
            // Bank transfer, invoice, or other manual methods — submit directly
            this.submit();
        }
    } catch(err) {
        console.error('Payment error:', err);
        alert('Payment processing error. Please try again.');
        resetBtn(btn);
    }
});

function resetBtn(btn) {
    if (!btn) return;
    btn.disabled = false;
    btn.textContent = '🔒 Place Order – £' + (checkoutSubtotal - checkoutDiscount + checkoutDelivery + Math.round((checkoutSubtotal - checkoutDiscount) * 0.20 * 100) / 100).toFixed(2);
}

function setHidden(name, value) {
    var existing = document.querySelector('[name="' + name + '"][type="hidden"]');
    if (existing) { existing.value = value; return; }
    var inp = document.createElement('input');
    inp.type = 'hidden'; inp.name = name; inp.value = value;
    document.getElementById('checkoutForm').appendChild(inp);
}

// ─── DELIVERY SELECTION ────────────────────────────────
function selectDelivery(label, cost) {
    document.querySelectorAll('.delivery-opt').forEach(l => l.classList.remove('selected'));
    label.classList.add('selected');
    checkoutDelivery = cost;
    updateCheckoutTotals();
}

var paypalConfigured = {{ !empty($paypalClientId) ? 'true' : 'false' }};

// ─── PAYMENT SELECTION ────────────────────────────────
function selectPayment(label, provider, slug) {
    document.querySelectorAll('.payment-opt').forEach(l => l.classList.remove('selected'));
    label.classList.add('selected');
    // Show/hide relevant panels
    var stripeFields = document.getElementById('stripeCardFields');
    var paypalContainer = document.getElementById('paypal-button-container');
    var bankFields = document.getElementById('bankFields');
    if (stripeFields) stripeFields.classList.toggle('show', provider === 'stripe');
    if (paypalContainer) paypalContainer.style.display = (provider === 'paypal' && paypalConfigured) ? 'block' : 'none';
    if (bankFields) bankFields.style.display = slug === 'bank_transfer' ? 'block' : 'none';
    // Hide Place Order button only when PayPal is selected AND configured (PayPal has its own button)
    var btn = document.getElementById('placeOrderBtn');
    if (btn) btn.style.display = (provider === 'paypal' && paypalConfigured) ? 'none' : 'block';
}

// ─── TOTALS UPDATE ─────────────────────────────────────
function updateCheckoutTotals() {
    var vat = Math.round((checkoutSubtotal - checkoutDiscount) * 0.20 * 100) / 100;
    var total = checkoutSubtotal - checkoutDiscount + checkoutDelivery + vat;

    var deliveryEl = document.getElementById('coDeliveryVal');
    if (deliveryEl) {
        if (checkoutDelivery > 0) { deliveryEl.textContent = '£' + checkoutDelivery.toFixed(2); deliveryEl.style.color = '#333'; }
        else { deliveryEl.textContent = 'FREE'; deliveryEl.style.color = '#3c9c3c'; }
    }

    var discountRow = document.getElementById('coDiscountRow');
    if (discountRow) discountRow.style.display = checkoutDiscount > 0 ? 'flex' : 'none';
    var discountEl = document.getElementById('coDiscountVal');
    if (discountEl) discountEl.textContent = '-£' + checkoutDiscount.toFixed(2);

    var vatEl = document.querySelector('.co-summary-line .v[id]');
    // Update VAT display
    document.querySelectorAll('.co-summary-line').forEach(function(row) {
        if (row.querySelector('.k') && row.querySelector('.k').textContent.includes('VAT')) {
            var vEl = row.querySelector('.v');
            if (vEl) vEl.textContent = '£' + vat.toFixed(2);
        }
    });

    var totalEl = document.getElementById('coTotalVal');
    if (totalEl) totalEl.textContent = '£' + total.toFixed(2);

    var btn = document.getElementById('placeOrderBtn');
    if (btn && !btn.disabled) btn.textContent = '🔒 Place Order – £' + total.toFixed(2);
}

// ─── PROMO CODE ────────────────────────────────────────
async function applyPromo() {
    var code = (document.getElementById('promoCodeInput')?.value || '').trim().toUpperCase();
    var msgEl = document.getElementById('promoMsg');
    if (!code) { if (msgEl) msgEl.innerHTML = ''; return; }

    if (msgEl) msgEl.innerHTML = '<span style="color:#888">Checking...</span>';

    try {
        var resp = await fetch('{{ route("api.promo.validate") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ code: code, subtotal: checkoutSubtotal })
        });
        var data = await resp.json();
        if (data.valid) {
            checkoutDiscount = parseFloat(data.discount) || 0;
            if (msgEl) msgEl.innerHTML = '<span style="color:#3c9c3c;font-weight:700;">✓ ' + (data.message || 'Discount applied!') + '</span>';
            updateCheckoutTotals();
        } else {
            checkoutDiscount = 0;
            if (msgEl) msgEl.innerHTML = '<span style="color:#e8352a;">✗ ' + (data.message || 'Invalid or expired code') + '</span>';
            updateCheckoutTotals();
        }
    } catch(err) {
        if (msgEl) msgEl.innerHTML = '<span style="color:#e8352a;">Could not validate code. Try again.</span>';
    }
}

// Initialise totals on page load
(function() {
    var firstDelivery = document.querySelector('[name="delivery_method"]:checked');
    if (firstDelivery) {
        var firstLabel = firstDelivery.closest('.delivery-opt');
        if (firstLabel && firstLabel.getAttribute('onclick')) {
            var m = firstLabel.getAttribute('onclick').match(/selectDelivery\(this,\s*([\d.]+)\)/);
            if (m) { checkoutDelivery = parseFloat(m[1]) || 0; }
        }
    }
    // Also initialise payment method on load (shows PayPal buttons, hides Place Order for PayPal etc.)
    var firstPaymentRadio = document.querySelector('[name="payment_method"]:checked');
    if (firstPaymentRadio) {
        var firstPaymentLabel = firstPaymentRadio.closest('.payment-opt');
        if (firstPaymentLabel) {
            var oc = firstPaymentLabel.getAttribute('onclick') || '';
            var pm = oc.match(/selectPayment\(this,\s*'([^']+)',\s*'([^']*)'\)/);
            if (pm) selectPayment(firstPaymentLabel, pm[1], pm[2]);
        }
    }
    updateCheckoutTotals();
})();
</script>
@endsection
