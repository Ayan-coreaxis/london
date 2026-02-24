{{-- resources/views/pages/home.blade.php --}}
@extends('layouts.app')

@section('title', 'London InstantPrint – Bound to Impress')
@section('meta_description', 'Professional printing services UK. Leaflets, Business Cards, Banners & Brochures. Free next day delivery. Trade customers only.')

@section('content')

{{-- ===== HERO ===== --}}
{{-- ===== HERO =====
     home.blade.php mein purana hero section replace karo is se
--}}
<section class="hero" data-aos="fade-up" data-aos-duration="750">
    <div class="hero-left">
        <h1>Bound to<br>Impress</h1>
        <p>From everyday booklets to high-end brochures we have got you covered</p>
        <a href="{{ route('products') }}" class="btn-shop-now">Shop Now</a>
    </div>

    <div class="hero-right">

        {{-- Navy scalloped SVG — right edge ke bumps navy se pink mein jaate hain --}}
        <svg class="hero-wave-svg"
             viewBox="0 0 60 400"
             preserveAspectRatio="none"
             xmlns="http://www.w3.org/2000/svg">
            <path d="
                M0,0 L40,0
                C40,0  65,25  40,50
                C15,75  65,100 40,125
                C15,150 65,175 40,200
                C15,225 65,250 40,275
                C15,300 65,325 40,350
                C15,375 40,400 40,400
                L0,400 Z
            " fill="#1e3a6e"/>
        </svg>

        <div class="hero-right-content">
            <img
                src="{{ asset('images/hero-product.png') }}"
                alt="Featured Products"
                class="hero-product-img"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
            >
            <div class="hero-brochures">
                <div class="brochure"></div>
                <div class="brochure"></div>
                <div class="brochure"></div>
                <div class="brochure"></div>
                <div class="brochure"></div>
            </div>
        </div>

    </div>
</section>

{{-- ===== TRUST STRIP ===== --}}
<div class="trust-strip">
    <div class="trust-item" data-aos="fade-up" data-aos-delay="0" data-aos-duration="500">
        <div class="trust-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
                <path d="M16 11l2 2 4-4" stroke-width="2"/>
            </svg>
        </div>
        <div class="trust-label">Dedicated Account<br>Managers</div>
    </div>

    <div class="trust-item" data-aos="fade-up" data-aos-delay="80" data-aos-duration="500">
        <div class="trust-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect x="1" y="3" width="15" height="13" rx="2"/>
                <path d="M16 8h4l3 3v5h-7V8z"/>
                <circle cx="5.5" cy="18.5" r="2.5"/>
                <circle cx="18.5" cy="18.5" r="2.5"/>
            </svg>
        </div>
        <div class="trust-label">Free Next Day<br>Delivery</div>
    </div>

    <div class="trust-item" data-aos="fade-up" data-aos-delay="160" data-aos-duration="500">
        <div class="trust-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <polyline points="9 11 12 14 22 4"/>
                <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
            </svg>
        </div>
        <div class="trust-label">Free 30 Point<br>Artwork Check</div>
    </div>

    <div class="trust-item" data-aos="fade-up" data-aos-delay="240" data-aos-duration="500">
        <div class="trust-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                <path d="M16 3.13a4 4 0 010 7.75"/>
            </svg>
        </div>
        <div class="trust-label">Trade<br>Customers Only</div>
    </div>

    {{-- Trustpilot --}}
    <div class="tp-box" data-aos="fade-up" data-aos-delay="320" data-aos-duration="500">
        <div class="tp-row1">
            <span class="tp-logo">Trustpilot</span>
            <div class="tp-stars">
                @for($i = 0; $i < 4; $i++)
                    <div class="tp-star">
                        <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                @endfor
                <div class="tp-star half">
                    <svg viewBox="0 0 24 24" fill="#aaa"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
            </div>
        </div>
        <div class="tp-excellent">Excellent</div>
        <div class="tp-count">Based on 10,814 reviews</div>
    </div>
</div>

{{-- ===== FEATURED PRODUCTS ===== --}}
<section class="featured-section">
    <h2 data-aos="fade-up" data-aos-duration="600">Featured Products</h2>
    <div class="products-grid">
        @foreach($products as $index => $product)
            <a href="{{ $product['url'] }}" class="product-card" data-aos="fade-up" data-aos-delay="{{ $index * 80 }}" data-aos-duration="600">
                <div class="product-thumb {{ $product['thumb_class'] }}">
                    <img
                        src="{{ asset($product['image']) }}"
                        alt="{{ $product['name'] }}"
                        class="product-thumb-img"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <div class="product-thumb-fallback" style="display:none;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.6)" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                </div>
                <div class="product-info">
                    <h3>{{ $product['name'] }}</h3>
                    <span class="order-now-link">Order now ›</span>
                </div>
            </a>
        @endforeach
    </div>
    <a href="{{ route('products') }}" class="btn-view-all" data-aos="fade-up" data-aos-delay="100">View All Products</a>
</section>

{{-- ===== SAMPLE PACK ===== --}}
<div class="main">
    <section class="sample-section">
        <div class="sample-bg-posters">
            <div class="poster-panel">
                {{-- <div class="poster-permit">OAC #121 &nbsp; PERMIT #12934106-01-SG &nbsp; OAC #121</div>
                <div class="poster-text yellow" style="font-size:clamp(22px,4vw,52px);">tipsy<br>tiger</div> --}}
            </div>
        </div>
        <div class="sample-overlay" data-aos="zoom-in" data-aos-duration="700">
            <span class="top-line">Get in touch and order</span>
            <span class="free-line"><em>Free Sample</em></span>
            <span class="pack-line">Pack Today</span>
            <a href="#" class="btn-order-now">Order Now</a>
        </div>
    </section>
</div>

{{-- ===== DELIVERY ===== --}}
<section class="delivery-section">
    <div class="delivery-title" data-aos="fade-right" data-aos-duration="700">
        <h2>
            <span class="word-delivery">Delivery</span>
            <span class="word-rest">Options to suit<br>all your needs</span>
        </h2>
    </div>
    <div class="delivery-cards" data-aos="fade-left" data-aos-duration="700">
        <a href="#" class="delivery-card dc-standard">
            <div class="dc-icon"><i class="fa-solid fa-truck-fast" style="color:white;"></i></div>
            <div class="dc-title">Standard</div>
            <div class="dc-link">Find Out more ›</div>
        </a>
        <a href="#" class="delivery-card dc-nextday">
            <div class="dc-icon"><i class="fa-regular fa-calendar-days" style="color:white;"></i></div>
            <div class="dc-title" style="color:white;">Next Day</div>
            <div class="dc-link" style="color:white;">Find Out more ›</div>
        </a>
    </div>
</section>

{{-- ===== TRENDING BLOGS ===== --}}
<section class="trending-section">
    <h2 data-aos="fade-up" data-aos-duration="600">Trending this week</h2>
    <p class="sub" data-aos="fade-up" data-aos-delay="80" data-aos-duration="600">Pick up tips and get inspired by our latest blogs.</p>
    <div class="blogs-grid">
        @foreach($blogs as $index => $blog)
            <a href="{{ $blog['url'] }}" class="blog-card" data-aos="fade-up" data-aos-delay="{{ $index * 80 }}" data-aos-duration="600">
                <div class="blog-thumb {{ $blog['thumb_class'] }}">
                    <img
                        src="{{ asset($blog['image']) }}"
                        alt="{{ $blog['title'] }}"
                        class="blog-thumb-img"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <div class="thumb-fallback" style="display:none;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(0,0,0,0.25)" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                </div>
                <div class="blog-body">
                    <h3>{{ $blog['title'] }}</h3>
                    <span class="explore-link">Explore more ›</span>
                </div>
            </a>
        @endforeach
    </div>
</section>

<section class="bottom"></section>

@endsection