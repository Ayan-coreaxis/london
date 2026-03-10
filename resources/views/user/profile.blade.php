@extends('layouts.app')

@section('title', 'My Profile – London InstantPrint')

@section('styles')
<style>
.account-wrap { max-width: 900px; margin: 0 auto; padding: 36px 24px 80px; font-family: 'Open Sans', sans-serif; }
.account-breadcrumb { font-size: 13px; color: #888; margin-bottom: 28px; }
.account-breadcrumb a { color: #888; text-decoration: none; }
.account-breadcrumb a:hover { color: #1e3a6e; }
.account-breadcrumb span { margin: 0 6px; }
.account-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; flex-wrap: wrap; gap: 16px; }
.account-header h1 { font-family: 'Montserrat', sans-serif; font-size: 26px; font-weight: 900; color: #1e3a6e; margin: 0 0 4px; }
.account-header p { font-size: 14px; color: #888; margin: 0; }

.account-nav { display: flex; gap: 8px; margin-bottom: 28px; flex-wrap: wrap; }
.account-nav a {
    padding: 9px 18px; border: 1.5px solid #e0e0e0; border-radius: 7px;
    font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700;
    color: #555; text-decoration: none; transition: all 0.2s;
}
.account-nav a:hover, .account-nav a.active { background: #1e3a6e; color: #fff; border-color: #1e3a6e; }

.profile-card {
    background: #fff; border: 1px solid #e8e8e8; border-radius: 12px;
    padding: 28px 32px; margin-bottom: 24px;
}
.profile-card h3 {
    font-family: 'Montserrat', sans-serif; font-size: 16px; font-weight: 800;
    color: #1e3a6e; margin: 0 0 24px; padding-bottom: 14px;
    border-bottom: 1px solid #f0f0f0;
}
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
.form-row.single { grid-template-columns: 1fr; }
@media(max-width:600px){ .form-row { grid-template-columns: 1fr; } }
.form-group label {
    display: block; font-size: 12px; font-weight: 700; color: #555;
    margin-bottom: 6px; font-family: 'Montserrat', sans-serif; text-transform: uppercase;
}
.form-group input {
    width: 100%; padding: 11px 14px; border: 1.5px solid #ddd; border-radius: 7px;
    font-size: 14px; color: #333; outline: none; box-sizing: border-box; transition: border-color 0.2s;
}
.form-group input:focus { border-color: #1e3a6e; }
.btn-save {
    padding: 12px 28px; background: #1e3a6e; color: #fff; border: none;
    border-radius: 7px; font-family: 'Montserrat', sans-serif; font-size: 14px;
    font-weight: 700; cursor: pointer; transition: background 0.2s;
}
.btn-save:hover { background: #15305e; }
.alert-success {
    background: #f0faf0; border: 1px solid #99dd99; border-radius: 8px;
    padding: 12px 16px; font-size: 14px; color: #2a7a2a; margin-bottom: 20px;
}
.alert-error {
    background: #fff3f2; border: 1px solid #fbc9c6; border-radius: 8px;
    padding: 12px 16px; font-size: 14px; color: #c0392b; margin-bottom: 20px;
}
.field-error { font-size: 12px; color: #e8352a; margin-top: 4px; }
</style>
@endsection

@section('content')
<div class="account-wrap">
    <div class="account-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>›</span>
        <a href="{{ route('user.dashboard') }}">My Account</a>
        <span>›</span> My Profile
    </div>

    <div class="account-header">
        <div>
            <h1>My Profile</h1>
            <p>Update your personal details and password</p>
        </div>
    </div>

    <div class="account-nav">
        <a href="{{ route('user.dashboard') }}">My Orders</a>
        <a href="{{ route('user.profile') }}" class="active">My Profile</a>
        <a href="{{ route('user.addresses') }}">Addresses</a>
        <a href="{{ route('user.wishlist') }}">Wishlist</a>
    </div>

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    {{-- Personal Details --}}
    <div class="profile-card">
        <h3>Personal Details</h3>
        @if($errors->has('first_name') || $errors->has('last_name') || $errors->has('email'))
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('user.profile.update') }}">
            @csrf
            @method('PUT')
            @php
                $nameParts = explode(' ', $user->name, 2);
                $firstName = $nameParts[0] ?? '';
                $lastName  = $nameParts[1] ?? '';
            @endphp
            <div class="form-row">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $firstName) }}" required>
                    @error('first_name')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $lastName) }}" required>
                    @error('last_name')<p class="field-error">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="+44 7700 000000">
                </div>
            </div>
            <div class="form-row single">
                <div class="form-group">
                    <label>Company Name (Optional)</label>
                    <input type="text" name="company" value="{{ old('company', $user->company ?? '') }}" placeholder="Your company">
                </div>
            </div>
            <button type="submit" class="btn-save">Save Changes</button>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="profile-card">
        <h3>Change Password</h3>
        @error('current_password')
            <div class="alert-error">{{ $message }}</div>
        @enderror
        <form method="POST" action="{{ route('user.password.update') }}">
            @csrf
            @method('PUT')
            <div class="form-row single">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required placeholder="Enter current password">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password" required placeholder="Min 8 characters">
                    @error('password')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="password_confirmation" required placeholder="Repeat new password">
                </div>
            </div>
            <button type="submit" class="btn-save">Change Password</button>
        </form>
    </div>

    {{-- Profile Picture --}}
    <div class="profile-card">
        <h3>Profile Picture</h3>
        <div style="display:flex;align-items:center;gap:20px;margin-bottom:16px">
            <div style="width:72px;height:72px;border-radius:50%;overflow:hidden;background:#eef3ff;display:flex;align-items:center;justify-content:center;border:2px solid #e0e0e0;flex-shrink:0">
                @if($user->avatar ?? false)
                    <img src="{{ asset($user->avatar) }}" style="width:100%;height:100%;object-fit:cover">
                @else
                    <span style="font-size:28px;color:#1e3a6e;font-weight:800;font-family:'Montserrat',sans-serif">{{ strtoupper(substr($user->name,0,1)) }}</span>
                @endif
            </div>
            <form method="POST" action="{{ route('user.profile.avatar') }}" enctype="multipart/form-data" style="display:flex;gap:10px;align-items:end">
                @csrf
                <input type="file" name="avatar" accept="image/*" required style="font-size:13px">
                <button type="submit" class="btn-save" style="padding:9px 18px;font-size:13px">Upload</button>
            </form>
        </div>
    </div>

    {{-- Notification Preferences --}}
    <div class="profile-card">
        <h3>Notification Preferences</h3>
        <form method="POST" action="{{ route('user.profile.notifications') }}">
            @csrf
            @method('PUT')
            <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:16px">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:14px;color:#444">
                    <input type="checkbox" name="email_notifications" value="1" {{ ($user->email_notifications ?? true) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:#1e3a6e">
                    Email notifications (order updates, promotions)
                </label>
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:14px;color:#444">
                    <input type="checkbox" name="sms_notifications" value="1" {{ ($user->sms_notifications ?? false) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:#1e3a6e">
                    SMS notifications (delivery updates)
                </label>
            </div>
            <button type="submit" class="btn-save">Save Preferences</button>
        </form>
    </div>
</div>
@endsection
