@extends('layouts.app')

@section('title', 'Sign In to Continue – London InstantPrint')
@section('meta_description', 'Sign in or create an account to complete your order.')

@section('styles')
<style>
.auth-hero {
    position: relative;
    min-height: 620px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-image: url('{{ asset('images/auth-bg.png') }}');
    background-size: cover;
    background-position: center;
    padding: 60px 24px;
}
.checkout-auth-wrap {
    width: 100%;
    max-width: 880px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    position: relative;
    z-index: 2;
}
.auth-card {
    background: #fff;
    border-radius: 12px;
    padding: 40px 36px 36px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.15);
}
.auth-card-badge {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    font-family: 'Montserrat', sans-serif;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 14px;
}
.badge-existing { background: #e8f4ff; color: #0066cc; }
.badge-new      { background: #fff8e6; color: #c87600; }

.auth-card h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 22px;
    font-weight: 900;
    color: #111;
    margin: 0 0 6px;
}
.auth-card .auth-sub {
    font-size: 13px;
    color: #888;
    margin-bottom: 24px;
}
.auth-field { margin-bottom: 12px; }
.auth-field input,
.auth-field-row input {
    width: 100%;
    height: 46px;
    border: 1.5px solid #d8dce2;
    border-radius: 6px;
    padding: 0 16px;
    font-family: 'Open Sans', sans-serif;
    font-size: 14px;
    color: #222;
    background: #fff;
    outline: none;
    box-sizing: border-box;
    transition: border-color 0.2s;
}
.auth-field input:focus,
.auth-field-row input:focus { border-color: #1e3a6e; }
.auth-field input::placeholder,
.auth-field-row input::placeholder { color: #bbb; }
.auth-field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 12px;
}
.auth-forgot {
    display: block;
    font-size: 12px;
    color: #e8352a;
    text-decoration: none;
    margin-bottom: 18px;
}
.auth-forgot:hover { text-decoration: underline; }
.auth-submit-btn {
    width: 100%;
    height: 48px;
    background: #f5c800;
    color: #111;
    border: none;
    border-radius: 8px;
    font-family: 'Montserrat', sans-serif;
    font-size: 15px;
    font-weight: 900;
    cursor: pointer;
    transition: background 0.2s, transform 0.1s;
    margin-bottom: 12px;
}
.auth-submit-btn:hover { background: #e0b215; transform: translateY(-1px); }
.auth-submit-btn.secondary {
    background: #1e3a6e;
    color: #fff;
}
.auth-submit-btn.secondary:hover { background: #162d56; }
.auth-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 16px 0;
    font-size: 12px;
    color: #bbb;
}
.auth-divider::before,
.auth-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #eee;
}
.auth-secure {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 11px;
    color: #aaa;
    margin-top: 8px;
}
.auth-errors {
    background: #fff3f2;
    border: 1px solid #fbc9c6;
    border-left: 4px solid #e8352a;
    border-radius: 6px;
    padding: 10px 14px;
    margin-bottom: 16px;
}
.auth-errors ul { list-style: none; padding: 0; margin: 0; }
.auth-errors ul li { font-size: 12px; color: #c0392b; padding: 2px 0; }
.auth-errors ul li::before { content: '• '; }

/* Steps indicator at top */
.checkout-steps-bar {
    width: 100%;
    max-width: 880px;
    display: flex;
    align-items: center;
    background: rgba(255,255,255,0.15);
    border-radius: 10px;
    padding: 14px 24px;
    margin-bottom: 20px;
    position: relative;
    z-index: 2;
}
.cstep {
    display: flex; align-items: center; gap: 8px;
    font-size: 12px; font-weight: 700; color: rgba(255,255,255,0.5);
    font-family: 'Montserrat', sans-serif; flex: 1;
}
.cstep.done   { color: #a8e6a8; }
.cstep.active { color: #fff; }
.cstep-num {
    width: 26px; height: 26px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 900; background: transparent; flex-shrink: 0;
}
.cstep.done   .cstep-num { border-color: #a8e6a8; background: #a8e6a8; color: #1e6e1e; }
.cstep.active .cstep-num { border-color: #fff; background: #fff; color: #1e3a6e; }
.cstep-connector { flex: 1; height: 1px; background: rgba(255,255,255,0.2); margin: 0 8px; }

@media (max-width: 700px) {
    .checkout-auth-wrap { grid-template-columns: 1fr; }
    .auth-card { padding: 30px 22px; }
}
</style>
@endsection

@section('content')
<div class="auth-hero">

    {{-- Steps bar --}}
    <div class="checkout-steps-bar">
        <div class="cstep done">
            <div class="cstep-num">✓</div>
            <span>Basket</span>
        </div>
        <div class="cstep-connector"></div>
        <div class="cstep active">
            <div class="cstep-num">2</div>
            <span>Login / Register</span>
        </div>
        <div class="cstep-connector"></div>
        <div class="cstep">
            <div class="cstep-num">3</div>
            <span>Checkout</span>
        </div>
        <div class="cstep-connector"></div>
        <div class="cstep">
            <div class="cstep-num">4</div>
            <span>Confirmation</span>
        </div>
    </div>

    <div class="checkout-auth-wrap">

        {{-- LEFT: Existing user login --}}
        <div class="auth-card">
            <span class="auth-card-badge badge-existing">Existing Customer</span>
            <h2>Sign In</h2>
            <p class="auth-sub">Already have an account? Log in to continue to checkout.</p>

            @if ($errors->any() && old('_form') === 'login')
                <div class="auth-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if ($errors->any() && old('_form') === 'login')
                <div class="auth-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <input type="hidden" name="redirect" value="checkout">
                <input type="hidden" name="_form" value="login">

                <div class="auth-field">
                    <input type="email" name="email" placeholder="Email address"
                        value="{{ old('email') }}" required autofocus autocomplete="email">
                </div>
                <div class="auth-field">
                    <input type="password" name="password" placeholder="Password"
                        required autocomplete="current-password">
                </div>
                <a href="{{ route('password.request') }}" class="auth-forgot">Forgotten your password?</a>
                <button type="submit" class="auth-submit-btn">Sign In & Continue →</button>
            </form>

            <div class="auth-secure">
                <i class="fa fa-lock"></i>
                Secure SSL — your details are protected
            </div>
        </div>

        {{-- RIGHT: New user register --}}
        <div class="auth-card">
            <span class="auth-card-badge badge-new">New Customer</span>
            <h2>Create Account</h2>
            <p class="auth-sub">First time? Register in seconds and proceed to checkout.</p>

            @if ($errors->any() && old('_form') === 'register')
                <div class="auth-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <input type="hidden" name="redirect" value="checkout">
                <input type="hidden" name="_form" value="register">

                <div class="auth-field-row">
                    <input type="text" name="first_name" placeholder="First Name"
                        value="{{ old('first_name') }}" required>
                    <input type="text" name="last_name" placeholder="Last Name"
                        value="{{ old('last_name') }}" required>
                </div>
                <div class="auth-field">
                    <input type="email" name="email" placeholder="Email address"
                        value="{{ old('email') }}" required autocomplete="email">
                </div>
                <div class="auth-field">
                    <input type="tel" name="phone" placeholder="Phone Number"
                        value="{{ old('phone') }}">
                </div>
                <div class="auth-field">
                    <input type="text" name="company_name" placeholder="Company Name (optional)"
                        value="{{ old('company_name') }}">
                </div>
                <div class="auth-field">
                    <input type="password" name="password" placeholder="Password (min 8 characters)"
                        required autocomplete="new-password">
                </div>
                <div class="auth-field">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password"
                        required autocomplete="new-password">
                </div>
                <button type="submit" class="auth-submit-btn secondary">Create Account & Continue →</button>
            </form>

            <div class="auth-secure">
                <i class="fa fa-lock"></i>
                Secure SSL — your details are protected
            </div>
        </div>

    </div>
</div>
@endsection
