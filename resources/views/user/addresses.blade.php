@extends('layouts.app')
@section('title', 'My Addresses – London InstantPrint')

@section('styles')
<style>
.account-wrap{max-width:1100px;margin:0 auto;padding:36px 24px 80px;font-family:'Open Sans',sans-serif}
.account-breadcrumb{font-size:13px;color:#888;margin-bottom:28px}.account-breadcrumb a{color:#888;text-decoration:none}.account-breadcrumb span{margin:0 6px}
.account-nav{display:flex;gap:8px;margin-bottom:28px;flex-wrap:wrap}
.account-nav a{padding:9px 18px;border:1.5px solid #e0e0e0;border-radius:7px;font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;color:#555;text-decoration:none;transition:all .2s}
.account-nav a:hover,.account-nav a.active{background:#1e3a6e;color:#fff;border-color:#1e3a6e}
.addr-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px;margin-bottom:24px}
.addr-card{background:#fff;border:1px solid #e8e8e8;border-radius:10px;padding:20px;position:relative}
.addr-card.default{border-color:#1e3a6e;border-width:2px}
.addr-label{font-family:'Montserrat',sans-serif;font-size:13px;font-weight:800;color:#1e3a6e;margin-bottom:6px;display:flex;align-items:center;gap:8px}
.addr-default-badge{background:#e8f0fe;color:#1e3a6e;font-size:10px;padding:2px 8px;border-radius:10px;font-weight:700}
.addr-text{font-size:13px;color:#444;line-height:1.7;margin-bottom:12px}
.addr-actions{display:flex;gap:8px;flex-wrap:wrap}
.addr-btn{padding:6px 12px;border-radius:5px;font-size:11px;font-weight:700;cursor:pointer;border:none;text-decoration:none;transition:all .2s;font-family:'Montserrat',sans-serif}
.addr-btn-edit{background:#f0f4ff;color:#1e3a6e}.addr-btn-edit:hover{background:#dde9ff}
.addr-btn-default{background:#f0faf0;color:#22a85a}.addr-btn-default:hover{background:#d0f0d0}
.addr-btn-delete{background:#fff5f5;color:#e53935}.addr-btn-delete:hover{background:#ffe0e0}
.form-card{background:#fff;border:1px solid #e8e8e8;border-radius:10px;padding:24px 28px}
.form-card h3{font-family:'Montserrat',sans-serif;font-size:16px;font-weight:800;color:#1e3a6e;margin:0 0 20px;padding-bottom:12px;border-bottom:1px solid #f0f0f0}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
@media(max-width:600px){.form-row{grid-template-columns:1fr}}
.form-group label{display:block;font-size:12px;font-weight:700;color:#555;margin-bottom:5px;text-transform:uppercase}
.form-group input,.form-group select{width:100%;padding:10px 13px;border:1.5px solid #ddd;border-radius:7px;font-size:14px;outline:none;box-sizing:border-box}
.form-group input:focus,.form-group select:focus{border-color:#1e3a6e}
.btn-save{padding:12px 28px;background:#1e3a6e;color:#fff;border:none;border-radius:7px;font-family:'Montserrat',sans-serif;font-size:14px;font-weight:700;cursor:pointer}
</style>
@endsection

@section('content')
<div class="account-wrap">
    <div class="account-breadcrumb"><a href="{{ route('home') }}">Home</a><span>›</span><a href="{{ route('user.dashboard') }}">My Account</a><span>›</span>Addresses</div>

    <h1 style="font-family:'Montserrat',sans-serif;font-size:26px;font-weight:900;color:#1e3a6e;margin-bottom:24px">My Addresses</h1>

    <div class="account-nav">
        <a href="{{ route('user.dashboard') }}">My Orders</a>
        <a href="{{ route('user.profile') }}">My Profile</a>
        <a href="{{ route('user.addresses') }}" class="active">Addresses</a>
        <a href="{{ route('user.wishlist') }}">Wishlist</a>
    </div>

    @if(session('success'))<div style="background:#f0faf0;border:1px solid #c6e8c6;border-radius:8px;padding:12px 18px;color:#2a7a2a;font-size:14px;font-weight:600;margin-bottom:20px">✅ {{ session('success') }}</div>@endif

    {{-- Existing addresses --}}
    @if(count($addresses) > 0)
    <div class="addr-grid">
        @foreach($addresses as $addr)
        <div class="addr-card {{ $addr->is_default ? 'default' : '' }}">
            <div class="addr-label">
                {{ $addr->label }}
                @if($addr->is_default)<span class="addr-default-badge">DEFAULT</span>@endif
            </div>
            <div class="addr-text">
                <strong>{{ $addr->first_name }} {{ $addr->last_name }}</strong><br>
                @if($addr->company){{ $addr->company }}<br>@endif
                {{ $addr->address_line1 }}<br>
                @if($addr->address_line2){{ $addr->address_line2 }}<br>@endif
                {{ $addr->city }}, {{ $addr->postcode }}<br>
                @if($addr->phone)📞 {{ $addr->phone }}@endif
            </div>
            <div class="addr-actions">
                @if(!$addr->is_default)
                <form method="POST" action="{{ route('user.addresses.default', $addr->id) }}" style="display:inline">@csrf<button type="submit" class="addr-btn addr-btn-default">Set Default</button></form>
                @endif
                <form method="POST" action="{{ route('user.addresses.destroy', $addr->id) }}" onsubmit="return confirm('Delete this address?')" style="display:inline">@csrf @method('DELETE')<button type="submit" class="addr-btn addr-btn-delete">Delete</button></form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Add new --}}
    <div class="form-card">
        <h3>Add New Address</h3>
        <form method="POST" action="{{ route('user.addresses.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group"><label>Label</label><select name="label"><option>Home</option><option>Office</option><option>Warehouse</option><option>Other</option></select></div>
                <div class="form-group"><label>Default?</label><select name="is_default"><option value="0">No</option><option value="1">Yes — use for checkout</option></select></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>First Name *</label><input type="text" name="first_name" required></div>
                <div class="form-group"><label>Last Name *</label><input type="text" name="last_name" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Company</label><input type="text" name="company"></div>
                <div class="form-group"><label>Phone</label><input type="text" name="phone"></div>
            </div>
            <div class="form-row" style="grid-template-columns:1fr">
                <div class="form-group"><label>Address Line 1 *</label><input type="text" name="address_line1" required></div>
            </div>
            <div class="form-row" style="grid-template-columns:1fr">
                <div class="form-group"><label>Address Line 2</label><input type="text" name="address_line2"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>City *</label><input type="text" name="city" required></div>
                <div class="form-group"><label>Postcode *</label><input type="text" name="postcode" required></div>
            </div>
            <button type="submit" class="btn-save">Save Address</button>
        </form>
    </div>
</div>
@endsection
