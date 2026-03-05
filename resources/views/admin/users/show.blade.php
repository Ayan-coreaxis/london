@extends('layouts.admin')
@section('title','Customer: '.$user->name)
@section('page_title','Customer Profile')

@section('content')

<div style="display:flex;gap:10px;margin-bottom:24px;align-items:center">
  <a href="{{ route('admin.users.index') }}" class="btn-primary btn-sm" style="background:#888">
    <i class="fas fa-arrow-left"></i> Back
  </a>
  <h2 style="font-size:18px;color:#1e3a6e;font-weight:800">{{ $user->name }}</h2>
</div>

<div style="display:grid;grid-template-columns:300px 1fr;gap:20px">

  {{-- LEFT: Profile card --}}
  <div>
    <div class="data-card" style="margin-bottom:16px">
      <div class="data-card-hdr" style="background:#1e3a6e">
        <h3 style="color:#fff"><i class="fas fa-user" style="margin-right:6px"></i>Customer Info</h3>
      </div>
      <div style="padding:20px">

        {{-- Avatar --}}
        <div style="text-align:center;margin-bottom:18px">
          <div style="width:72px;height:72px;border-radius:50%;background:#eef3ff;color:#1e3a6e;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:900;margin:0 auto 10px">
            {{ strtoupper(substr($user->name,0,1)) }}
          </div>
          <div style="font-weight:800;font-size:16px">{{ $user->name }}</div>
          <div style="font-size:12px;color:#aaa;margin-top:2px">
            Customer since {{ date('M Y',strtotime($user->created_at)) }}
          </div>
        </div>

        {{-- Details --}}
        <div style="border-top:1px solid #f0f0f0;padding-top:14px;font-size:13px">
          <div style="display:flex;gap:8px;padding:7px 0;border-bottom:1px solid #f8f8f8">
            <i class="fas fa-envelope" style="color:#1e3a6e;width:16px;margin-top:2px"></i>
            <a href="mailto:{{ $user->email }}" style="color:#1e3a6e;word-break:break-all">{{ $user->email }}</a>
          </div>
          <div style="display:flex;gap:8px;padding:7px 0;border-bottom:1px solid #f8f8f8">
            <i class="fas fa-phone" style="color:#1e3a6e;width:16px;margin-top:2px"></i>
            <span style="color:#555">{{ $user->phone ?: '—' }}</span>
          </div>
          <div style="display:flex;gap:8px;padding:7px 0">
            <i class="fas fa-building" style="color:#1e3a6e;width:16px;margin-top:2px"></i>
            <span style="color:#555">{{ $user->company ?: '—' }}</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Stats card --}}
    <div class="data-card">
      <div class="data-card-hdr"><h3><i class="fas fa-chart-bar" style="margin-right:6px;color:#1e3a6e"></i>Stats</h3></div>
      <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:10px">
        <div style="background:#eef3ff;border-radius:8px;padding:14px;text-align:center">
          <div style="font-size:26px;font-weight:900;color:#1e3a6e;line-height:1">{{ count($orders) }}</div>
          <div style="font-size:11px;color:#888;font-weight:600;margin-top:4px">Total Orders</div>
        </div>
        <div style="background:#e6ffed;border-radius:8px;padding:14px;text-align:center">
          <div style="font-size:18px;font-weight:900;color:#1a7a1a;line-height:1">
            £{{ number_format(collect($orders)->where('status','!=','cancelled')->sum('total'),2) }}
          </div>
          <div style="font-size:11px;color:#888;font-weight:600;margin-top:4px">Total Spent</div>
        </div>
        <div style="background:#fff8e6;border-radius:8px;padding:14px;text-align:center">
          <div style="font-size:22px;font-weight:900;color:#d08800;line-height:1">
            {{ collect($orders)->whereIn('status',['pending','confirmed','in_production','dispatched'])->count() }}
          </div>
          <div style="font-size:11px;color:#888;font-weight:600;margin-top:4px">Active Orders</div>
        </div>
        <div style="background:#fff0f0;border-radius:8px;padding:14px;text-align:center">
          <div style="font-size:22px;font-weight:900;color:#cc0000;line-height:1">
            {{ collect($orders)->where('status','cancelled')->count() }}
          </div>
          <div style="font-size:11px;color:#888;font-weight:600;margin-top:4px">Cancelled</div>
        </div>
      </div>
    </div>
  </div>

  {{-- RIGHT: Orders --}}
  <div>
    <div class="data-card">
      <div class="data-card-hdr">
        <h3><i class="fas fa-shopping-bag" style="margin-right:6px;color:#1e3a6e"></i>Order History</h3>
      </div>
      <table class="data-table">
        <thead>
          <tr><th>Ref</th><th>Total</th><th>Delivery</th><th>Status</th><th>Date</th><th></th></tr>
        </thead>
        <tbody>
          @forelse($orders as $order)
          <tr>
            <td><strong style="color:#1e3a6e">{{ $order->order_ref }}</strong></td>
            <td><strong>£{{ number_format($order->total,2) }}</strong></td>
            <td>
              @if($order->delivery_cost > 0)
                <span style="font-size:11px;background:#fff4ee;color:#cc4400;padding:2px 8px;border-radius:4px;border:1px solid #ffbb88">Same Day</span>
              @else
                <span style="font-size:11px;background:#e6ffed;color:#1a7a1a;padding:2px 8px;border-radius:4px;border:1px solid #99ddaa">Next Day</span>
              @endif
            </td>
            <td><span class="badge badge-{{ $order->status }}">{{ ucfirst(str_replace('_',' ',$order->status)) }}</span></td>
            <td style="font-size:12px;color:#aaa">{{ date('d M Y',strtotime($order->created_at)) }}</td>
            <td><a href="{{ route('admin.orders.show',$order->id) }}" class="btn-primary btn-sm">View</a></td>
          </tr>
          @empty
          <tr>
            <td colspan="6" style="text-align:center;padding:40px;color:#aaa">No orders yet</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection
