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
        <h1>{{ $settings['hero_title_line1'] ?? 'Bound to' }}<br>{{ $settings['hero_title_line2'] ?? 'Impress' }}</h1>
        <p>{{ $settings['hero_subtitle'] ?? 'From everyday booklets to high-end brochures we have got you covered' }}</p>
        <a href="{{ route('products') }}" class="btn-shop-now">{{ $settings['hero_btn_text'] ?? 'Shop Now' }}</a>
    </div>

    <div class="hero-right">

        {{-- Scalloped wave SVG — left panel color se right panel mein transition --}}
        @php $waveColor = $settings['color_hero_left'] ?? '#81C071'; @endphp
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
            " fill="{{ $waveColor }}"/>
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
            <img src="{{ asset('images/icon2.png') }}" alt="" srcset="" width='50'>
        </div>
        <div class="trust-label">Dedicated Account<br>Managers</div>
    </div>

    <div class="trust-item" data-aos="fade-up" data-aos-delay="80" data-aos-duration="500">
        <div class="trust-icon">
           <img src="{{ asset('images/icon1.png') }}" alt="" srcset="" width='50'>
        </div>
        <div class="trust-label">Free Next Day<br>Delivery</div>
    </div>

    <div class="trust-item" data-aos="fade-up" data-aos-delay="160" data-aos-duration="500">
        <div class="trust-icon">
            <img src="{{ asset('images/icon3.png') }}" alt="" srcset="" width='50'>
        </div>
        <div class="trust-label">Free 30 Point<br>Artwork Check</div>
    </div>

    <div class="trust-item" data-aos="fade-up" data-aos-delay="240" data-aos-duration="500">
        <div class="trust-icon">
             <img src="{{ asset('images/icon4.png') }}" alt="" srcset="" width='50'>
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
        <div class="tp-count">Based on {{ $settings['trustpilot_reviews'] ?? '10,814' }} reviews</div>
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
                    @if(!empty($product['base_price']))
                    <span style="font-size:12px;color:#888;display:block;margin-bottom:3px">From £{{ number_format($product['base_price'],2) }}</span>
                    @endif
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

{{-- ===== FREE ARTWORK CHECK BANNER ===== --}}
<section class="artwork-cta-section" data-aos="fade-up" data-aos-duration="700">
    <div class="artwork-cta-inner">
        <div class="artwork-cta-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <polyline points="9 15 11 17 15 13"/>
            </svg>
        </div>
        <div class="artwork-cta-text">
            <h3>Free 30-Point Artwork Check</h3>
            <p>Every order includes a thorough check of your artwork — resolution, bleed, colours and more. We'll contact you if anything needs fixing before we print.</p>
        </div>
        <a href="{{ route('contact') }}" class="artwork-cta-btn">Learn More</a>
    </div>
</section>

{{-- ===== WHY CHOOSE US ===== --}}
<section class="why-us-section" data-aos="fade-up" data-aos-duration="700">
    <h2>Why London InstantPrint?</h2>
    <div class="why-us-grid">
        <div class="why-us-card" data-aos="fade-up" data-aos-delay="0">
            <div class="why-us-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            <h4>Quality Guaranteed</h4>
            <p>Professional print quality on every order — or we reprint for free.</p>
        </div>
        <div class="why-us-card" data-aos="fade-up" data-aos-delay="80">
            <div class="why-us-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <h4>Fast Turnaround</h4>
            <p>Order by 5pm for next-day dispatch. Same-day options available on selected products.</p>
        </div>
        <div class="why-us-card" data-aos="fade-up" data-aos-delay="160">
            <div class="why-us-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="3" width="15" height="13" rx="1"/>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/>
                    <circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
            </div>
            <h4>Free UK Delivery</h4>
            <p>Free next-day delivery on all orders. No minimum order value required.</p>
        </div>
        <div class="why-us-card" data-aos="fade-up" data-aos-delay="240">
            <div class="why-us-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <h4>Dedicated Support</h4>
            <p>Personal account managers to help you every step of the way.</p>
        </div>
    </div>
</section>

{{-- ===== DELIVERY ===== --}}
<section class="delivery-section">
    <div class="delivery-title" data-aos="fade-right" data-aos-duration="700">
        <h2>
            <span class="word-delivery">Delivery</span>
            <span class="word-rest">Options to suit<br>all your needs</span>
        </h2>
    </div>
    <div class="delivery-cards" data-aos="fade-left" data-aos-duration="700">
        @if(isset($deliveryMethods) && $deliveryMethods->count() > 0)
            @php $dcClasses = ['dc-standard','dc-nextday','dc-sameday','dc-highlands']; $dcIcons = ['fa-truck-fast','fa-calendar-days','fa-bolt','fa-mountain']; @endphp
            @foreach($deliveryMethods->take(4) as $di => $dm)
            @php $isLight = $di % 2 === 0; @endphp
            <a href="{{ route('checkout') }}" class="delivery-card {{ $dcClasses[$di % 4] }}">
                <div class="dc-icon"><i class="fa-solid {{ $dcIcons[$di % 4] }}" style="{{ $isLight ? '' : 'color:white;' }}"></i></div>
                <div class="dc-title" style="{{ $isLight ? '' : 'color:white;' }}">{{ $dm->name }}</div>
                <div class="dc-sub" style="font-size:11px;margin-top:3px;{{ $isLight ? 'color:#555;' : 'color:rgba(255,255,255,0.8);' }}">{{ $dm->price > 0 ? '£'.number_format($dm->price,2) : 'FREE' }}</div>
                <div class="dc-link" style="{{ $isLight ? '' : 'color:white;' }}">Find Out more ›</div>
            </a>
            @endforeach
        @else
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
        @endif
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



@endsection