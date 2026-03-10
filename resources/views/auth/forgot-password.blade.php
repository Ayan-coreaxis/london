@extends('layouts.app')

@section('title', 'Forgot Password – London InstantPrint')
@section('meta_description', 'Reset your London InstantPrint account password.')

@section('styles')
<style>
.auth-hero {
    position: relative;
    min-height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-image: url('{{ asset('images/auth-bg.png') }}');
    background-size: cover;
    background-position: center;
    padding: 60px 24px;
}
.auth-card {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 460px;
    padding: 48px 44px 40px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.15);
    position: relative;
    z-index: 2;
}
.auth-overlay {
    position: absolute; inset: 0;
    background: rgba(10,20,50,0.55);
    z-index: 1;
}
.auth-logo {
    display: block;
    text-align: center;
    margin-bottom: 24px;
}
.auth-logo img { height: 38px; }
.auth-card h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 22px; font-weight: 900;
    color: #1e3a6e; margin: 0 0 8px;
    text-align: center;
}
.auth-sub {
    font-size: 13px; color: #888;
    text-align: center; margin-bottom: 28px;
    line-height: 1.5;
}
.auth-field { margin-bottom: 16px; }
.auth-field label {
    display: block; font-size: 12px; font-weight: 700;
    color: #555; margin-bottom: 6px;
    font-family: 'Montserrat', sans-serif; text-transform: uppercase;
}
.auth-field input {
    width: 100%; padding: 12px 14px;
    border: 1.5px solid #ddd; border-radius: 7px;
    font-size: 14px; color: #333; outline: none;
    box-sizing: border-box; transition: border-color 0.2s;
}
.auth-field input:focus { border-color: #1e3a6e; }
.btn-auth {
    width: 100%; padding: 14px;
    background: #e8352a; color: #fff; border: none;
    border-radius: 7px; font-family: 'Montserrat', sans-serif;
    font-size: 14px; font-weight: 700; cursor: pointer;
    transition: background 0.2s; margin-top: 4px;
}
.btn-auth:hover { background: #c42d24; }
.auth-back { text-align: center; margin-top: 20px; font-size: 13px; color: #888; }
.auth-back a { color: #1e3a6e; font-weight: 700; text-decoration: none; }
.alert-success {
    background: #f0faf0; border: 1px solid #99dd99;
    border-radius: 7px; padding: 12px 16px;
    font-size: 13px; color: #2a7a2a; margin-bottom: 20px;
}
.alert-error {
    background: #fff3f2; border: 1px solid #fbc9c6;
    border-radius: 7px; padding: 12px 16px;
    font-size: 13px; color: #c0392b; margin-bottom: 20px;
}
</style>
@endsection

@section('content')
<section class="auth-hero">
    <div class="auth-overlay"></div>
    <div class="auth-card">
        <a href="{{ route('home') }}" class="auth-logo">
            <img src="{{ asset('images/logo.png') }}" alt="London InstantPrint" onerror="this.style.display='none'">
        </a>
        <h2>Reset Password</h2>
        <p class="auth-sub">Enter your email address and we'll send you a link to reset your password.</p>

        @if(session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="auth-field">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
            </div>
            <button type="submit" class="btn-auth">Send Reset Link</button>
        </form>

        <div class="auth-back">
            Remembered it? <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </div>
</section>
@endsection
