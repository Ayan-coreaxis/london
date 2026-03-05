@extends('layouts.admin')
@section('title','Orders')
@section('page_title','Order Management')

@section('content')

{{-- ── STATUS FILTER TABS ── --}}
<div style="display:flex;gap:6px;margin-bottom:18px;flex-wrap:wrap;align-items:center">
  @php
    $allCount = array_sum(array_column((array)$counts,'c') ?: array_values($counts));
    $tabs = ['' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'in_production' => 'In Production', 'dispatched' => 'Dispatched', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
    $tabColors = ['' => '#1e3a6e', 'pending' => '#d08800', 'confirmed' => '#0066cc', 'in_production' => '#7700cc', 'dispatched' => '#cc4400', 'completed' => '#1a7a1a', 'cancelled' => '#cc0000'];
  @endphp
  @foreach($tabs as $val => $label)
  @php $cnt = $val === '' ? array_sum(array_values($counts)) : ($counts[$val] ?? 0); @endphp
  <a href="{{ route('admin.orders.index') }}?status={{ $val }}&search={{ $search }}"
     style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:7px;font-size:12px;font-weight:700;text-decoration:none;border:1.5px solid {{ $status===$val ? $tabColors[$val] : '#e0e0e0' }};background:{{ $status===$val ? $tabColors[$val] : '#fff' }};color:{{ $status===$val ? '#fff' : '#666' }};transition:all .2s">
    {{ $label }}
    <span style="background:{{ $status===$val ? 'rgba(255,255,255,.25)' : '#f0f0f0' }};color:{{ $status===$val ? '#fff' : '#888' }};padding:1px 7px;border-radius:10px;font-size:11px">{{ $cnt }}</span>
  </a>
  @endforeach
</div>

{{-- ── SEARCH BAR ── --}}
<form method="GET" style="margin-bottom:18px;display:flex;gap:10px;align-items:center">
  <input type="hidden" name="status" value="{{ $status }}">
  <div style="position:relative;flex:1;max-width:420px">
    <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#aaa;font-size:13px"></i>
    <input type="text" name="search" value="{{ $search }}"
      placeholder="Search by name, email, order ref…"
      style="width:100%;height:42px;border:1.5px solid #ddd;border-radius:7px;padding:0 14px 0 36px;font-size:13px;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s"
      onfocus="this.style.borderColor='#1e3a6e'" onblur="this.style.borderColor='#ddd'">
  </div>
  <button type="submit" class="btn-primary"><i class="fas fa-search"></i> Search</button>
  @if($search || $status)
    <a href="{{ route('admin.orders.index') }}" class="btn-primary" style="background:#888"><i class="fas fa-times"></i> Clear</a>
  @endif
</form>

{{-- ── ORDERS TABLE ── --}}
<div class="data-card">
  <div class="data-card-hdr">
    <h3><i class="fas fa-list" style="margin-right:6px;color:#1e3a6e"></i>
      {{ count($orders) }} order{{ count($orders) != 1 ? 's' : '' }}
      @if($status) — <span style="color:#1e3a6e">{{ ucfirst(str_replace('_',' ',$status)) }}</span>@endif
      @if($search) matching "<em>{{ $search }}</em>"@endif
    </h3>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Order Ref</th>
        <th>Customer</th>
        <th>Company</th>
        <th>Total</th>
        <th>Payment</th>
        <th>Delivery</th>
        <th>Status</th>
        <th>Date</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($orders as $order)
      <tr>
        <td><strong style="color:#1e3a6e;font-size:13px">{{ $order->order_ref }}</strong></td>
        <td>
          <div style="font-weight:600;font-size:13px">{{ $order->first_name }} {{ $order->last_name }}</div>
          <div style="font-size:11px;color:#aaa">{{ $order->email }}</div>
        </td>
        <td style="font-size:12px;color:#888">{{ $order->company ?: '—' }}</td>
        <td><strong style="color:#1e3a6e">£{{ number_format($order->total,2) }}</strong></td>
        <td>
          <span style="font-size:11px;background:#f3f3f3;padding:2px 8px;border-radius:4px;color:#555">
            {{ ucfirst($order->payment_method ?? 'card') }}
          </span>
        </td>
        <td>
          @if($order->delivery_cost > 0)
            <span style="font-size:11px;background:#fff4ee;color:#cc4400;padding:2px 8px;border-radius:4px;border:1px solid #ffbb88">Same Day</span>
          @else
            <span style="font-size:11px;background:#e6ffed;color:#1a7a1a;padding:2px 8px;border-radius:4px;border:1px solid #99ddaa">Next Day</span>
          @endif
        </td>
        <td>
          <span class="badge badge-{{ $order->status }}">{{ ucfirst(str_replace('_',' ',$order->status)) }}</span>
        </td>
        <td style="font-size:12px;color:#aaa;white-space:nowrap">{{ date('d M Y',strtotime($order->created_at)) }}</td>
        <td>
          <a href="{{ route('admin.orders.show',$order->id) }}" class="btn-primary btn-sm">
            <i class="fas fa-eye"></i> View
          </a>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="9" style="text-align:center;padding:48px;color:#aaa">
          <i class="fas fa-inbox" style="font-size:32px;margin-bottom:10px;display:block;opacity:.3"></i>
          No orders found
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
