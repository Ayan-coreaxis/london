@extends('layouts.app')

@section('title', 'Register Your Account – London InstantPrint')
@section('meta_description', 'Create a free London InstantPrint account.')

@section('styles')
<style>
.auth-hero {
    position: relative;
    min-height: 640px;
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
    max-width: 500px;
    padding: 48px 44px 40px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.15);
    position: relative;
    z-index: 2;
}

.auth-card h1 {
    font-family: var(--font);
    font-size: 28px;
    font-weight: 900;
    color: #111;
    text-align: center;
    margin-bottom: 28px;
    letter-spacing: -0.3px;
}

/* ── 2 COLUMN ROW ── */
.auth-field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 14px;
}

/* ── SINGLE FIELD ── */
.auth-field {
    margin-bottom: 14px;
}

.auth-field input,
.auth-field select,
.auth-field-row input {
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
    appearance: none;
    -webkit-appearance: none;
}

.auth-field input:focus,
.auth-field select:focus,
.auth-field-row input:focus {
    border-color: #999;
}

.auth-field input::placeholder,
.auth-field-row input::placeholder {
    color: #bbb;
}

/* ── INDUSTRY SELECT ── */
.auth-select-wrap {
    position: relative;
    margin-bottom: 20px;
}

.auth-select-wrap select {
    width: 100%;
    height: 50px;
    border: 1.5px solid #d8dce2;
    border-radius: 6px;
    padding: 0 40px 0 18px;
    font-family: var(--body);
    font-size: 14px;
    color: #bbb;
    background: #fff;
    outline: none;
    cursor: pointer;
    transition: border-color 0.2s;
    appearance: none;
    -webkit-appearance: none;
}

.auth-select-wrap select:focus {
    border-color: #999;
    color: #222;
}

.auth-select-wrap select option {
    color: #222;
}

.auth-select-wrap::after {
    content: '';
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 6px solid #999;
    pointer-events: none;
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

/* ── BOTTOM TEXT ── */
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

/* ── ERRORS ── */
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

@media (max-width: 540px) {
    .auth-card {
        padding: 36px 24px 30px;
    }
    .auth-card h1 {
        font-size: 22px;
    }
    .auth-field-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<div class="auth-hero">
    <div class="auth-card">

        <h1>Register Your Account</h1>

        @if ($errors->any())
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
            @if(request('redirect'))
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
            @endif

            {{-- First Name / Last Name --}}
            <div class="auth-field-row">
                <input
                    type="text"
                    name="first_name"
                    placeholder="Last Name"
                    value="{{ old('first_name') }}"
                    required
                    autofocus
                    autocomplete="given-name"
                >
                <input
                    type="text"
                    name="last_name"
                    placeholder="Last Name"
                    value="{{ old('last_name') }}"
                    required
                    autocomplete="family-name"
                >
            </div>

            {{-- Email --}}
            <div class="auth-field">
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                >
            </div>

            {{-- Password --}}
            <div class="auth-field">
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                    autocomplete="new-password"
                >
            </div>

            {{-- Confirm Password --}}
            <div class="auth-field">
                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirm Password"
                    required
                    autocomplete="new-password"
                >
            </div>

            {{-- Company Name --}}
            <div class="auth-field">
                <input
                    type="text"
                    name="company_name"
                    placeholder="Company Name"
                    value="{{ old('company_name') }}"
                    autocomplete="organization"
                >
            </div>

            {{-- Industry Dropdown --}}
            <div class="auth-select-wrap">
                <select name="industry">
                    <option value="" disabled selected>Industry</option>
                    <option value="advertising" {{ old('industry') == 'advertising' ? 'selected' : '' }}>Advertising & Marketing</option>
                    <option value="architecture" {{ old('industry') == 'architecture' ? 'selected' : '' }}>Architecture & Design</option>
                    <option value="education" {{ old('industry') == 'education' ? 'selected' : '' }}>Education</option>
                    <option value="events" {{ old('industry') == 'events' ? 'selected' : '' }}>Events & Entertainment</option>
                    <option value="finance" {{ old('industry') == 'finance' ? 'selected' : '' }}>Finance & Banking</option>
                    <option value="healthcare" {{ old('industry') == 'healthcare' ? 'selected' : '' }}>Healthcare</option>
                    <option value="hospitality" {{ old('industry') == 'hospitality' ? 'selected' : '' }}>Hospitality & Tourism</option>
                    <option value="legal" {{ old('industry') == 'legal' ? 'selected' : '' }}>Legal</option>
                    <option value="manufacturing" {{ old('industry') == 'manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                    <option value="nonprofit" {{ old('industry') == 'nonprofit' ? 'selected' : '' }}>Non-Profit</option>
                    <option value="retail" {{ old('industry') == 'retail' ? 'selected' : '' }}>Retail</option>
                    <option value="technology" {{ old('industry') == 'technology' ? 'selected' : '' }}>Technology</option>
                    <option value="other" {{ old('industry') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <button type="submit" class="auth-submit-btn">Create Account</button>

        </form>

        <p class="auth-bottom-text">
            Already have an account?
            <a href="{{ route('login') }}{{ request('redirect') ? '?redirect='.request('redirect') : '' }}">Login Here</a>
        </p>

        <div class="auth-secure">
            <i class="fa fa-lock"></i>
            londoninstantprint.co.uk is secure and your personal details are protected
        </div>

    </div>
</div>
@endsection