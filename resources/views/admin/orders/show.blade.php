@extends('layouts.admin')
@section('title','Order '.$order->order_ref)
@section('page_title','Order Detail')

@section('styles')
<style>
.timeline { position: relative; padding-left: 28px; }
.timeline::before { content:''; position:absolute; left:9px; top:0; bottom:0; width:2px; background:#eee; }
.tl-item { position:relative; margin-bottom:18px; }
.tl-dot { position:absolute; left:-24px; top:3px; width:12px; height:12px; border-radius:50%; border:2px solid #1e3a6e; background:#fff; }
.tl-dot.active { background:#1e3a6e; }
.tl-status { font-size:13px; font-weight:700; color:#333; }
.tl-note { font-size:12px; color:#888; margin-top:2px; }
.tl-time { font-size:11px; color:#bbb; margin-top:2px; }
</style>
@endsection

@section('content')

{{-- Header --}}
<div style="display:flex;gap:10px;margin-bottom:24px;align-items:center;flex-wrap:wrap">
  <a href="{{ route('admin.orders.index') }}" class="btn-primary btn-sm" style="background:#888">
    <i class="fas fa-arrow-left"></i> Back
  </a>
  <h2 style="font-size:20px;color:#1e3a6e;font-weight:800">{{ $order->order_ref }}</h2>
  <span class="badge badge-{{ $order->status }}" style="font-size:13px;padding:5px 14px">
    {{ ucfirst(str_replace('_',' ',$order->status)) }}
  </span>
  <span style="margin-left:auto;font-size:12px;color:#aaa">
    <i class="fas fa-clock"></i> {{ date('d M Y H:i', strtotime($order->created_at)) }}
  </span>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px">

  {{-- ── LEFT COLUMN ── --}}
  <div>

    {{-- Order Items --}}
    <div class="data-card" style="margin-bottom:20px">
      <div class="data-card-hdr">
        <h3><i class="fas fa-box" style="margin-right:6px;color:#1e3a6e"></i>Order Items</h3>
      </div>
      <table class="data-table">
        <thead>
          <tr><th>Product</th><th>Options</th><th>Qty</th><th>Unit</th><th>Total</th></tr>
        </thead>
        <tbody>
          @foreach($items as $item)
          <tr>
            <td><strong>{{ $item->product_name }}</strong></td>
            <td style="font-size:12px;color:#888">
              @if($item->options)
                @foreach(json_decode($item->options,true) ?? [] as $k=>$v)
                  <span style="background:#f3f3f3;padding:1px 7px;border-radius:3px;margin:1px;display:inline-block">{{ $k }}: {{ $v }}</span>
                @endforeach
              @else —
              @endif
            </td>
            <td style="font-weight:700">{{ $item->quantity }}</td>
            <td style="color:#888">£{{ number_format($item->unit_price,2) }}</td>
            <td><strong style="color:#1e3a6e">£{{ number_format($item->line_total,2) }}</strong></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Update Status --}}
    <div class="data-card" style="margin-bottom:20px">
      <div class="data-card-hdr">
        <h3><i class="fas fa-edit" style="margin-right:6px;color:#1e3a6e"></i>Update Status</h3>
      </div>
      <div style="padding:20px">
        <form method="POST" action="{{ route('admin.orders.status',$order->id) }}">
          @csrf
          <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:14px;align-items:end">
            <div class="form-group" style="margin-bottom:0">
              <label>New Status</label>
              <select name="status" class="form-control">
                @foreach(['pending','confirmed','in_production','dispatched','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ $order->status===$s?'selected':'' }}>
                  {{ ucfirst(str_replace('_',' ',$s)) }}
                </option>
                @endforeach
              </select>
            </div>
            <div class="form-group" style="margin-bottom:0">
              <label>Note (optional)</label>
              <input type="text" name="note" class="form-control" placeholder="e.g. DHL tracking: 1Z99AA…">
            </div>
            <button type="submit" class="btn-yellow" style="white-space:nowrap">
              <i class="fas fa-save"></i> Save
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Status History Timeline --}}
    @if(count($history) > 0)
    <div class="data-card">
      <div class="data-card-hdr">
        <h3><i class="fas fa-history" style="margin-right:6px;color:#1e3a6e"></i>Status History</h3>
      </div>
      <div style="padding:20px 24px">
        <div class="timeline">
          @foreach($history as $i => $h)
          <div class="tl-item">
            <div class="tl-dot {{ $i===0 ? 'active' : '' }}"></div>
            <div class="tl-status">
              <span class="badge badge-{{ $h->status }}">{{ ucfirst(str_replace('_',' ',$h->status)) }}</span>
              @if($h->admin_name ?? null)
                <span style="font-size:11px;color:#aaa;margin-left:8px">by {{ $h->admin_name }}</span>
              @endif
            </div>
            @if($h->note)
              <div class="tl-note">{{ $h->note }}</div>
            @endif
            <div class="tl-time">{{ date('d M Y H:i',strtotime($h->created_at)) }}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif
  </div>

  {{-- ── RIGHT COLUMN ── --}}
  <div>

    {{-- Order Summary --}}
    <div class="data-card" style="margin-bottom:16px">
      <div class="data-card-hdr" style="background:#1e3a6e">
        <h3 style="color:#fff"><i class="fas fa-receipt" style="margin-right:6px"></i>Order Summary</h3>
      </div>
      <div style="padding:18px">
        <div style="display:flex;justify-content:space-between;font-size:13px;padding:6px 0;border-bottom:1px solid #f5f5f5">
          <span style="color:#666">Subtotal (ex. VAT)</span><span>£{{ number_format($order->subtotal,2) }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:13px;padding:6px 0;border-bottom:1px solid #f5f5f5">
          <span style="color:#666">VAT (20%)</span><span>£{{ number_format($order->vat,2) }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:13px;padding:6px 0;border-bottom:1px solid #f5f5f5">
          <span style="color:#666">Delivery</span>
          <span style="{{ $order->delivery_cost > 0 ? 'color:#333' : 'color:#22a85a;font-weight:700' }}">
            {{ $order->delivery_cost > 0 ? '£'.number_format($order->delivery_cost,2) : 'FREE' }}
          </span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:17px;font-weight:800;color:#1e3a6e;padding:10px 0 0">
          <span>Total</span><span>£{{ number_format($order->total,2) }}</span>
        </div>
        <div style="margin-top:10px;padding-top:10px;border-top:1px solid #f0f0f0">
          <div style="font-size:11px;color:#aaa;margin-bottom:4px">Payment Method</div>
          <span style="background:#f3f3f3;padding:4px 10px;border-radius:4px;font-size:12px;font-weight:600">
            {{ ucfirst($order->payment_method ?? 'Card') }}
          </span>
        </div>
      </div>
    </div>

    {{-- Customer Info --}}
    <div class="data-card" style="margin-bottom:16px">
      <div class="data-card-hdr">
        <h3><i class="fas fa-user" style="margin-right:6px;color:#1e3a6e"></i>Customer</h3>
        @if($order->user_id)
          <a href="{{ route('admin.users.show',$order->user_id) }}" class="btn-primary btn-sm">Profile</a>
        @endif
      </div>
      <div style="padding:16px;font-size:13px;line-height:1.9">
        <div style="font-weight:700;font-size:14px">{{ $order->first_name }} {{ $order->last_name }}</div>
        @if($order->company)<div style="color:#888">{{ $order->company }}</div>@endif
        <div><a href="mailto:{{ $order->email }}" style="color:#1e3a6e">{{ $order->email }}</a></div>
        <div style="color:#666">{{ $order->phone }}</div>
      </div>
    </div>

    {{-- Delivery Address --}}
    <div class="data-card">
      <div class="data-card-hdr">
        <h3><i class="fas fa-map-marker-alt" style="margin-right:6px;color:#1e3a6e"></i>Delivery Address</h3>
      </div>
      <div style="padding:16px;font-size:13px;line-height:1.9;color:#555">
        <div>{{ $order->address_line1 }}</div>
        @if($order->address_line2)<div>{{ $order->address_line2 }}</div>@endif
        <div>{{ $order->city }}, {{ $order->postcode }}</div>
        <div>{{ $order->country ?? 'United Kingdom' }}</div>
        @if($order->delivery_notes)
          <div style="margin-top:8px;padding:8px 10px;background:#fff8e6;border-radius:5px;font-size:12px;color:#888;border:1px solid #ffe099">
            <i class="fas fa-info-circle" style="color:#d08800"></i> {{ $order->delivery_notes }}
          </div>
        @endif
      </div>
    </div>

  </div>
</div>

@endsection
