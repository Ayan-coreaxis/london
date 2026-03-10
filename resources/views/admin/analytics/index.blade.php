@extends('layouts.admin')
@section('title','Analytics')
@section('page_title','Analytics & Reports')

@section('content')

{{-- Date range filter --}}
<form method="GET" style="display:flex;gap:10px;align-items:center;margin-bottom:22px;flex-wrap:wrap">
  <label style="font-size:12px;font-weight:700;color:#888;text-transform:uppercase">Period:</label>
  @foreach(['7'=>'Last 7 Days','30'=>'Last 30 Days','90'=>'Last 90 Days','365'=>'Last Year'] as $val=>$label)
  <a href="?days={{ $val }}"
     style="padding:8px 16px;border-radius:7px;font-size:12px;font-weight:700;text-decoration:none;border:1.5px solid {{ request('days',$defaultDays)==$val ? '#1e3a6e' : '#e0e0e0' }};background:{{ request('days',$defaultDays)==$val ? '#1e3a6e' : '#fff' }};color:{{ request('days',$defaultDays)==$val ? '#fff' : '#666' }}">
    {{ $label }}
  </a>
  @endforeach
</form>

{{-- KPI Row --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px">
  <div style="background:#fff;border:1px solid #e8e8e8;border-radius:10px;padding:18px 20px;border-left:4px solid #1e3a6e">
    <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Orders (Period)</div>
    <div style="font-size:28px;font-weight:900;color:#1e3a6e">{{ $periodOrders }}</div>
    <div style="font-size:11px;color:#aaa;margin-top:4px">{{ $days }} day window</div>
  </div>
  <div style="background:#fff;border:1px solid #e8e8e8;border-radius:10px;padding:18px 20px;border-left:4px solid #22a85a">
    <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Revenue (Period)</div>
    <div style="font-size:28px;font-weight:900;color:#22a85a">£{{ number_format($periodRevenue,2) }}</div>
    <div style="font-size:11px;color:#aaa;margin-top:4px">Excl. cancelled</div>
  </div>
  <div style="background:#fff;border:1px solid #e8e8e8;border-radius:10px;padding:18px 20px;border-left:4px solid #f5c800">
    <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Avg. Order Value</div>
    <div style="font-size:28px;font-weight:900;color:#d08800">
      £{{ $periodOrders > 0 ? number_format($periodRevenue / $periodOrders, 2) : '0.00' }}
    </div>
    <div style="font-size:11px;color:#aaa;margin-top:4px">Per order</div>
  </div>
  <div style="background:#fff;border:1px solid #e8e8e8;border-radius:10px;padding:18px 20px;border-left:4px solid #e8352a">
    <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">New Customers</div>
    <div style="font-size:28px;font-weight:900;color:#e8352a">{{ $newCustomers }}</div>
    <div style="font-size:11px;color:#aaa;margin-top:4px">Registered</div>
  </div>
</div>

{{-- Charts Row --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px">

  {{-- Revenue Over Time --}}
  <div class="data-card">
    <div class="data-card-hdr">
      <h3><i class="fas fa-chart-area" style="color:#1e3a6e;margin-right:8px"></i>Revenue Over Time</h3>
    </div>
    <div style="padding:20px">
      <canvas id="revenueLineChart" height="100"></canvas>
    </div>
  </div>

  {{-- Top Products --}}
  <div class="data-card">
    <div class="data-card-hdr">
      <h3><i class="fas fa-fire" style="color:#e8352a;margin-right:8px"></i>Top Products</h3>
    </div>
    <div style="padding:4px 0">
      @forelse($topProducts as $i => $prod)
      <div style="display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid #f5f5f5">
        <div style="width:24px;height:24px;border-radius:50%;background:{{ ['#1e3a6e','#22a85a','#f5c800','#e8352a','#9933cc'][$i] ?? '#888' }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;flex-shrink:0">
          {{ $i+1 }}
        </div>
        <div style="flex:1;min-width:0">
          <div style="font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $prod->product_name }}</div>
          <div style="font-size:11px;color:#aaa">{{ $prod->total_qty }} units sold</div>
        </div>
        <div style="text-align:right;flex-shrink:0">
          <div style="font-size:13px;font-weight:800;color:#1e3a6e">£{{ number_format($prod->total_revenue,2) }}</div>
        </div>
      </div>
      @empty
      <div style="padding:30px;text-align:center;color:#aaa">No data yet</div>
      @endforelse
    </div>
  </div>
</div>

{{-- Orders + Customers Row --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">

  {{-- Orders by Day --}}
  <div class="data-card">
    <div class="data-card-hdr">
      <h3><i class="fas fa-chart-bar" style="color:#1e3a6e;margin-right:8px"></i>Orders per Day</h3>
    </div>
    <div style="padding:20px">
      <canvas id="ordersBarChart" height="120"></canvas>
    </div>
  </div>

  {{-- Status breakdown --}}
  <div class="data-card">
    <div class="data-card-hdr">
      <h3><i class="fas fa-chart-pie" style="color:#1e3a6e;margin-right:8px"></i>Status Breakdown (Period)</h3>
    </div>
    <div style="padding:20px;display:flex;gap:20px;align-items:center">
      <canvas id="statusDonut" style="max-width:150px;max-height:150px"></canvas>
      <div style="flex:1">
        @php $sColors = ['pending'=>'#f5c800','confirmed'=>'#0066cc','in_production'=>'#9933cc','dispatched'=>'#ff6600','completed'=>'#22a85a','cancelled'=>'#e8352a']; @endphp
        @foreach($statusBreakdown as $row)
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
          <div style="display:flex;align-items:center;gap:8px">
            <div style="width:10px;height:10px;border-radius:2px;background:{{ $sColors[$row->status] ?? '#888' }}"></div>
            <span style="font-size:12px;color:#555">{{ ucfirst(str_replace('_',' ',$row->status)) }}</span>
          </div>
          <strong style="font-size:12px">{{ $row->count }}</strong>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const revenueData = {!! json_encode($revenueByDay) !!};
new Chart(document.getElementById('revenueLineChart').getContext('2d'), {
  type: 'line',
  data: {
    labels: revenueData.map(d => d.day),
    datasets: [{
      label: 'Revenue £',
      data: revenueData.map(d => parseFloat(d.revenue)),
      borderColor: '#1e3a6e', backgroundColor: 'rgba(30,58,110,0.07)',
      borderWidth: 2, fill: true, tension: 0.3, pointRadius: 3,
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true, ticks: { callback: v => '£'+v }, grid: { color: '#f5f5f5' } },
      x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } }
    }
  }
});

const ordersData = {!! json_encode($ordersByDay) !!};
new Chart(document.getElementById('ordersBarChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: ordersData.map(d => d.day),
    datasets: [{
      label: 'Orders',
      data: ordersData.map(d => d.count),
      backgroundColor: 'rgba(30,58,110,0.15)', borderColor: '#1e3a6e',
      borderWidth: 1.5, borderRadius: 4,
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f5f5f5' } },
      x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } }
    }
  }
});

new Chart(document.getElementById('statusDonut').getContext('2d'), {
  type: 'doughnut',
  data: {
    labels: {!! json_encode(collect($statusBreakdown)->pluck('status')->map(fn($s)=>ucfirst(str_replace('_',' ',$s)))) !!},
    datasets: [{
      data: {!! json_encode(collect($statusBreakdown)->pluck('count')) !!},
      backgroundColor: ['#f5c800','#0066cc','#9933cc','#ff6600','#22a85a','#e8352a'],
      borderWidth: 0,
    }]
  },
  options: { cutout: '60%', plugins: { legend: { display: false } }, responsive: true }
});
</script>
@endsection
