@extends('layouts.app')

@section('title', 'My Account – London InstantPrint')
@section('meta_description', 'View your orders and account details.')

@section('styles')
<style>
.account-wrap {
    max-width: 1100px;
    margin: 0 auto;
    padding: 36px 24px 80px;
    font-family: 'Open Sans', sans-serif;
}
.account-breadcrumb { font-size: 13px; color: #888; margin-bottom: 28px; }
.account-breadcrumb a { color: #888; text-decoration: none; }
.account-breadcrumb a:hover { color: #1e3a6e; }
.account-breadcrumb span { margin: 0 6px; }
.account-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 32px; flex-wrap: wrap; gap: 16px;
}
.account-header-left h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 26px; font-weight: 900; color: #1e3a6e; margin: 0 0 4px;
}
.account-header-left p { font-size: 14px; color: #888; margin: 0; }
.btn-logout {
    padding: 10px 22px; background: none; border: 2px solid #ddd;
    border-radius: 7px; font-family: 'Montserrat', sans-serif;
    font-size: 13px; font-weight: 700; color: #666; cursor: pointer;
    text-decoration: none; transition: border-color 0.2s, color 0.2s;
}
.btn-logout:hover { border-color: #e8352a; color: #e8352a; }
.account-stats {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px;
}
.stat-card {
    background: #fff; border: 1px solid #e8e8e8; border-radius: 10px;
    padding: 20px 22px; text-align: center;
}
.stat-card .stat-num {
    font-family: 'Montserrat', sans-serif; font-size: 32px; font-weight: 900;
    color: #1e3a6e; line-height: 1; margin-bottom: 6px;
}
.stat-card .stat-label { font-size: 13px; color: #888; font-weight: 600; }
.flash-success {
    background: #f0faf0; border: 1px solid #c6e8c6; border-radius: 8px;
    padding: 14px 20px; color: #2a7a2a; font-size: 14px; font-weight: 600;
    margin-bottom: 24px; display: flex; align-items: center; gap: 10px;
}
.orders-card { background: #fff; border: 1px solid #e8e8e8; border-radius: 10px; overflow: hidden; }
.orders-card-hdr {
    padding: 18px 24px; background: #f9f9f9; border-bottom: 1px solid #e8e8e8;
    font-family: 'Montserrat', sans-serif; font-size: 15px; font-weight: 700; color: #222;
}
.orders-table { width: 100%; border-collapse: collapse; }
.orders-table th {
    padding: 12px 20px; text-align: left; font-family: 'Montserrat', sans-serif;
    font-size: 12px; font-weight: 700; color: #888; text-transform: uppercase;
    letter-spacing: 0.5px; border-bottom: 1px solid #f0f0f0; background: #fafafa;
}
.orders-table td {
    padding: 16px 20px; font-size: 13px; color: #444;
    border-bottom: 1px solid #f5f5f5; vertical-align: middle;
}
.orders-table tr:last-child td { border-bottom: none; }
.orders-table tr:hover td { background: #fafeff; }
.order-ref { font-family: 'Montserrat', sans-serif; font-weight: 700; color: #1e3a6e; font-size: 13px; }
.order-date { color: #888; font-size: 12px; }
.status-badge {
    display: inline-block; padding: 4px 12px; border-radius: 20px;
    font-size: 11px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    text-transform: uppercase; letter-spacing: 0.3px;
}
.status-pending    { background: #fff8e6; color: #c87600; border: 1px solid #ffd888; }
.status-processing { background: #e8f4ff; color: #0066cc; border: 1px solid #99ccff; }
.status-printing   { background: #f0e8ff; color: #6600cc; border: 1px solid #cc99ff; }
.status-dispatched { background: #fff0e8; color: #cc4400; border: 1px solid #ffbb88; }
.status-delivered  { background: #f0faf0; color: #2a7a2a; border: 1px solid #99dd99; }
.status-cancelled  { background: #fff3f2; color: #c0392b; border: 1px solid #fbc9c6; }
.btn-view-order {
    display: inline-block; padding: 7px 16px; background: #1e3a6e; color: #fff;
    border-radius: 6px; font-size: 12px; font-weight: 700;
    font-family: 'Montserrat', sans-serif; text-decoration: none; transition: background 0.2s;
}
.btn-view-order:hover { background: #e8352a; }
.orders-empty { padding: 60px 24px; text-align: center; color: #888; }
.orders-empty h3 { font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 700; color: #555; margin-bottom: 8px; }
.orders-empty a {
    display: inline-block; margin-top: 16px; padding: 11px 28px; background: #1e3a6e;
    color: #fff; border-radius: 6px; text-decoration: none; font-weight: 700; font-size: 14px;
}
.orders-empty a:hover { background: #e8352a; }
@media (max-width: 768px) {
    .account-stats { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endsection

@section('content')
<div class="account-wrap">

    <div class="account-breadcrumb">
        <a href="{{ route('home') }}">Home</a><span>›</span><strong>My Account</strong>
    </div>

    @if(session('order_success'))
        <div class="flash-success">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('order_success') }}
        </div>
    @endif

    <div class="account-header">
        <div class="account-header-left">
            <h1>Welcome back, {{ $user->name }}!</h1>
            <p>{{ $user->email }}{{ $user->company ? ' · ' . $user->company : '' }}</p>
        </div>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <a href="{{ route('user.profile') }}" class="btn-logout" style="text-decoration:none;">Edit Profile</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn-logout">Log Out</button>
            </form>
        </div>
    </div>

    @php
        $totalOrders  = count($orders);
        $totalSpent   = collect($orders)->sum('total');
        $activeCount  = collect($orders)->whereIn('status', ['pending','processing','printing','dispatched'])->count();
    @endphp
    <div class="account-stats">
        <div class="stat-card">
            <div class="stat-num">{{ $totalOrders }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">£{{ number_format($totalSpent, 2) }}</div>
            <div class="stat-label">Total Spent</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ $activeCount }}</div>
            <div class="stat-label">Active Orders</div>
        </div>
    </div>

    <div style="display:flex;gap:8px;margin-bottom:28px;flex-wrap:wrap">
        <a href="{{ route('user.dashboard') }}" style="padding:9px 18px;background:#1e3a6e;color:#fff;border:1.5px solid #1e3a6e;border-radius:7px;font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;text-decoration:none">My Orders</a>
        <a href="{{ route('user.profile') }}" style="padding:9px 18px;border:1.5px solid #e0e0e0;border-radius:7px;font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;color:#555;text-decoration:none">My Profile</a>
        <a href="{{ route('user.addresses') }}" style="padding:9px 18px;border:1.5px solid #e0e0e0;border-radius:7px;font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;color:#555;text-decoration:none">Addresses</a>
        <a href="{{ route('user.wishlist') }}" style="padding:9px 18px;border:1.5px solid #e0e0e0;border-radius:7px;font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;color:#555;text-decoration:none">Wishlist</a>
    </div>

    <div class="orders-card">
        <div class="orders-card-hdr">My Orders</div>
        @if(empty($orders))
            <div class="orders-empty">
                <svg width="52" height="52" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.3" style="color:#ccc;margin-bottom:14px;">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3>No orders yet</h3>
                <p>Place your first order to get started.</p>
                <a href="{{ route('products') }}">Browse Products</a>
            </div>
        @else
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order</th><th>Products & Options</th>
                        <th>Total</th><th>Status</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    @php
                        $orderItems = \Illuminate\Support\Facades\DB::select("SELECT product_name, quantity, options FROM order_items WHERE order_id = ? LIMIT 3", [$order->id]);
                    @endphp
                    <tr>
                        <td>
                            <div class="order-ref">{{ $order->order_ref }}</div>
                            <div class="order-date">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</div>
                        </td>
                        <td>
                            @foreach($orderItems as $oi)
                                <div style="font-size:13px;font-weight:600;color:#333;">{{ $oi->product_name }}</div>
                                @php $opts = json_decode($oi->options ?? '{}', true); @endphp
                                @if(!empty($opts))
                                <div style="font-size:11px;color:#888;margin-bottom:3px;">
                                    @foreach($opts as $k => $v)
                                        <span style="background:#f3f3f3;padding:1px 6px;border-radius:3px;margin-right:2px;">{{ ucfirst(str_replace('_',' ',$k)) }}: {{ $v }}</span>
                                    @endforeach
                                </div>
                                @endif
                            @endforeach
                            @if($order->item_count > 3)
                                <div style="font-size:11px;color:#aaa;">+{{ $order->item_count - 3 }} more items</div>
                            @endif
                        </td>
                        <td>
                            <strong>£{{ number_format($order->total, 2) }}</strong>
                            <div style="font-size:11px;color:#aaa;">inc. VAT</div>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $order->status }}">{{ ucfirst(str_replace('_',' ',$order->status)) }}</span>
                            <div style="font-size:11px;color:#aaa;margin-top:4px;">
                                {{ ucfirst(str_replace('_',' ',$order->delivery_method ?? 'standard')) }}
                            </div>
                        </td>
                        <td><a href="{{ route('user.order', $order->id) }}" class="btn-view-order">View Details</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
@endsection
