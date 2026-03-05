{{-- resources/views/components/header.blade.php --}}
<header class="site-header">

    {{-- TOP ROW: Logo + Right Icons --}}
    <div class="header-top-row">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="logo-wrap">
            @php
                try { $__logo = \App\Models\SiteSetting::get('header_logo',''); } catch(\Exception $e) { $__logo=''; }
            @endphp
            @if($__logo)
                <img src="{{ asset($__logo) }}" alt="Logo" style="height:48px;width:auto;" onerror="this.src='{{ asset('images/logo.png') }}'">
            @else
                <img src="{{ asset('images/logo.png') }}" alt="London InstantPrint" style="height:48px;width:auto;">
            @endif
        </a>
        <div class="header-spacer"></div>
        {{-- Right Icons --}}
        <div class="header-right">
            {{-- Cart --}}
            <a href="{{ route('basket') }}" class="hdr-icon-link" aria-label="Cart" style="position:relative;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 01-8 0"/>
                </svg>
                @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
                @if($cartCount > 0)
                <span style="position:absolute;top:-6px;right:-6px;background:#e8352a;color:#fff;border-radius:50%;width:17px;height:17px;font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center;font-family:'Montserrat',sans-serif;">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                @endif
            </a>
            {{-- Help --}}
            <a href="#" class="hdr-icon-link" aria-label="Help">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/>
                    <circle cx="12" cy="17" r="0.5" fill="currentColor"/>
                </svg>
            </a>
            {{-- Sign In / My Account --}}
            @auth
            <a href="{{ route('user.dashboard') }}" class="hdr-signin">
                My Account
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </a>
            @else
            <a href="{{ route('login') }}" class="hdr-signin">
                Sign in
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </a>
            @endauth
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
            {{-- Hamburger (mobile only) --}}
            <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle menu" aria-expanded="false">
                <span class="ham-line"></span>
                <span class="ham-line"></span>
                <span class="ham-line"></span>
            </button>
        </div>
    </div>

    {{-- BOTTOM ROW: Nav + Search + Blog (desktop) --}}
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
            <a href="{{ route('product.show', 'business-cards') }}"
                class="nav-link @if(request()->routeIs('product.show')) active @endif">
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

    {{-- MOBILE MENU DRAWER --}}
    <div class="mobile-menu" id="mobileMenu" aria-hidden="true">
        {{-- Mobile Search --}}
        <form class="mobile-search" action="{{ route('search') }}" method="GET">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2">
                <circle cx="11" cy="11" r="7"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" name="q" placeholder="Search products..." value="{{ request('q') }}" autocomplete="off">
        </form>

        {{-- Mobile Nav Links --}}
        <nav class="mobile-nav">
            <a href="{{ route('products') }}"
               class="mobile-nav-link @if(request()->routeIs('products') || request()->routeIs('home')) active @endif">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="7" height="7"/><rect x="15" y="3" width="7" height="7"/><rect x="15" y="14" width="7" height="7"/><rect x="2" y="14" width="7" height="7"/></svg>
                All Products
            </a>
            <a href="{{ route('banners') }}"
               class="mobile-nav-link @if(request()->routeIs('banners')) active @endif">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="1"/></svg>
                Banners
            </a>
            <a href="{{ route('product.show', 'business-cards') }}"
               class="mobile-nav-link @if(request()->routeIs('product.show')) active @endif">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M6 12h4M6 15h2"/></svg>
                Business Cards
            </a>
            <a href="{{ route('brochures') }}"
               class="mobile-nav-link @if(request()->routeIs('brochures')) active @endif">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="12" y2="17"/></svg>
                Brochures &amp; Booklets
            </a>
            <a href="{{ route('blog') }}"
               class="mobile-nav-link @if(request()->routeIs('blog')) active @endif">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                Blog
            </a>
        </nav>

        {{-- Mobile Footer --}}
        <div class="mobile-menu-footer">
            @auth
            <a href="{{ route('user.dashboard') }}" class="mobile-signin-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                My Account
            </a>
            @else
            <a href="{{ route('login') }}" class="mobile-signin-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Sign In / Register
            </a>
            @endauth
        </div>
    </div>

    {{-- Overlay --}}
    <div class="mobile-overlay" id="mobileOverlay"></div>

</header>

<style>
/* ===========================
   HAMBURGER BUTTON
   =========================== */
.hamburger-btn {
    display: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 5px;
    width: 36px;
    height: 36px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: background 0.2s;
}
.hamburger-btn:hover { background: #f5f5f5; }
.ham-line {
    display: block;
    width: 22px;
    height: 2px;
    background: #222;
    border-radius: 2px;
    transition: transform 0.3s ease, opacity 0.3s ease, width 0.3s ease;
    transform-origin: center;
}

/* Animated X state */
.hamburger-btn.is-open .ham-line:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.hamburger-btn.is-open .ham-line:nth-child(2) { opacity: 0; width: 0; }
.hamburger-btn.is-open .ham-line:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

/* ===========================
   MOBILE MENU DRAWER
   =========================== */
.mobile-menu {
    display: none; /* hidden on desktop */
    position: fixed;
    top: 0;
    right: -100%;
    width: min(320px, 85vw);
    height: 100dvh;
    background: #fff;
    z-index: 1100;
    flex-direction: column;
    box-shadow: -8px 0 40px rgba(0,0,0,0.15);
    transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    overflow-y: auto;
    padding: 80px 0 24px;
}
.mobile-menu.is-open {
    right: 0;
}

/* Overlay */
.mobile-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0);
    z-index: 1050;
    backdrop-filter: blur(0px);
    transition: background 0.35s ease, backdrop-filter 0.35s ease;
    pointer-events: none;
}
.mobile-overlay.is-open {
    background: rgba(0,0,0,0.45);
    backdrop-filter: blur(2px);
    pointer-events: all;
    cursor: pointer;
}

/* Mobile Search */
.mobile-search {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 20px 8px;
    padding: 11px 16px;
    background: #f5f5f5;
    border-radius: 8px;
    border: 1.5px solid transparent;
    transition: border-color 0.2s, background 0.2s;
}
.mobile-search:focus-within {
    background: #fff;
    border-color: #1e3a6e;
}
.mobile-search input {
    border: none;
    outline: none;
    background: transparent;
    font-family: var(--font, 'Montserrat', sans-serif);
    font-size: 14px;
    color: #333;
    width: 100%;
}
.mobile-search input::placeholder { color: #aaa; }

/* Mobile Nav Links */
.mobile-nav {
    display: flex;
    flex-direction: column;
    padding: 8px 0;
}
.mobile-nav-link {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 24px;
    font-family: var(--font, 'Montserrat', sans-serif);
    font-size: 14px;
    font-weight: 600;
    color: #222;
    text-decoration: none;
    border-left: 3px solid transparent;
    transition: background 0.2s, color 0.2s, border-color 0.2s;
    position: relative;
}
.mobile-nav-link svg {
    color: #888;
    flex-shrink: 0;
    transition: color 0.2s;
}
.mobile-nav-link:hover,
.mobile-nav-link.active {
    background: #f8f9ff;
    color: #e8352a;
    border-left-color: #e8352a;
}
.mobile-nav-link:hover svg,
.mobile-nav-link.active svg {
    color: #e8352a;
}

/* Divider between nav items */
.mobile-nav-link + .mobile-nav-link::before {
    content: '';
    position: absolute;
    top: 0; left: 24px; right: 24px;
    height: 1px;
    background: #f0f0f0;
}

/* Mobile Footer */
.mobile-menu-footer {
    margin-top: auto;
    padding: 20px 20px 0;
    border-top: 1px solid #efefef;
}
.mobile-signin-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px;
    background: #1e3a6e;
    color: #fff;
    font-family: var(--font, 'Montserrat', sans-serif);
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    border-radius: 6px;
    transition: background 0.2s, transform 0.2s;
}
.mobile-signin-btn:hover {
    background: #e8352a;
    transform: translateY(-1px);
}

/* ===========================
   MOBILE BREAKPOINT
   =========================== */
@media (max-width: 768px) {
    .hamburger-btn { display: flex; }
    .mobile-menu { display: flex; }
    .mobile-overlay { display: block; }

    /* Hide desktop sign-in & UK selector on mobile */
    .hdr-signin,
    .hdr-divider,
    .hdr-uk { display: none; }
}
</style>

<script>
(function () {
    const btn       = document.getElementById('hamburgerBtn');
    const menu      = document.getElementById('mobileMenu');
    const overlay   = document.getElementById('mobileOverlay');

    function openMenu() {
        btn.classList.add('is-open');
        menu.classList.add('is-open');
        overlay.classList.add('is-open');
        btn.setAttribute('aria-expanded', 'true');
        menu.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        btn.classList.remove('is-open');
        menu.classList.remove('is-open');
        overlay.classList.remove('is-open');
        btn.setAttribute('aria-expanded', 'false');
        menu.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    btn.addEventListener('click', function () {
        btn.classList.contains('is-open') ? closeMenu() : openMenu();
    });

    overlay.addEventListener('click', closeMenu);

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMenu();
    });
})();
</script>