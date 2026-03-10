@extends('layouts.admin')
@section('title','Payment Methods')
@section('page_title','Payment Methods')

@section('content')
<form method="POST" action="{{ route('admin.payment-methods.save') }}">
@csrf

@foreach($methods as $i => $m)
@php $config = json_decode($m->config, true) ?? []; @endphp
<div class="data-card" style="margin-bottom:20px">
    <div class="data-card-hdr" style="display:flex;align-items:center;justify-content:space-between">
        <h3>
            @if($m->provider==='stripe')<i class="fab fa-stripe" style="color:#635bff;margin-right:6px;font-size:18px"></i>
            @elseif($m->provider==='paypal')<i class="fab fa-paypal" style="color:#003087;margin-right:6px;font-size:18px"></i>
            @else<i class="fas fa-credit-card" style="color:#1e3a6e;margin-right:6px"></i>
            @endif
            {{ $m->name }}
        </h3>
        <div style="display:flex;gap:12px;align-items:center">
            @if($m->is_test_mode)<span style="background:#fff3cd;color:#856404;padding:3px 10px;border-radius:4px;font-size:11px;font-weight:700">TEST MODE</span>@endif
            <span style="background:{{ $m->is_active ? '#d4edda' : '#f8d7da' }};color:{{ $m->is_active ? '#155724' : '#721c24' }};padding:3px 10px;border-radius:4px;font-size:11px;font-weight:700">{{ $m->is_active ? 'ACTIVE' : 'DISABLED' }}</span>
        </div>
    </div>
    <div style="padding:20px">
        <input type="hidden" name="pm_id[]" value="{{ $m->id }}">
        <div style="display:grid;grid-template-columns:1fr 1fr auto auto;gap:14px;margin-bottom:16px">
            <div class="form-group" style="margin:0"><label>Display Name</label><input type="text" name="pm_name[]" value="{{ $m->name }}" class="form-control"></div>
            <div class="form-group" style="margin:0"><label>Description</label><input type="text" name="pm_description[]" value="{{ $m->description }}" class="form-control"></div>
            <div class="form-group" style="margin:0"><label>Active</label><select name="pm_active[{{ $i }}]" class="form-control"><option value="1" {{ $m->is_active?'selected':'' }}>Yes</option><option value="0" {{ !$m->is_active?'selected':'' }}>No</option></select></div>
            <div class="form-group" style="margin:0"><label>Test Mode</label><select name="pm_test_mode[{{ $i }}]" class="form-control"><option value="1" {{ $m->is_test_mode?'selected':'' }}>Test</option><option value="0" {{ !$m->is_test_mode?'selected':'' }}>Live</option></select></div>
        </div>

        {{-- Provider-specific config --}}
        @if($m->provider === 'stripe')
        <div style="background:#f8f9ff;border:1px solid #e0e7ff;border-radius:8px;padding:16px;margin-top:8px">
            <div style="font-weight:700;font-size:13px;color:#1e3a6e;margin-bottom:12px"><i class="fab fa-stripe"></i> Stripe Configuration</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div class="form-group" style="margin:0"><label>Publishable Key (pk_...)</label><input type="text" name="pm_config_{{ $m->id }}[public_key]" value="{{ $config['public_key'] ?? '' }}" class="form-control" placeholder="pk_test_..." style="font-family:monospace;font-size:12px"></div>
                <div class="form-group" style="margin:0"><label>Secret Key (sk_...)</label><input type="password" name="pm_config_{{ $m->id }}[secret_key]" value="{{ $config['secret_key'] ?? '' }}" class="form-control" placeholder="sk_test_..." style="font-family:monospace;font-size:12px"></div>
                <div class="form-group" style="margin:0"><label>Webhook Secret (whsec_...)</label><input type="text" name="pm_config_{{ $m->id }}[webhook_secret]" value="{{ $config['webhook_secret'] ?? '' }}" class="form-control" placeholder="whsec_..." style="font-family:monospace;font-size:12px"></div>
            </div>
            <div style="margin-top:10px;font-size:12px;color:#6366f1"><i class="fas fa-info-circle"></i> Get keys from <a href="https://dashboard.stripe.com/apikeys" target="_blank" style="color:#4f46e5;font-weight:700">dashboard.stripe.com</a></div>
        </div>
        @elseif($m->provider === 'paypal')
        <div style="background:#fff8f0;border:1px solid #fde68a;border-radius:8px;padding:16px;margin-top:8px">
            <div style="font-weight:700;font-size:13px;color:#92400e;margin-bottom:12px"><i class="fab fa-paypal"></i> PayPal Configuration</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div class="form-group" style="margin:0"><label>Client ID</label><input type="text" name="pm_config_{{ $m->id }}[client_id]" value="{{ $config['client_id'] ?? '' }}" class="form-control" style="font-family:monospace;font-size:12px"></div>
                <div class="form-group" style="margin:0"><label>Secret</label><input type="password" name="pm_config_{{ $m->id }}[secret]" value="{{ $config['secret'] ?? '' }}" class="form-control" style="font-family:monospace;font-size:12px"></div>
                <div class="form-group" style="margin:0"><label>Mode</label><select name="pm_config_{{ $m->id }}[mode]" class="form-control"><option value="sandbox" {{ ($config['mode']??'')==='sandbox'?'selected':'' }}>Sandbox</option><option value="live" {{ ($config['mode']??'')==='live'?'selected':'' }}>Live</option></select></div>
                <div class="form-group" style="margin:0"><label>Currency</label><input type="text" name="pm_config_{{ $m->id }}[currency]" value="{{ $config['currency'] ?? 'GBP' }}" class="form-control"></div>
            </div>
            <div style="margin-top:10px;font-size:12px;color:#b45309"><i class="fas fa-info-circle"></i> Get credentials from <a href="https://developer.paypal.com" target="_blank" style="color:#92400e;font-weight:700">developer.paypal.com</a></div>
        </div>
        @elseif($m->provider === 'manual' && $m->slug === 'bank_transfer')
        <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:16px;margin-top:8px">
            <div style="font-weight:700;font-size:13px;color:#0369a1;margin-bottom:12px"><i class="fas fa-university"></i> Bank Details (shown to customer)</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div class="form-group" style="margin:0"><label>Bank Name</label><input type="text" name="pm_config_{{ $m->id }}[bank_name]" value="{{ $config['bank_name'] ?? '' }}" class="form-control"></div>
                <div class="form-group" style="margin:0"><label>Account Name</label><input type="text" name="pm_config_{{ $m->id }}[account_name]" value="{{ $config['account_name'] ?? '' }}" class="form-control"></div>
                <div class="form-group" style="margin:0"><label>Sort Code</label><input type="text" name="pm_config_{{ $m->id }}[sort_code]" value="{{ $config['sort_code'] ?? '' }}" class="form-control"></div>
                <div class="form-group" style="margin:0"><label>Account Number</label><input type="text" name="pm_config_{{ $m->id }}[account_number]" value="{{ $config['account_number'] ?? '' }}" class="form-control"></div>
            </div>
        </div>
        @elseif($m->provider === 'manual' && $m->slug === 'invoice')
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:16px;margin-top:8px">
            <div style="font-weight:700;font-size:13px;color:#166534;margin-bottom:8px"><i class="fas fa-file-invoice"></i> Invoice Settings</div>
            <div class="form-group" style="margin:0;max-width:200px"><label>Payment Terms (days)</label><input type="number" name="pm_config_{{ $m->id }}[terms_days]" value="{{ $config['terms_days'] ?? 30 }}" class="form-control"></div>
        </div>
        @endif
    </div>
</div>
@endforeach

<button type="submit" class="btn-yellow" style="padding:13px 36px;font-size:15px"><i class="fas fa-save"></i> Save Payment Settings</button>
</form>
@endsection
