@extends('layouts.admin')
@section('title','Customers')
@section('page_title','Customer Management')

@section('content')

{{-- Search --}}
<form method="GET" style="margin-bottom:18px;display:flex;gap:10px">
  <div style="position:relative;flex:1;max-width:420px">
    <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#aaa;font-size:13px"></i>
    <input type="text" name="search" value="{{ request('search') }}"
      placeholder="Search by name, email, company…"
      style="width:100%;height:42px;border:1.5px solid #ddd;border-radius:7px;padding:0 14px 0 36px;font-size:13px;font-family:'Inter',sans-serif;outline:none"
      onfocus="this.style.borderColor='#1e3a6e'" onblur="this.style.borderColor='#ddd'">
  </div>
  <button type="submit" class="btn-primary"><i class="fas fa-search"></i> Search</button>
  @if(request('search'))
    <a href="{{ route('admin.users.index') }}" class="btn-primary" style="background:#888"><i class="fas fa-times"></i> Clear</a>
  @endif
</form>

{{-- Stats row --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px">
  <div style="background:#fff;border:1px solid #e8e8e8;border-radius:10px;padding:16px 20px;border-left:4px solid #1e3a6e">
    <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Total Customers</div>
    <div style="font-size:28px;font-weight:800;color:#1e3a6e">{{ count($users) }}</div>
  </div>
  <div style="background:#fff;border:1px solid #e8e8e8;border-radius:10px;padding:16px 20px;border-left:4px solid #22a85a">
    <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Total Revenue</div>
    <div style="font-size:28px;font-weight:800;color:#22a85a">£{{ number_format(collect($users)->sum('total_spent'),2) }}</div>
  </div>
  <div style="background:#fff;border:1px solid #e8e8e8;border-radius:10px;padding:16px 20px;border-left:4px solid #f5c800">
    <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Avg. Per Customer</div>
    <div style="font-size:28px;font-weight:800;color:#d08800">
      £{{ count($users) > 0 ? number_format(collect($users)->sum('total_spent') / count($users),2) : '0.00' }}
    </div>
  </div>
</div>

{{-- Table --}}
<div class="data-card">
  <div class="data-card-hdr">
    <h3><i class="fas fa-users" style="margin-right:6px;color:#1e3a6e"></i>All Customers ({{ count($users) }})</h3>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Customer</th>
        <th>Company</th>
        <th>Phone</th>
        <th>Orders</th>
        <th>Total Spent</th>
        <th>Joined</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $user)
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:10px">
            <div style="width:34px;height:34px;border-radius:50%;background:#eef3ff;color:#1e3a6e;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;flex-shrink:0">
              {{ strtoupper(substr($user->name,0,1)) }}
            </div>
            <div>
              <div style="font-weight:600;font-size:13px">{{ $user->name }}</div>
              <div style="font-size:11px;color:#aaa">{{ $user->email }}</div>
            </div>
          </div>
        </td>
        <td style="font-size:12px;color:#888">{{ $user->company ?: '—' }}</td>
        <td style="font-size:12px;color:#888">{{ $user->phone ?: '—' }}</td>
        <td>
          <span style="background:#eef3ff;color:#1e3a6e;padding:3px 10px;border-radius:10px;font-size:12px;font-weight:700">
            {{ $user->order_count }}
          </span>
        </td>
        <td>
          <strong style="color:{{ $user->total_spent > 0 ? '#1a7a1a' : '#aaa' }}">
            £{{ number_format($user->total_spent,2) }}
          </strong>
        </td>
        <td style="font-size:12px;color:#aaa">{{ date('d M Y',strtotime($user->created_at)) }}</td>
        <td>
          <a href="{{ route('admin.users.show',$user->id) }}" class="btn-primary btn-sm">
            <i class="fas fa-eye"></i> View
          </a>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" style="text-align:center;padding:48px;color:#aaa">
          <i class="fas fa-users" style="font-size:32px;margin-bottom:10px;display:block;opacity:.3"></i>
          No customers found
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
