{{-- resources/views/components/header.blade.php --}}
<header class="site-header">
    

    {{-- TOP ROW: Logo + Right Icons --}}
    <div class="header-top-row">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="logo-wrap">
            <img src="{{ asset('images/logo.png') }}" alt="London InstantPrint" style="height: 48px; width: auto;">
        </a>

        <div class="header-spacer"></div>

        {{-- Right Icons --}}
        <div class="header-right">

            {{-- Cart --}}
            <a href="#" class="hdr-icon-link" aria-label="Cart">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 01-8 0"/>
                </svg>
            </a>

            {{-- Help --}}
            <a href="#" class="hdr-icon-link" aria-label="Help">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/>
                    <circle cx="12" cy="17" r="0.5" fill="currentColor"/>
                </svg>
            </a>

            {{-- Sign In --}}
            <a href="#" class="hdr-signin">
                Sign in
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </a>

            {{-- Divider --}}
            <div class="hdr-divider"></div>

            {{-- UK Flag --}}
            <div class="hdr-uk">
                <img src="https://flagcdn.com/w20/gb.png" alt="UK" width="20" height="14" style="border-radius:2px; display:block;">
                <span>UK</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </div>

        </div>
    </div>

    {{-- BOTTOM ROW: Nav + Search + Blog --}}
    <div class="header-nav-row">

        <nav class="main-nav">
            <a href="{{ route('products') }}"
               class="nav-link @if(request()->routeIs('products') || request()->routeIs('home')) active @endif">
                All Products
            </a>
            <a href="{{ route('banners') }}"
               class="nav-link @if(request()->routeIs('banners')) active @endif">
                Banners
            </a>
            <a href="{{ route('business-cards') }}"
               class="nav-link @if(request()->routeIs('business-cards')) active @endif">
                Business Cards
            </a>
            <a href="{{ route('brochures') }}"
               class="nav-link @if(request()->routeIs('brochures')) active @endif">
                Brochures &amp; Booklets
            </a>
        </nav>

        {{-- Search --}}
        <form class="nav-search" action="{{ route('search') }}" method="GET">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2">
                <circle cx="11" cy="11" r="7"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" name="q" placeholder="Search" value="{{ request('q') }}" autocomplete="off">
        </form>

        {{-- Blog --}}
        <a href="{{ route('blog') }}" class="nav-blog @if(request()->routeIs('blog')) active @endif">
            Blog
        </a>

    </div>

</header>