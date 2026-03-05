{{-- resources/views/pages/all-products.blade.php --}}
@extends('layouts.app')

@section('title', 'All Products – London InstantPrint')
@section('meta_description', 'Browse all our professional printing products. Leaflets, Business Cards, Banners, Brochures & more. Free next day delivery.')

@section('styles')
<style>
/* ===========================
   ALL PRODUCTS PAGE
   =========================== */

/* Page Hero / Title Bar */
.ap-hero {
    background: #fff;
    padding: 48px 40px 20px;
    text-align: center;
}
.ap-hero h1 {
    font-family: var(--font);
    font-size: clamp(26px, 4vw, 42px);
    font-weight: 900;
    color: var(--navy);
    margin-bottom: 8px;
    line-height: 1.1;
}
.ap-hero p {
    font-family: var(--body);
    font-size: 14px;
    color: #666;
}

/* ===========================
   SEARCH BAR
   =========================== */
.ap-search-wrap {
    max-width: 560px;
    margin: 22px auto 0;
    padding: 0 24px;
}
.ap-search-form {
    display: flex;
    align-items: center;
    background: #f5f5f5;
    border: 2px solid transparent;
    border-radius: 6px;
    padding: 0 16px;
    gap: 10px;
    transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
}
.ap-search-form:focus-within {
    background: #fff;
    border-color: var(--navy);
    box-shadow: 0 4px 18px rgba(30,58,110,0.10);
}
.ap-search-form svg {
    flex-shrink: 0;
    color: #aaa;
    transition: color 0.25s;
}
.ap-search-form:focus-within svg { color: var(--navy); }
.ap-search-form input {
    flex: 1;
    border: none;
    background: transparent;
    outline: none;
    font-family: var(--font);
    font-size: 14px;
    font-weight: 500;
    color: #222;
    padding: 13px 0;
}
.ap-search-form input::placeholder { color: #aaa; }
.ap-search-btn {
    background: var(--navy);
    color: #fff;
    border: none;
    padding: 8px 18px;
    border-radius: 4px;
    font-family: var(--font);
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    letter-spacing: 0.3px;
    white-space: nowrap;
    transition: background 0.2s, transform 0.2s;
    flex-shrink: 0;
}
.ap-search-btn:hover { background: var(--red); transform: translateY(-1px); }

/* Clear search badge */
.ap-search-active {
    margin: 10px 0 0;
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
}
.ap-search-query {
    font-family: var(--font);
    font-size: 12px;
    color: #555;
}
.ap-search-query strong { color: var(--navy); }
.ap-clear-btn {
    font-family: var(--font);
    font-size: 11px;
    font-weight: 700;
    color: var(--red);
    text-decoration: none;
    border: 1.5px solid var(--red);
    padding: 2px 10px;
    border-radius: 20px;
    transition: background 0.2s, color 0.2s;
    line-height: 1.6;
}
.ap-clear-btn:hover { background: var(--red); color: #fff; }

/* Filter/Category Bar */
.ap-filter-bar {
    max-width: 1200px;
    margin: 24px auto 0;
    padding: 0 24px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.ap-filter-btn {
    font-family: var(--font);
    font-size: 12px;
    font-weight: 700;
    padding: 8px 18px;
    border-radius: 3px;
    border: 1.5px solid #ddd;
    background: #fff;
    color: #444;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s, color 0.2s, border-color 0.2s;
    white-space: nowrap;
}
.ap-filter-btn:hover,
.ap-filter-btn.active {
    background: var(--navy);
    color: #fff;
    border-color: var(--navy);
}
.ap-filter-count {
    margin-left: auto;
    font-family: var(--body);
    font-size: 12px;
    color: #999;
}

/* Products Section */
.ap-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 32px 24px 60px;
}

/* Product Grid — 4 columns */
.ap-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

/* Product Card */
.ap-card {
    display: block;
    text-decoration: none;
    color: inherit;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 8px;
    overflow: hidden;
}
.ap-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 40px rgba(0,0,0,0.12);
}

/* ===========================
   THUMBNAIL — HOVER IMAGE SWAP
   =========================== */
.ap-thumb {
    width: 100%;
    aspect-ratio: 1 / 1;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Primary image — visible by default */
.ap-thumb .img-primary {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: opacity 0.4s ease, transform 0.45s ease;
    opacity: 1;
    transform: scale(1);
    z-index: 2;
}

/* Hover image — hidden by default */
.ap-thumb .img-hover {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: opacity 0.4s ease, transform 0.45s ease;
    opacity: 0;
    transform: scale(1.1);
    z-index: 3;
}

/* On card hover: swap */
.ap-card:hover .img-primary {
    opacity: 0;
    transform: scale(1.07);
}
.ap-card:hover .img-hover {
    opacity: 1;
    transform: scale(1);
}

/* "Quick Order" overlay button — appears on hover */
.ap-thumb-overlay {
    position: absolute;
    inset: 0;
    z-index: 4;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 18px;
    opacity: 0;
    transition: opacity 0.3s ease;
    background: linear-gradient(to top, rgba(0,0,0,0.30) 0%, transparent 55%);
}
.ap-card:hover .ap-thumb-overlay { opacity: 1; }

.ap-overlay-btn {
    background: var(--yellow);
    color: #222;
    font-family: var(--font);
    font-size: 11px;
    font-weight: 800;
    padding: 8px 24px;
    border-radius: 3px;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    transform: translateY(10px);
    transition: transform 0.35s ease, background 0.2s;
    pointer-events: none;
    white-space: nowrap;
}
.ap-card:hover .ap-overlay-btn { transform: translateY(0); }

/* Fallback placeholder (behind images) */
.ap-thumb-fallback {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
}

/* Thumbnail bg colours */
.pt-1 { background: linear-gradient(135deg, #a8edea 0%, #7dd6cc 100%); }
.pt-2 { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); }
.pt-3 { background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%); }
.pt-4 { background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); }
.pt-5 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.pt-6 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.pt-7 { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.pt-8 { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }

/* Card info below image */
.ap-card-info {
    padding: 12px 4px 4px;
}
.ap-card-info h3 {
    font-family: var(--font);
    font-size: 14px;
    font-weight: 700;
    color: #222;
    margin-bottom: 6px;
    line-height: 1.3;
}
.ap-order-link {
    font-family: var(--font);
    font-size: 12px;
    font-weight: 600;
    color: var(--red);
    display: inline-flex;
    align-items: center;
    gap: 3px;
    transition: gap 0.25s;
}
.ap-card:hover .ap-order-link { gap: 7px; }

/* Empty State */
.ap-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
    color: #999;
}
.ap-empty svg { margin-bottom: 16px; opacity: 0.3; }
.ap-empty p { font-family: var(--font); font-size: 15px; font-weight: 600; }
.ap-empty a {
    display: inline-block;
    margin-top: 16px;
    font-family: var(--font);
    font-size: 13px;
    font-weight: 700;
    color: var(--navy);
    border: 2px solid var(--navy);
    padding: 8px 22px;
    border-radius: 4px;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
}
.ap-empty a:hover { background: var(--navy); color: #fff; }

/* Pagination */
.ap-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    margin-top: 48px;
}

/* ===========================
   TRENDING SECTION (reused)
   =========================== */
.ap-trending {
    background: #CFF4FF;
    padding: 60px 40px 70px;
    text-align: center;
}
.ap-trending h2 {
    font-family: var(--font);
    font-size: 32px;
    font-weight: 900;
    color: var(--navy);
    margin-bottom: 6px;
}
.ap-trending .sub {
    font-family: var(--body);
    font-size: 13px;
    color: #555;
    margin-bottom: 36px;
}

/* ===========================
   RESPONSIVE
   =========================== */
@media (max-width: 1024px) {
    .ap-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 768px) {
    .ap-hero { padding: 32px 20px 16px; }
    .ap-filter-bar { padding: 0 16px; }
    .ap-section { padding: 24px 16px 48px; }
    .ap-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
    .ap-trending { padding: 40px 20px 50px; }
    .ap-search-wrap { padding: 0 16px; }
    .ap-search-btn { padding: 8px 12px; font-size: 11px; }
}
@media (max-width: 480px) {
    .ap-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .ap-card-info h3 { font-size: 12px; }
}
</style>
@endsection

@section('content')

{{-- ===== PAGE TITLE + SEARCH ===== --}}
<div class="ap-hero" data-aos="fade-up" data-aos-duration="600">
    <h1>Find The Perfect Print For Your Needs</h1>
    <p>High-quality printing for trade professionals. Free next day delivery on all orders.</p>

    {{-- SEARCH BAR --}}
    <div class="ap-search-wrap" data-aos="fade-up" data-aos-delay="100" data-aos-duration="600">
        <form class="ap-search-form" action="{{ route('products') }}" method="GET">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <circle cx="11" cy="11" r="7"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Search products… e.g. Business Cards, Banners"
                autocomplete="off"
            >
            <button type="submit" class="ap-search-btn">Search</button>
        </form>

        @if(request('q'))
        <div class="ap-search-active">
            <span class="ap-search-query">Results for: <strong>"{{ request('q') }}"</strong></span>
            <a href="{{ route('products', array_filter(['category' => request('category')])) }}"
               class="ap-clear-btn">✕ Clear</a>
        </div>
        @endif
    </div>
</div>

{{-- ===== FILTER BAR ===== --}}
<div class="ap-filter-bar" data-aos="fade-up" data-aos-delay="80" data-aos-duration="500">
    <a href="{{ route('products', array_filter(['q' => request('q')])) }}"
       class="ap-filter-btn {{ !request('category') ? 'active' : '' }}">All Products</a>

    @foreach($categories as $cat)
        <a href="{{ route('products', array_filter(['category' => $cat, 'q' => request('q')])) }}"
           class="ap-filter-btn {{ request('category') === $cat ? 'active' : '' }}">
            {{ $cat }}
        </a>
    @endforeach

    <span class="ap-filter-count">
        @if(method_exists($products, 'total'))
            {{ $products->total() }} Products
        @else
            {{ count($products) }} Products
        @endif
    </span>
</div>

{{-- ===== PRODUCTS GRID ===== --}}
<section class="ap-section">
    <div class="ap-grid">

        @forelse($products as $index => $product)
            @php
                $colors      = ['pt-1','pt-2','pt-3','pt-4','pt-5','pt-6','pt-7','pt-8'];
                $isModel     = $product instanceof \App\Models\Product;

                $productName = $isModel ? $product->name : ($product['name'] ?? 'Product');
                $productUrl  = $isModel ? route('product.show', $product->slug) : ($product['url'] ?? '#');
                $thumbClass  = $isModel
                    ? $colors[$index % count($colors)]
                    : ($product['thumb_class'] ?? $colors[$index % count($colors)]);

                // image1 = thumbnail (primary)
                $imageUrl = $isModel
                    ? ($product->image1 ? asset($product->image1) : null)
                    : asset($product['image'] ?? '');

                // image2 = hover image (agar upload ki ho), warna image1 hi zoom hogi
                $hoverUrl = $isModel
                    ? ($product->image2 ? asset($product->image2) : $imageUrl)
                    : $imageUrl;
            @endphp

            <a href="{{ $productUrl }}"
               class="ap-card"
               data-aos="fade-up"
               data-aos-delay="{{ ($index % 4) * 80 }}"
               data-aos-duration="600">

                {{-- Thumbnail --}}
                <div class="ap-thumb {{ $thumbClass }}">

                    {{-- Gradient fallback (always behind) --}}
                    <div class="ap-thumb-fallback">
                        <svg width="52" height="52" viewBox="0 0 24 24" fill="none"
                             stroke="rgba(255,255,255,0.45)" stroke-width="1.4">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>

                    @if($imageUrl)
                        {{-- Primary image --}}
                        <img class="img-primary"
                             src="{{ $imageUrl }}"
                             alt="{{ $productName }}"
                             loading="lazy"
                             onerror="this.style.display='none';">

                        {{-- Hover image (same ya alag) --}}
                        <img class="img-hover"
                             src="{{ $hoverUrl }}"
                             alt="{{ $productName }}"
                             loading="lazy"
                             onerror="this.style.display='none';">
                    @endif

                    {{-- Quick Order overlay --}}
                    <div class="ap-thumb-overlay">
                        <span class="ap-overlay-btn">Quick Order</span>
                    </div>
                </div>

                {{-- Card Info --}}
                <div class="ap-card-info">
                    <h3>{{ $productName }}</h3>
                    @if(!empty($product->base_price) && $product->base_price > 0)
                    <span style="font-size:12px;color:#888;display:block;margin:2px 0">From £{{ number_format($product->base_price,2) }}</span>
                    @endif
                    <span class="ap-order-link">Order Now ›</span>
                </div>

            </a>

        @empty
            <div class="ap-empty">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
                <p>
                    @if(request('q'))
                        "<strong>{{ request('q') }}</strong>" ke liye koi product nahi mila.
                    @else
                        Abhi koi product available nahi.
                    @endif
                </p>
                @if(request('q'))
                    <a href="{{ route('products') }}">← Sab Products Dekho</a>
                @endif
            </div>
        @endforelse

    </div>

    {{-- Pagination --}}
    @if(method_exists($products, 'links'))
        <div class="ap-pagination">
            {{ $products->links() }}
        </div>
    @endif

</section>

{{-- ===== TRENDING BLOGS ===== --}}
<section class="ap-trending">
    <h2 data-aos="fade-up" data-aos-duration="600">Trending This Week</h2>
    <p class="sub" data-aos="fade-up" data-aos-delay="80" data-aos-duration="600">
        Pick up tips and get inspired by our latest blogs.
    </p>
    <div class="blogs-grid">
        @foreach($blogs as $index => $blog)
            <a href="{{ $blog['url'] }}"
               class="blog-card"
               data-aos="fade-up"
               data-aos-delay="{{ $index * 80 }}"
               data-aos-duration="600">

                <div class="blog-thumb {{ $blog['thumb_class'] }}">
                    <img
                        src="{{ asset($blog['image']) }}"
                        alt="{{ $blog['title'] }}"
                        class="blog-thumb-img"
                        loading="lazy"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <div class="thumb-fallback" style="display:none;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                             stroke="rgba(0,0,0,0.25)" stroke-width="1.5">
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof AOS !== 'undefined') {
        AOS.init({ once: true, offset: 60 });
    }
});
</script>
@endsection