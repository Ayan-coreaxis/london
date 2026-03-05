@extends('layouts.app')

@section('title', 'Set New Password – London InstantPrint')

@section('styles')
<style>
.auth-hero {
    position: relative; min-height: 560px;
    display: flex; align-items: center; justify-content: center;
    background-image: url('{{ asset('images/auth-bg.png') }}');
    background-size: cover; background-position: center; padding: 60px 24px;
}
.auth-overlay { position: absolute; inset: 0; background: rgba(10,20,50,0.55); z-index: 1; }
.auth-card {
    background: #fff; border-radius: 12px; width: 100%; max-width: 460px;
    padding: 48px 44px 40px; box-shadow: 0 8px 40px rgba(0,0,0,0.15);
    position: relative; z-index: 2;
}
.auth-card h2 { font-family: 'Montserrat', sans-serif; font-size: 22px; font-weight: 900; color: #1e3a6e; margin: 0 0 24px; text-align: center; }
.auth-field { margin-bottom: 16px; }
.auth-field label { display: block; font-size: 12px; font-weight: 700; color: #555; margin-bottom: 6px; font-family: 'Montserrat', sans-serif; text-transform: uppercase; }
.auth-field input { width: 100%; padding: 12px 14px; border: 1.5px solid #ddd; border-radius: 7px; font-size: 14px; color: #333; outline: none; box-sizing: border-box; transition: border-color 0.2s; }
.auth-field input:focus { border-color: #1e3a6e; }
.btn-auth { width: 100%; padding: 14px; background: #e8352a; color: #fff; border: none; border-radius: 7px; font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
.btn-auth:hover { background: #c42d24; }
.alert-error { background: #fff3f2; border: 1px solid #fbc9c6; border-radius: 7px; padding: 12px 16px; font-size: 13px; color: #c0392b; margin-bottom: 20px; }
</style>
@endsection

@section('content')
<section class="auth-hero">
    <div class="auth-overlay"></div>
    <div class="auth-card">
        <h2>Set New Password</h2>

        @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="auth-field">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>
            </div>
            <div class="auth-field">
                <label>New Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="auth-field">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn-auth">Reset Password</button>
        </form>
    </div>
</section>
@endsection
