{{-- Redirects to checkout-login which has both forms --}}
@extends('layouts.app')
@section('title', 'Register to Continue – London InstantPrint')
@section('content')
<script>window.location.href = "{{ route('checkout.login') }}";</script>
<div style="text-align:center;padding:60px;">
    <p>Redirecting to checkout... <a href="{{ route('checkout.login') }}">Click here</a> if not redirected.</p>
</div>
@endsection
