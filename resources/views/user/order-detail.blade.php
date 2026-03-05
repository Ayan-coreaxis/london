@extends('layouts.app')

@section('title', 'Order ' . $order->order_ref . ' – London InstantPrint')

@section('styles')
<style>
.order-wrap {
    max-width: 1000px; margin: 0 auto; padding: 36px 24px 80px;
    font-family: 'Open Sans', sans-serif;
}
.order-breadcrumb { font-size: 13px; color: #888; margin-bottom: 28px; }
.order-breadcrumb a { color: #888; text-decoration: none; }
.order-breadcrumb a:hover { color: #1e3a6e; }
.order-breadcrumb span { margin: 0 6px; }

/* Header */
.order-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    flex-wrap: wrap; gap: 16px; margin-bottom: 28px;
}
.order-header h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 24px; font-weight: 900; color: #1e3a6e; margin: 0 0 6px;
}
.order-header .order-meta { font-size: 13px; color: #888; }

/* Status badge */
.status-badge {
    display: inline-block; padding: 6px 18px; border-radius: 20px;
    font-size: 13px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    text-transform: uppercase; letter-spacing: 0.3px;
}
.status-pending    { background: #fff8e6; color: #c87600; border: 1px solid #ffd888; }
.status-processing { background: #e8f4ff; color: #0066cc; border: 1px solid #99ccff; }
.status-printing   { background: #f0e8ff; color: #6600cc; border: 1px solid #cc99ff; }
.status-dispatched { background: #fff0e8; color: #cc4400; border: 1px solid #ffbb88; }
.status-delivered  { background: #f0faf0; color: #2a7a2a; border: 1px solid #99dd99; }
.status-cancelled  { background: #fff3f2; color: #c0392b; border: 1px solid #fbc9c6; }

/* Flash */
.flash-success {
    background: #f0faf0; border: 1px solid #c6e8c6; border-radius: 8px;
    padding: 14px 20px; color: #2a7a2a; font-size: 14px; font-weight: 600;
    margin-bottom: 24px; display: flex; align-items: center; gap: 10px;
}

/* Progress tracker */
.progress-track {
    background: #fff; border: 1px solid #e8e8e8; border-radius: 10px;
    padding: 28px 32px; margin-bottom: 24px;
}
.progress-track h3 {
    font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 700;
    color: #888; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 24px;
}
.progress-steps {
    display: flex; align-items: center; justify-content: space-between; position: relative;
}
.progress-steps::before {
    content: ''; position: absolute; top: 16px; left: 0; right: 0; height: 3px;
    background: #e0e0e0; z-index: 0;
}
.progress-fill {
    position: absolute; top: 16px; left: 0; height: 3px;
    background: #3c9c3c; z-index: 1; transition: width 0.4s;
}
.prog-step {
    display: flex; flex-direction: column; align-items: center; gap: 10px;
    position: relative; z-index: 2; flex: 1;
}
.prog-dot {
    width: 34px; height: 34px; border-radius: 50%; border: 3px solid #e0e0e0;
    background: #fff; display: flex; align-items: center; justify-content: center;
    font-size: 14px; transition: all 0.3s;
}
.prog-step.done   .prog-dot { border-color: #3c9c3c; background: #3c9c3c; color: #fff; }
.prog-step.active .prog-dot { border-color: #1e3a6e; background: #1e3a6e; color: #fff; }
.prog-step.done   .prog-dot::after { content: '✓'; font-weight: 900; }
.prog-step:not(.done):not(.active) .prog-dot::after { content: attr(data-num); color: #ccc; font-weight: 700; }
.prog-step.active .prog-dot::after { content: attr(data-num); color: #fff; font-weight: 900; }
.prog-label { font-size: 11px; font-weight: 700; color: #aaa; font-family: 'Montserrat', sans-serif; text-align: center; text-transform: uppercase; letter-spacing: 0.3px; }
.prog-step.done   .prog-label { color: #3c9c3c; }
.prog-step.active .prog-label { color: #1e3a6e; }

/* Grid */
.order-grid { display: grid; grid-template-columns: 1fr 320px; gap: 20px; }
.order-panel {
    background: #fff; border: 1px solid #e8e8e8; border-radius: 10px; overflow: hidden; margin-bottom: 20px;
}
.order-panel:last-child { margin-bottom: 0; }
.panel-hdr {
    padding: 16px 22px; background: #f9f9f9; border-bottom: 1px solid #e8e8e8;
    font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 700; color: #222;
}

/* Items */
.order-item {
    display: flex; align-items: center; gap: 16px; padding: 16px 22px;
    border-bottom: 1px solid #f5f5f5;
}
.order-item:last-child { border-bottom: none; }
.order-item-thumb {
    width: 70px; height: 54px; border-radius: 7px; overflow: hidden;
    background: #eef3ff; flex-shrink: 0;
}
.order-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
.order-item-thumb .thumb-ph {
    width: 100%; height: 100%; display: flex; align-items: center;
    justify-content: center; font-size: 24px;
}
.order-item-name {
    font-family: 'Montserrat', sans-serif; font-weight: 700;
    font-size: 13px; color: #1e3a6e; margin-bottom: 3px;
}
.order-item-opts { font-size: 12px; color: #777; }
.order-item-opts span {
    display: inline-block; background: #f3f3f3; padding: 2px 7px;
    border-radius: 4px; margin-right: 3px;
}
.order-item-price { margin-left: auto; text-align: right; flex-shrink: 0; }
.order-item-price .qty { font-size: 11px; color: #aaa; }
.order-item-price .line { font-family: 'Montserrat', sans-serif; font-size: 16px; font-weight: 800; color: #1e3a6e; }

/* Summary lines */
.summary-line {
    display: flex; justify-content: space-between; padding: 10px 22px;
    font-size: 14px; color: #555; border-bottom: 1px solid #f5f5f5;
}
.summary-line:last-child { border-bottom: none; }
.summary-line.total {
    font-family: 'Montserrat', sans-serif; font-weight: 900; font-size: 16px;
    color: #1e3a6e; border-top: 2px solid #eee; margin-top: 4px;
}
.summary-line .free { color: #3c9c3c; font-weight: 700; }

/* Address panel */
.address-block { padding: 18px 22px; font-size: 14px; color: #444; line-height: 1.7; }
.address-block strong { display: block; font-size: 15px; color: #222; margin-bottom: 4px; }

/* History */
.history-item {
    display: flex; gap: 16px; padding: 14px 22px; border-bottom: 1px solid #f5f5f5;
}
.history-item:last-child { border-bottom: none; }
.history-dot {
    width: 10px; height: 10px; border-radius: 50%; background: #1e3a6e;
    flex-shrink: 0; margin-top: 5px;
}
.history-status { font-weight: 700; font-size: 13px; color: #333; margin-bottom: 2px; }
.history-note { font-size: 12px; color: #888; }
.history-time { font-size: 11px; color: #bbb; margin-top: 2px; }

/* Back btn */
.btn-back {
    display: inline-flex; align-items: center; gap: 7px; margin-bottom: 20px;
    padding: 9px 18px; background: none; border: 2px solid #ddd; border-radius: 7px;
    font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700;
    color: #555; text-decoration: none; transition: all 0.2s;
}
.btn-back:hover { border-color: #1e3a6e; color: #1e3a6e; }
.btn-shop {
    display: block; padding: 12px 22px; background: #f5c800; color: #1a1a1a;
    border-radius: 7px; font-family: 'Montserrat', sans-serif; font-size: 14px;
    font-weight: 900; text-align: center; text-decoration: none;
    margin: 16px 22px; transition: background 0.2s;
}
.btn-shop:hover { background: #e0b400; }

@media (max-width: 768px) {
    .order-grid { grid-template-columns: 1fr; }
    .progress-steps { gap: 4px; }
    .prog-label { font-size: 9px; }
}
</style>
@endsection

@section('content')
<div class="order-wrap">

    <div class="order-breadcrumb">
        <a href="{{ route('home') }}">Home</a><span>›</span>
        <a href="{{ route('user.dashboard') }}">My Account</a><span>›</span>
        <strong>{{ $order->order_ref }}</strong>
    </div>

    @if(session('order_success'))
        <div class="flash-success">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('order_success') }}
        </div>
    @endif

    <a href="{{ route('user.dashboard') }}" class="btn-back">
        ← Back to My Orders
    </a>

    {{-- Alerts --}}
    @if(session('success'))
        <div style="background:#f0faf0;border:1px solid #99dd99;border-radius:8px;padding:13px 16px;font-size:14px;color:#2a7a2a;margin-bottom:20px;">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background:#fff3f2;border:1px solid #fbc9c6;border-radius:8px;padding:13px 16px;font-size:14px;color:#c0392b;margin-bottom:20px;">⚠️ {{ session('error') }}</div>
    @endif
    @if(session('order_success'))
        <div style="background:#f0faf0;border:1px solid #99dd99;border-radius:8px;padding:13px 16px;font-size:14px;color:#2a7a2a;margin-bottom:20px;">🎉 {{ session('order_success') }}</div>
    @endif

    {{-- Header --}}
    <div class="order-header">
        <div>
            <h1>Order {{ $order->order_ref }}</h1>
            <div class="order-meta">
                Placed on {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y \a\t H:i') }}
                · {{ count($items) }} {{ count($items) == 1 ? 'item' : 'items' }}
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <span class="status-badge status-{{ $order->status }}">{{ ucfirst(str_replace('_',' ',$order->status)) }}</span>
            @if(in_array($order->status, ['pending','confirmed']))
            <form method="POST" action="{{ route('user.order.cancel', $order->id) }}"
                  onsubmit="return confirm('Are you sure you want to cancel this order?')">
                @csrf
                <button type="submit" style="padding:7px 16px;background:#fff;border:1.5px solid #e8352a;border-radius:6px;color:#e8352a;font-family:'Montserrat',sans-serif;font-size:12px;font-weight:700;cursor:pointer;">
                    Cancel Order
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Progress Tracker --}}
    @php
        $steps    = ['pending','processing','printing','dispatched','delivered'];
        $stepLabels = ['Order Placed','Processing','Printing','Dispatched','Delivered'];
        $currentIdx = array_search($order->status, $steps);
        if ($currentIdx === false) $currentIdx = 0;
        $fillPct = $currentIdx > 0 ? ($currentIdx / (count($steps)-1)) * 100 : 0;
    @endphp
    <div class="progress-track">
        <h3>Order Progress</h3>
        <div class="progress-steps">
            <div class="progress-fill" style="width: {{ $fillPct }}%;"></div>
            @foreach($steps as $i => $step)
            <div class="prog-step {{ $i < $currentIdx ? 'done' : ($i == $currentIdx ? 'active' : '') }}">
                <div class="prog-dot" data-num="{{ $i+1 }}"></div>
                <div class="prog-label">{{ $stepLabels[$i] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="order-grid">

        {{-- LEFT --}}
        <div>
            {{-- Items --}}
            <div class="order-panel">
                <div class="panel-hdr">Order Items</div>
                @foreach($items as $item)
                <div class="order-item">
                    <div class="order-item-thumb">
                        <div class="thumb-ph">🖨️</div>
                    </div>
                    <div style="flex:1">
                        <div class="order-item-name">{{ $item->product_name }}</div>
                        @php $opts = json_decode($item->options ?? '{}', true); @endphp
                        @if(!empty($opts))
                        <div class="order-item-opts">
                            @foreach($opts as $k => $v)
                                <span>{{ ucfirst(str_replace('_', ' ', $k)) }}: {{ $v }}</span>
                            @endforeach
                        </div>
                        @endif
                        <div style="font-size:12px;color:#aaa;margin-top:4px;">
                            Qty: <strong style="color:#333;">{{ $item->quantity }}</strong>
                            &nbsp;·&nbsp; Unit: <strong style="color:#333;">£{{ number_format($item->unit_price, 2) }}</strong>
                        </div>
                        {{-- Artwork --}}
                        @if(!empty($item->artwork_url))
                            @php $ext = strtolower(pathinfo($item->artwork_url, PATHINFO_EXTENSION)); @endphp
                            <div style="margin-top:8px;">
                                @if(in_array($ext, ['jpg','jpeg','png']))
                                    <a href="{{ asset($item->artwork_url) }}" target="_blank">
                                        <img src="{{ asset($item->artwork_url) }}" alt="Artwork"
                                             style="max-width:80px;max-height:60px;border-radius:5px;border:1px solid #e0e0e0;object-fit:cover;">
                                    </a>
                                @else
                                    <a href="{{ asset($item->artwork_url) }}" target="_blank"
                                       style="display:inline-flex;align-items:center;gap:5px;font-size:12px;color:#1e3a6e;font-weight:700;background:#f0f4ff;padding:5px 10px;border-radius:5px;text-decoration:none;">
                                        📄 View Artwork File
                                    </a>
                                @endif
                            </div>
                        @else
                            <div style="margin-top:8px;font-size:12px;color:#c87600;background:#fff8e6;padding:5px 10px;border-radius:5px;display:inline-block;">
                                ⚠️ No artwork uploaded
                            </div>
                        @endif
                    </div>
                    <div class="order-item-price">
                        <div class="qty">Qty: {{ $item->quantity }}</div>
                        <div class="line">£{{ number_format($item->line_total, 2) }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Status History --}}
            @if(!empty($history))
            <div class="order-panel">
                <div class="panel-hdr">Status History</div>
                @foreach($history as $h)
                <div class="history-item">
                    <div class="history-dot"></div>
                    <div>
                        <div class="history-status">{{ ucfirst($h->status) }}</div>
                        @if(!empty($h->note))
                            <div class="history-note">{{ $h->note }}</div>
                        @endif
                        <div class="history-time">{{ \Carbon\Carbon::parse($h->created_at)->format('d M Y H:i') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- RIGHT --}}
        <div>
            {{-- Order Summary --}}
            <div class="order-panel" style="margin-bottom:20px;">
                <div class="panel-hdr">Order Summary</div>
                <div class="summary-line">
                    <span>Subtotal (ex. VAT)</span><span>£{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="summary-line">
                    <span>Delivery</span>
                    @if($order->delivery_cost == 0)
                        <span class="free">FREE</span>
                    @else
                        <span>£{{ number_format($order->delivery_cost, 2) }}</span>
                    @endif
                </div>
                <div class="summary-line">
                    <span>VAT (20%)</span><span>£{{ number_format($order->vat, 2) }}</span>
                </div>
                <div class="summary-line total">
                    <span>Total</span><span>£{{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            {{-- Delivery Address --}}
            <div class="order-panel" style="margin-bottom:20px;">
                <div class="panel-hdr">Delivery Address</div>
                <div class="address-block">
                    <strong>{{ $order->first_name }} {{ $order->last_name }}</strong>
                    @if($order->company) {{ $order->company }}<br> @endif
                    {{ $order->address_line1 }}<br>
                    @if($order->address_line2) {{ $order->address_line2 }}<br> @endif
                    {{ $order->city }}, {{ $order->postcode }}<br>
                    @if($order->phone) <br>📞 {{ $order->phone }} @endif
                </div>
            </div>

            {{-- Delivery & Payment Info --}}
            <div class="order-panel" style="margin-bottom:20px;">
                <div class="panel-hdr">Order Info</div>
                <table style="width:100%;font-size:13px;border-collapse:collapse;">
                    <tr style="border-bottom:1px solid #f0f0f0;">
                        <td style="padding:8px 0;color:#888;width:45%;">Delivery Method</td>
                        <td style="padding:8px 0;font-weight:600;color:#333;">
                            @php
                                $deliveryLabels = [
                                    'next_day'   => '🚚 Next Day',
                                    'same_day'   => '⚡ Same Day (+£19.99)',
                                    'standard'   => '📦 Standard',
                                    'collection' => '🏪 Collection',
                                ];
                                echo $deliveryLabels[$order->delivery_method] ?? ucfirst(str_replace('_',' ',$order->delivery_method));
                            @endphp
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #f0f0f0;">
                        <td style="padding:8px 0;color:#888;">Payment Method</td>
                        <td style="padding:8px 0;font-weight:600;color:#333;">
                            @php
                                $paymentLabels = [
                                    'card'   => '💳 Credit / Debit Card',
                                    'paypal' => '🅿️ PayPal',
                                    'bacs'   => '🏦 Bank Transfer',
                                ];
                                echo $paymentLabels[$order->payment_method] ?? ucfirst($order->payment_method);
                            @endphp
                        </td>
                    </tr>
                    @if($order->delivery_notes)
                    <tr>
                        <td style="padding:8px 0;color:#888;">Delivery Notes</td>
                        <td style="padding:8px 0;color:#333;">{{ $order->delivery_notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            {{-- Shop again --}}
            <a href="{{ route('products') }}" class="btn-shop">🖨️ Order Again</a>
        </div>

    </div>

</div>
@endsection
