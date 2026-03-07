@extends('layouts.admin')
@section('title','Activity Log')
@section('page_title','Admin Activity Log')

@section('content')
<div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-history" style="color:#1e3a6e;margin-right:6px"></i>Recent Activity</h3></div>
    <table class="data-table">
        <thead><tr><th>Time</th><th>Admin</th><th>Action</th><th>Entity</th><th>Details</th><th>IP</th></tr></thead>
        <tbody>
        @forelse($logs as $log)
        <tr>
            <td style="font-size:12px;color:#888;white-space:nowrap">{{ date('d M Y H:i', strtotime($log->created_at)) }}</td>
            <td><strong style="font-size:12px">{{ $log->admin_name ?? '—' }}</strong></td>
            <td><span style="background:#f0f4ff;color:#1e3a6e;padding:3px 10px;border-radius:4px;font-size:11px;font-weight:700">{{ str_replace('_',' ',ucfirst($log->action)) }}</span></td>
            <td style="font-size:12px;color:#666">{{ $log->entity_type ? ucfirst($log->entity_type) . ' #' . $log->entity_id : '—' }}</td>
            <td style="font-size:12px;color:#888;max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $log->details }}">{{ \Illuminate\Support\Str::limit($log->details, 80) }}</td>
            <td style="font-size:11px;color:#aaa;font-family:monospace">{{ $log->ip_address }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:30px;color:#aaa">No activity logged yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
