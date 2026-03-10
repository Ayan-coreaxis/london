<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'London InstantPrint – Professional printing services UK. Leaflets, Business Cards, Banners & Brochures. Free next day delivery.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    {{-- Local Font Faces for dynamic font switching --}}
    <style>
        @font-face { font-family:'ABCGravity'; src:url('{{ asset('fonts/ABCGravityVariable-Trial.ttf') }}') format('truetype'); font-weight:100 900; font-style:normal; font-display:swap; }
        @font-face { font-family:'ABCGravity'; src:url('{{ asset('fonts/ABCGravityItalicVariable-Trial.ttf') }}') format('truetype'); font-weight:100 900; font-style:italic; font-display:swap; }
        @font-face { font-family:'BrittiSans'; src:url('{{ asset('fonts/BrittiSansTrial-Light-BF6757bfd494951.otf') }}') format('opentype'); font-weight:300; font-style:normal; font-display:swap; }
        @font-face { font-family:'BrittiSans'; src:url('{{ asset('fonts/BrittiSansTrial-Regular-BF6757bfd47ffbf.otf') }}') format('opentype'); font-weight:400; font-style:normal; font-display:swap; }
        @font-face { font-family:'BrittiSans'; src:url('{{ asset('fonts/BrittiSansTrial-Semibold-BF6757bfd443a8a.otf') }}') format('opentype'); font-weight:600; font-style:normal; font-display:swap; }
        @font-face { font-family:'BrittiSans'; src:url('{{ asset('fonts/BrittiSansTrial-Bold-BF6757bfd4a96ed.otf') }}') format('opentype'); font-weight:700; font-style:normal; font-display:swap; }
    </style>

    {{-- Dynamic CSS Variables from Site Settings --}}
    @php
        try {
            $ds = \App\Models\SiteSetting::allKeyed();
        } catch(\Exception $e) { $ds = []; }
    @endphp
    <style>
        :root {
            --navy:   {{ $ds['color_primary']       ?? '#1e3a6e' }};
            --red:    {{ $ds['color_accent']         ?? '#e8352a' }};
            --yellow: {{ $ds['color_yellow']         ?? '#f5c518' }};
            --color-bg:          {{ $ds['color_bg']           ?? '#ffffff' }};
            --color-text:        {{ $ds['color_text']         ?? '#222222' }};
            --color-header-bg:   {{ $ds['color_header_bg']   ?? '#ffffff' }};
            --color-footer-bg:   {{ $ds['color_footer_bg']   ?? '#1e3a6e' }};
            --color-promo-bg:    {{ $ds['color_promo_bg']    ?? '#f5c518' }};
            --color-promo-text:  {{ $ds['color_promo_text']  ?? '#1a1a1a' }};
            --color-hero-bg:     {{ $ds['color_hero_bg']     ?? '#f8f4f0' }};
            --color-hero-left:   {{ $ds['color_hero_left']   ?? '#81C071' }};
            --color-hero-right:  {{ $ds['color_hero_right_bg'] ?? '#fd6c99' }};
            --color-btn-bg:      {{ $ds['color_btn_primary_bg']   ?? '#1e3a6e' }};
            --color-btn-text:    {{ $ds['color_btn_primary_text'] ?? '#ffffff' }};
            --color-nav-active:  {{ $ds['color_nav_active']  ?? '#e8352a' }};
            --font:   'ABCGravity', sans-serif;
            --body:   'BrittiSans', sans-serif;
            --font-size-base: {{ ($ds['font_size_base'] ?? '15') }}px;
            --color-pt1: {{ $ds['color_pt1'] ?? 'linear-gradient(135deg,#b2f5b2,#1a8c1a)' }};
            --color-pt2: {{ $ds['color_pt2'] ?? 'linear-gradient(135deg,#ffe082,#e65100)' }};
            --color-pt3: {{ $ds['color_pt3'] ?? 'linear-gradient(135deg,#b3e5fc,#0277bd)' }};
            --color-pt4: {{ $ds['color_pt4'] ?? 'linear-gradient(135deg,#c8e6c9,#2e7d32)' }};
        }
        body { background: var(--color-bg); color: var(--color-text); font-size: var(--font-size-base); font-family: var(--body) !important; }
        h1,h2,h3,h4,h5,h6,
        .hero-left h1, .promo-main-text,
        .nav-link, .hdr-signin, .btn-shop-now,
        .featured-section h2, .trending-section h2,
        .product-info h3, .blog-body h3,
        .delivery-title h2, .sample-overlay .free-line,
        .ap-hero h1, .footer-col h4 {
            font-family: var(--font) !important;
        }
        .site-header { background: var(--color-header-bg) !important; }
        .promo-bar { background: var(--color-promo-bg) !important; }
        .promo-bar-inner, .promo-main-text, .promo-sub-text { color: var(--color-promo-text) !important; }
        .hero { background: var(--color-hero-bg) !important; }
        .hero-left { background: var(--color-hero-left, #81C071) !important; }
        .hero-right { background: var(--color-hero-right, #fd6c99) !important; }
        .hero-wave-svg path { fill: var(--color-hero-left, #81C071) !important; }
        .btn-shop-now { background: var(--color-btn-bg) !important; color: var(--color-btn-text) !important; }
        .nav-link.active, .nav-link:hover { color: var(--color-nav-active) !important; }
        footer { background: var(--color-footer-bg) !important; }
        .pt-1 { background: var(--color-pt1) !important; }
        .pt-2 { background: var(--color-pt2) !important; }
        .pt-3 { background: var(--color-pt3) !important; }
        .pt-4 { background: var(--color-pt4) !important; }
    </style>
    {{-- Google Analytics --}}
    @php try { $__ga = \App\Models\SiteSetting::get('google_analytics',''); } catch(\Exception $e){ $__ga=''; } @endphp
    @if($__ga)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $__ga }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $__ga }}');</script>
    @endif
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

    {{-- Visual Page Editor (admin only) --}}
    @include('components.page-editor')

</body>
</html>
