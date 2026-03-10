@extends('layouts.app')

@section('title', 'Account Login – London InstantPrint')
@section('meta_description', 'Sign in to your London InstantPrint account.')

@section('styles')
<style>
/* ── AUTH HERO SECTION ── */
.auth-hero {
    position: relative;
    min-height: 580px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-image: url('{{ asset('images/auth-bg.png') }}');
    background-size: cover;
    background-position: center;
    padding: 60px 24px;
}

/* ── WHITE MODAL CARD ── */
.auth-card {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 500px;
    padding: 48px 44px 40px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.15);
    position: relative;
    z-index: 2;
}

.auth-card h1 {
    font-family: var(--font);
    font-size: 30px;
    font-weight: 900;
    color: #111;
    text-align: center;
    margin-bottom: 28px;
    letter-spacing: -0.3px;
}

/* ── FORM FIELDS ── */
.auth-field {
    margin-bottom: 14px;
}

.auth-field input {
    width: 100%;
    height: 50px;
    border: 1.5px solid #d8dce2;
    border-radius: 6px;
    padding: 0 18px;
    font-family: var(--body);
    font-size: 14px;
    color: #222;
    background: #fff;
    outline: none;
    transition: border-color 0.2s;
}

.auth-field input:focus {
    border-color: #999;
}

.auth-field input::placeholder {
    color: #bbb;
}

/* ── FORGOT PASSWORD ── */
.auth-forgot {
    display: block;
    font-family: var(--body);
    font-size: 13px;
    color: var(--red);
    text-decoration: none;
    margin-bottom: 20px;
    margin-top: 2px;
}

.auth-forgot:hover {
    text-decoration: underline;
}

/* ── SUBMIT BUTTON ── */
.auth-submit-btn {
    width: 100%;
    height: 35px;
    background: var(--yellow);
    color: #111;
    border: none;
    border-radius: 10px;
    font-family: var(--font);
    font-size: 15px;
    font-weight: 800;
    cursor: pointer;
    letter-spacing: 0.2px;
    transition: background 0.2s, transform 0.1s;
    margin-bottom: 18px;
}

.auth-submit-btn:hover {
    background: #e0b215;
    transform: translateY(-1px);
}

.auth-submit-btn:active {
    transform: translateY(0);
}

/* ── BOTTOM LINKS ── */
.auth-bottom-text {
    text-align: center;
    font-family: var(--body);
    font-size: 14px;
    color: #222;
    margin-bottom: 16px;
}

.auth-bottom-text a {
    color: var(--red);
    font-weight: 600;
    text-decoration: none;
}

.auth-bottom-text a:hover {
    text-decoration: underline;
}

/* ── SECURE NOTE ── */
.auth-secure {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    font-family: var(--body);
    font-size: 12px;
    color: #888;
    text-align: center;
}

.auth-secure i {
    font-size: 13px;
    color: #888;
    flex-shrink: 0;
}

/* ── ERROR MESSAGES ── */
.auth-errors {
    background: #fff3f2;
    border: 1px solid #fbc9c6;
    border-left: 4px solid var(--red);
    border-radius: 6px;
    padding: 12px 16px;
    margin-bottom: 18px;
}

.auth-errors ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.auth-errors ul li {
    font-family: var(--body);
    font-size: 13px;
    color: #c0392b;
    padding: 2px 0;
}

.auth-errors ul li::before {
    content: '• ';
}

.auth-status {
    background: #f0faf4;
    border: 1px solid #a8dfc0;
    border-left: 4px solid #27ae60;
    border-radius: 6px;
    padding: 12px 16px;
    margin-bottom: 18px;
    font-family: var(--body);
    font-size: 13px;
    color: #1e7e44;
}

@media (max-width: 540px) {
    .auth-card {
        padding: 36px 24px 30px;
    }
    .auth-card h1 {
        font-size: 24px;
    }
}
</style>
@endsection

@section('content')
<div class="auth-hero">
    <div class="auth-card">

        <h1>Account Login</h1>

        @if ($errors->any())
            <div class="auth-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="auth-status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            @if(request('redirect'))
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
            @endif

            <div class="auth-field">
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                >
            </div>

            <div class="auth-field">
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                    autocomplete="current-password"
                >
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-forgot">
                    Forgotten your password?
                </a>
            @endif

            <button type="submit" class="auth-submit-btn">Log in</button>

        </form>

        <p class="auth-bottom-text">
            New to London InstantPrint?
            <a href="{{ route('register') }}{{ request('redirect') ? '?redirect='.request('redirect') : '' }}">Register your account here</a>
        </p>

        <div class="auth-secure">
            <i class="fa fa-lock"></i>
            londoninstantprint.co.uk is secure and your personal details are protected
        </div>

    </div>
</div>
@endsection