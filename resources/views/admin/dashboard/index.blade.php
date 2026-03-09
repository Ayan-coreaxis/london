@extends('layouts.admin')
@section('title','Dashboard')
@section('page_title','Dashboard Overview')

@section('content')

{{-- ── STAT CARDS ── --}}
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px">

  <div class="stat-card" style="border-left:4px solid #1e3a6e">
    <div class="icon" style="background:#eef3ff;color:#1e3a6e;float:right"><i class="fas fa-shopping-bag"></i></div>
    <div class="label">Total Orders</div>
    <div class="value">{{ number_format($totalOrders) }}</div>
    <div class="change"><i class="fas fa-circle" style="font-size:7px;color:#1e3a6e"></i> All time</div>
  </div>

  <div class="stat-card" style="border-left:4px solid #22a85a">
    <div class="icon" style="background:#e6ffed;color:#1a7a1a;float:right"><i class="fas fa-pound-sign"></i></div>
    <div class="label">Total Revenue</div>
    <div class="value">£{{ number_format($totalRevenue,2) }}</div>
    <div class="change" style="color:#22a85a"><i class="fas fa-arrow-up"></i> Excl. cancelled</div>
  </div>

  <div class="stat-card" style="border-left:4px solid #f5c800">
    <div class="icon" style="background:#fff8e6;color:#d08800;float:right"><i class="fas fa-users"></i></div>
    <div class="label">Customers</div>
    <div class="value">{{ number_format($totalUsers) }}</div>
    <div class="change" style="color:#d08800"><i class="fas fa-user-plus"></i> Registered</div>
  </div>

  <div class="stat-card" style="border-left:4px solid #e8352a">
    <div class="icon" style="background:#fff0f0;color:#cc3300;float:right"><i class="fas fa-eye"></i></div>
    <div class="label">Today's Visitors</div>
    <div class="value">{{ number_format($todayVisitors) }}</div>
    <div class="change" style="color:#888"><i class="fas fa-calendar"></i> {{ number_format($monthVisitors) }} this month</div>
  </div>

</div>

{{-- ── CHARTS ROW ── --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px">

  {{-- Revenue Chart --}}
  <div class="data-card">
    <div class="data-card-hdr">
      <h3><i class="fas fa-chart-line" style="color:#1e3a6e;margin-right:8px"></i>Revenue – Last 6 Months</h3>
    </div>
    <div style="padding:20px">
      <canvas id="revenueChart" height="90"></canvas>
    </div>
  </div>

  {{-- Orders by Status (Doughnut) --}}
  <div class="data-card">
    <div class="data-card-hdr">
      <h3><i class="fas fa-chart-pie" style="color:#1e3a6e;margin-right:8px"></i>Orders by Status</h3>
    </div>
    <div style="padding:20px;display:flex;flex-direction:column;align-items:center">
      <canvas id="statusChart" height="160" style="max-width:200px"></canvas>
      <div style="margin-top:14px;width:100%">
        @php
          $statusColors = ['pending'=>'#f5c800','confirmed'=>'#0066cc','in_production'=>'#9933cc','dispatched'=>'#ff6600','completed'=>'#22a85a','cancelled'=>'#e8352a'];
        @endphp
        @foreach($ordersByStatus as $row)
        <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;margin-bottom:5px">
          <div style="display:flex;align-items:center;gap:7px">
            <div style="width:10px;height:10px;border-radius:2px;background:{{ $statusColors[$row->status] ?? '#888' }}"></div>
            <span style="color:#555">{{ ucfirst(str_replace('_',' ',$row->status)) }}</span>
          </div>
          <strong>{{ $row->count }}</strong>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

{{-- ── RECENT ORDERS + QUICK STATS ── --}}
<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;margin-bottom:24px">

  {{-- Recent Orders --}}
  <div class="data-card">
    <div class="data-card-hdr">
      <h3><i class="fas fa-clock" style="color:#1e3a6e;margin-right:8px"></i>Recent Orders</h3>
      <a href="{{ route('admin.orders.index') }}" class="btn-primary btn-sm">View All</a>
    </div>
    <table class="data-table">
      <thead>
        <tr><th>Ref</th><th>Customer</th><th>Items</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr>
      </thead>
      <tbody>
        @forelse($recentOrders as $order)
        <tr>
          <td><strong style="color:#1e3a6e">{{ $order->order_ref }}</strong></td>
          <td>
            <div style="font-size:13px;font-weight:600">{{ $order->first_name }} {{ $order->last_name }}</div>
            <div style="font-size:11px;color:#aaa">{{ $order->email }}</div>
          </td>
          <td style="color:#888;font-size:12px">—</td>
          <td><strong>£{{ number_format($order->total,2) }}</strong></td>
          <td><span class="badge badge-{{ $order->status }}">{{ ucfirst(str_replace('_',' ',$order->status)) }}</span></td>
          <td style="font-size:12px;color:#aaa">{{ date('d M',strtotime($order->created_at)) }}</td>
          <td><a href="{{ route('admin.orders.show',$order->id) }}" class="btn-primary btn-sm">View</a></td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:30px;color:#aaa">No orders yet</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Quick Links + Mini Stats --}}
  <div>
    <div class="data-card" style="margin-bottom:16px">
      <div class="data-card-hdr"><h3>Quick Actions</h3></div>
      <div style="padding:16px;display:flex;flex-direction:column;gap:9px">
        <a href="{{ route('admin.orders.index') }}?status=pending" style="display:flex;align-items:center;gap:10px;padding:11px 14px;background:#fff8e6;border-radius:7px;text-decoration:none;border:1px solid #ffe099;transition:background .2s" onmouseover="this.style.background='#fff0cc'" onmouseout="this.style.background='#fff8e6'">
          <i class="fas fa-hourglass-half" style="color:#d08800;width:16px"></i>
          <span style="font-size:13px;color:#333;font-weight:600">Pending Orders</span>
          <span style="margin-left:auto;background:#f5c800;color:#1a1a1a;font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px">
            {{ collect($ordersByStatus)->where('status','pending')->first()->count ?? 0 }}
          </span>
        </a>
        <a href="{{ route('admin.orders.index') }}?status=in_production" style="display:flex;align-items:center;gap:10px;padding:11px 14px;background:#f0e8ff;border-radius:7px;text-decoration:none;border:1px solid #cc99ff;transition:background .2s" onmouseover="this.style.background='#e8d8ff'" onmouseout="this.style.background='#f0e8ff'">
          <i class="fas fa-print" style="color:#7700cc;width:16px"></i>
          <span style="font-size:13px;color:#333;font-weight:600">In Production</span>
          <span style="margin-left:auto;background:#9933cc;color:#fff;font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px">
            {{ collect($ordersByStatus)->where('status','in_production')->first()->count ?? 0 }}
          </span>
        </a>
        <a href="{{ route('admin.orders.index') }}?status=dispatched" style="display:flex;align-items:center;gap:10px;padding:11px 14px;background:#fff4ee;border-radius:7px;text-decoration:none;border:1px solid #ffbb88;transition:background .2s" onmouseover="this.style.background='#ffe8d8'" onmouseout="this.style.background='#fff4ee'">
          <i class="fas fa-truck" style="color:#cc4400;width:16px"></i>
          <span style="font-size:13px;color:#333;font-weight:600">Dispatched</span>
          <span style="margin-left:auto;background:#ff6600;color:#fff;font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px">
            {{ collect($ordersByStatus)->where('status','dispatched')->first()->count ?? 0 }}
          </span>
        </a>
        <a href="{{ route('admin.products.create') }}" style="display:flex;align-items:center;gap:10px;padding:11px 14px;background:#eef3ff;border-radius:7px;text-decoration:none;border:1px solid #99bbff;transition:background .2s" onmouseover="this.style.background='#dde9ff'" onmouseout="this.style.background='#eef3ff'">
          <i class="fas fa-plus-circle" style="color:#1e3a6e;width:16px"></i>
          <span style="font-size:13px;color:#333;font-weight:600">Add New Product</span>
          <i class="fas fa-chevron-right" style="margin-left:auto;color:#aaa;font-size:11px"></i>
        </a>
        <a href="{{ route('admin.users.index') }}" style="display:flex;align-items:center;gap:10px;padding:11px 14px;background:#e6ffed;border-radius:7px;text-decoration:none;border:1px solid #99ddaa;transition:background .2s" onmouseover="this.style.background='#d0f8e0'" onmouseout="this.style.background='#e6ffed'">
          <i class="fas fa-users" style="color:#1a7a1a;width:16px"></i>
          <span style="font-size:13px;color:#333;font-weight:600">All Customers</span>
          <span style="margin-left:auto;background:#22a85a;color:#fff;font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px">{{ $totalUsers }}</span>
        </a>
      </div>
    </div>
  </div>

</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
  type: 'bar',
  data: {
    labels: {!! json_encode(collect($revenueChart)->pluck('month')) !!},
    datasets: [{
      label: 'Revenue (£)',
      data: {!! json_encode(collect($revenueChart)->pluck('revenue')->map(fn($v)=>round($v,2))) !!},
      backgroundColor: 'rgba(30,58,110,0.12)',
      borderColor: '#1e3a6e',
      borderWidth: 2,
      borderRadius: 5,
      fill: true,
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true, ticks: { callback: v => '£'+v.toLocaleString() }, grid: { color: '#f0f0f0' } },
      x: { grid: { display: false } }
    }
  }
});

// Status Doughnut
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
  type: 'doughnut',
  data: {
    labels: {!! json_encode(collect($ordersByStatus)->pluck('status')->map(fn($s)=>ucfirst(str_replace('_',' ',$s)))) !!},
    datasets: [{
      data: {!! json_encode(collect($ordersByStatus)->pluck('count')) !!},
      backgroundColor: ['#f5c800','#0066cc','#9933cc','#ff6600','#22a85a','#e8352a'],
      borderWidth: 0,
    }]
  },
  options: {
    cutout: '65%',
    plugins: { legend: { display: false } },
    responsive: true,
  }
});
</script>
@endsection
