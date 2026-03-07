@extends('layouts.admin')
@section('title','Promo Codes')
@section('page_title','Promo Codes')

@section('content')
{{-- Create New --}}
<div class="data-card" style="margin-bottom:24px">
    <div class="data-card-hdr"><h3><i class="fas fa-plus-circle" style="color:#1e3a6e;margin-right:6px"></i>Create New Promo Code</h3></div>
    <div style="padding:20px">
        <form method="POST" action="{{ route('admin.promos.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr 1fr 1fr auto;gap:12px;align-items:end">
            <div class="form-group" style="margin:0"><label>Code</label><input type="text" name="code" class="form-control" required placeholder="SUMMER20" style="text-transform:uppercase"></div>
            <div class="form-group" style="margin:0"><label>Type</label><select name="type" class="form-control"><option value="percentage">Percentage (%)</option><option value="fixed">Fixed (£)</option></select></div>
            <div class="form-group" style="margin:0"><label>Value</label><input type="number" step="0.01" name="value" class="form-control" required placeholder="10"></div>
            <div class="form-group" style="margin:0"><label>Min Order (£)</label><input type="number" step="0.01" name="min_order_amount" class="form-control" value="0"></div>
            <div class="form-group" style="margin:0"><label>Max Uses</label><input type="number" name="max_uses" class="form-control" placeholder="Unlimited"></div>
            <div class="form-group" style="margin:0"><label>Expires</label><input type="date" name="expires_at" class="form-control"></div>
            <button type="submit" class="btn-yellow" style="height:42px;white-space:nowrap"><i class="fas fa-plus"></i> Create</button>
        </div>
        </form>
    </div>
</div>

{{-- Existing --}}
<div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-tags" style="color:#1e3a6e;margin-right:6px"></i>All Promo Codes</h3></div>
    <table class="data-table">
        <thead><tr><th>Code</th><th>Type</th><th>Value</th><th>Min Order</th><th>Used</th><th>Expires</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($promos as $p)
        <tr>
            <td><strong style="font-family:monospace;font-size:14px;color:#1e3a6e">{{ $p->code }}</strong></td>
            <td>{{ ucfirst($p->type) }}</td>
            <td><strong>{{ $p->type==='percentage' ? $p->value.'%' : '£'.number_format($p->value,2) }}</strong></td>
            <td>£{{ number_format($p->min_order_amount,2) }}</td>
            <td>{{ $p->used_count }}{{ $p->max_uses ? '/'.$p->max_uses : '' }}</td>
            <td>{{ $p->expires_at ? date('d M Y', strtotime($p->expires_at)) : '—' }}</td>
            <td>
                @if($p->is_active)<span class="badge badge-completed">Active</span>
                @else<span class="badge badge-cancelled">Disabled</span>@endif
            </td>
            <td style="display:flex;gap:6px">
                <form method="POST" action="{{ route('admin.promos.toggle', $p->id) }}" style="display:inline">@csrf<button type="submit" class="btn-primary btn-sm" style="background:{{ $p->is_active?'#e53935':'#22a85a' }}">{{ $p->is_active?'Disable':'Enable' }}</button></form>
                <form method="POST" action="{{ route('admin.promos.delete', $p->id) }}" onsubmit="return confirm('Delete?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="btn-primary btn-sm" style="background:#e53935"><i class="fas fa-trash"></i></button></form>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;color:#aaa;padding:24px">No promo codes yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
