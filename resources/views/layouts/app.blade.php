<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'London InstantPrint – Professional printing services UK. Leaflets, Business Cards, Banners & Brochures. Free next day delivery.')">
    <title>@yield('title', 'London InstantPrint – Professional Printing Services')</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,900&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- AOS Library --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

    {{-- Main CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    {{-- Font awesome--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


    {{-- Page specific styles --}}
    @yield('styles')
</head>
<body>

    {{-- Promo Bar --}}
    @include('components.promo-bar')

    {{-- Header --}}
    @include('components.header')

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    {{-- AOS JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    {{-- Main JS --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- Page specific scripts --}}
    @yield('scripts')

</body>
</html>
