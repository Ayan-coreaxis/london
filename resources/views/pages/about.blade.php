{{-- resources/views/pages/about.blade.php --}}
@extends('layouts.app')

@section('title', 'About Us – London InstantPrint')
@section('meta_description', 'Learn about London InstantPrint — the UK\'s trusted trade print partner. Quality printing, free next-day delivery, 15+ years experience.')

@section('styles')
<style>
/* ── HERO ── */
.about-hero {
    background: var(--navy);
    color: #fff;
    padding: 72px 40px 60px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.about-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 70% 50%, rgba(245,197,24,.12) 0%, transparent 70%);
    pointer-events: none;
}
.about-hero h1 {
    font-family: var(--font);
    font-size: clamp(32px, 5vw, 58px);
    font-weight: 900;
    line-height: 1.05;
    margin-bottom: 18px;
    position: relative;
}
.about-hero h1 span { color: var(--yellow); }
.about-hero p {
    font-size: clamp(14px, 1.8vw, 17px);
    color: rgba(255,255,255,.80);
    max-width: 620px;
    margin: 0 auto;
    line-height: 1.7;
    position: relative;
}

/* ── STATS STRIP ── */
.about-stats {
    background: var(--yellow);
    padding: 36px 40px;
}
.stats-grid {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    text-align: center;
}
.stat-item .stat-number {
    font-family: var(--font);
    font-size: clamp(28px, 3.5vw, 44px);
    font-weight: 900;
    color: var(--navy);
    line-height: 1;
    display: block;
}
.stat-item .stat-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--navy);
    opacity: .75;
    margin-top: 6px;
    display: block;
    text-transform: uppercase;
    letter-spacing: .4px;
}

/* ── STORY / MISSION ── */
.about-content {
    max-width: 1100px;
    margin: 0 auto;
    padding: 64px 40px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: start;
}
.about-section-title {
    font-family: var(--font);
    font-size: clamp(22px, 2.8vw, 32px);
    font-weight: 900;
    color: var(--navy);
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 14px;
}
.about-section-title::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 44px; height: 4px;
    background: var(--yellow);
    border-radius: 2px;
}
.about-body {
    font-size: 15px;
    color: #444;
    line-height: 1.8;
    white-space: pre-line;
}
.about-mission-box {
    background: #f8f8f8;
    border-left: 5px solid var(--navy);
    border-radius: 0 10px 10px 0;
    padding: 28px 28px;
}
.about-mission-box .about-section-title::after { background: var(--red); }

/* ── VALUES ── */
.about-values {
    background: #f4f7fb;
    padding: 64px 40px;
    text-align: center;
}
.values-header {
    font-family: var(--font);
    font-size: clamp(22px, 2.8vw, 34px);
    font-weight: 900;
    color: var(--navy);
    margin-bottom: 40px;
}
.values-grid {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
.value-card {
    background: #fff;
    border-radius: 14px;
    padding: 32px 24px;
    box-shadow: 0 2px 16px rgba(30,58,110,.07);
    transition: transform .25s, box-shadow .25s;
}
.value-card:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(30,58,110,.12); }
.value-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
    font-size: 22px;
}
.value-card h3 {
    font-family: var(--font);
    font-size: 17px;
    font-weight: 800;
    color: var(--navy);
    margin-bottom: 10px;
}
.value-card p { font-size: 14px; color: #666; line-height: 1.7; }

/* ── CTA ── */
.about-cta {
    background: var(--navy);
    color: #fff;
    text-align: center;
    padding: 64px 40px;
}
.about-cta h2 {
    font-family: var(--font);
    font-size: clamp(24px, 3.5vw, 40px);
    font-weight: 900;
    margin-bottom: 16px;
}
.about-cta p { font-size: 16px; color: rgba(255,255,255,.8); max-width: 520px; margin: 0 auto 28px; line-height: 1.7; }
.about-cta .btn-cta {
    display: inline-block;
    background: var(--yellow);
    color: var(--navy);
    font-family: var(--font);
    font-weight: 800;
    font-size: 15px;
    padding: 14px 36px;
    border-radius: 8px;
    text-decoration: none;
    transition: opacity .2s, transform .2s;
}
.about-cta .btn-cta:hover { opacity: .9; transform: translateY(-2px); }

/* ── BREADCRUMB ── */
.page-breadcrumb {
    background: #f8f8f8;
    border-bottom: 1px solid #eee;
    padding: 10px 40px;
    font-size: 13px;
    color: #999;
}
.page-breadcrumb a { color: var(--navy); text-decoration: none; }
.page-breadcrumb a:hover { text-decoration: underline; }
.page-breadcrumb span { margin: 0 6px; }

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .about-content { grid-template-columns: 1fr; padding: 40px 20px; gap: 32px; }
    .values-grid { grid-template-columns: 1fr; }
    .about-hero { padding: 48px 20px 40px; }
    .about-stats { padding: 28px 20px; }
    .about-values { padding: 40px 20px; }
    .about-cta { padding: 48px 20px; }
}
</style>
@endsection

@section('content')

@php
    try { $s = \App\Models\SiteSetting::allKeyed(); } catch(\Exception $e) { $s = []; }
@endphp

{{-- BREADCRUMB --}}
<div class="page-breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <span>›</span>
    <strong style="color:#333">About Us</strong>
</div>

{{-- HERO --}}
<section class="about-hero" data-aos="fade-up" data-aos-duration="700">
    <h1>{{ $s['about_hero_title'] ?? 'About' }} <span>London InstantPrint</span></h1>
    <p>{{ $s['about_hero_subtitle'] ?? 'Your trusted trade print partner since 2010. Quality printing with free next-day delivery across the UK.' }}</p>
</section>

{{-- STATS --}}
<section class="about-stats">
    <div class="stats-grid">
        <div class="stat-item" data-aos="fade-up" data-aos-delay="0">
            <span class="stat-number">{{ $s['about_stat1_number'] ?? '50,000+' }}</span>
            <span class="stat-label">{{ $s['about_stat1_label'] ?? 'Orders Delivered' }}</span>
        </div>
        <div class="stat-item" data-aos="fade-up" data-aos-delay="80">
            <span class="stat-number">{{ $s['about_stat2_number'] ?? '10,000+' }}</span>
            <span class="stat-label">{{ $s['about_stat2_label'] ?? 'Happy Customers' }}</span>
        </div>
        <div class="stat-item" data-aos="fade-up" data-aos-delay="160">
            <span class="stat-number">{{ $s['about_stat3_number'] ?? '15+' }}</span>
            <span class="stat-label">{{ $s['about_stat3_label'] ?? 'Years in Business' }}</span>
        </div>
        <div class="stat-item" data-aos="fade-up" data-aos-delay="240">
            <span class="stat-number">{{ $s['about_stat4_number'] ?? '4.8★' }}</span>
            <span class="stat-label">{{ $s['about_stat4_label'] ?? 'Trustpilot Rating' }}</span>
        </div>
    </div>
</section>

{{-- STORY + MISSION --}}
<div class="about-content">
    {{-- Story --}}
    <div data-aos="fade-right" data-aos-duration="700">
        <h2 class="about-section-title">{{ $s['about_story_title'] ?? 'Our Story' }}</h2>
        <div class="about-body">{{ $s['about_story_body'] ?? "London InstantPrint was founded with one simple goal: give trade professionals fast, affordable, high-quality print.\n\nOver the years we've grown from a small workshop to one of the UK's leading trade print suppliers — but our values haven't changed." }}</div>
    </div>

    {{-- Mission --}}
    <div data-aos="fade-left" data-aos-duration="700">
        <div class="about-mission-box">
            <h2 class="about-section-title">{{ $s['about_mission_title'] ?? 'Our Mission' }}</h2>
            <div class="about-body">{{ $s['about_mission_body'] ?? 'To be the most reliable trade print partner in the UK — combining cutting-edge print technology with friendly, expert service and unbeatable delivery speeds.' }}</div>
        </div>

        {{-- Why Choose Us --}}
        <div style="margin-top: 28px;">
            <h3 style="font-family:var(--font);font-weight:800;color:var(--navy);font-size:18px;margin-bottom:16px;">Why Trade Customers Choose Us</h3>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:12px;">
                @foreach([
                    ['icon'=>'fa-truck-fast','text'=>'Free next-day delivery on every order'],
                    ['icon'=>'fa-magnifying-glass','text'=>'Free 30-point artwork check on all files'],
                    ['icon'=>'fa-shield-halved','text'=>'Consistent quality you can resell confidently'],
                    ['icon'=>'fa-headset','text'=>'Dedicated account managers for trade customers'],
                    ['icon'=>'fa-boxes-stacking','text'=>'Huge range — from leaflets to large-format banners'],
                ] as $item)
                <li style="display:flex;align-items:flex-start;gap:12px;font-size:14px;color:#444;">
                    <span style="width:28px;height:28px;background:var(--navy);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
                        <i class="fas {{ $item['icon'] }}" style="color:#fff;font-size:12px;"></i>
                    </span>
                    {{ $item['text'] }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- VALUES --}}
<section class="about-values" data-aos="fade-up" data-aos-duration="700">
    <h2 class="values-header">{{ $s['about_values_title'] ?? 'What We Stand For' }}</h2>
    <div class="values-grid">
        @foreach([
            ['icon'=>'fa-star','bg'=>'#fff3cd','color'=>'#b8860b','title'=>'Quality First','desc'=>'Every print is produced on top-tier equipment with rigorous quality control. We check your artwork so you don\'t have to.'],
            ['icon'=>'fa-bolt','bg'=>'#d4edda','color'=>'#155724','title'=>'Speed & Reliability','desc'=>'Free next-day delivery as standard. We know deadlines are real — so we treat every order like it\'s urgent.'],
            ['icon'=>'fa-handshake','bg'=>'#cce5ff','color'=>'#004085','title'=>'Trade Partnership','desc'=>'We\'re not just a supplier — we\'re your print department. White-label options and dedicated account management available.'],
            ['icon'=>'fa-leaf','bg'=>'#d4edda','color'=>'#155724','title'=>'Sustainability','desc'=>'We use FSC-certified paper stocks and are continuously reducing our carbon footprint through responsible sourcing.'],
            ['icon'=>'fa-lock','bg'=>'#f8d7da','color'=>'#721c24','title'=>'Transparency','desc'=>'No hidden fees, no nasty surprises. What you see in our pricing calculator is what you pay.'],
            ['icon'=>'fa-headset','bg'=>'#e2d9f3','color'=>'#4a235a','title'=>'Expert Support','desc'=>'Our print specialists are on hand to advise on paper stocks, finishes, and anything else you need.'],
        ] as $v)
        <div class="value-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
            <div class="value-icon" style="background:{{ $v['bg'] }};color:{{ $v['color'] }};">
                <i class="fas {{ $v['icon'] }}"></i>
            </div>
            <h3>{{ $v['title'] }}</h3>
            <p>{{ $v['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="about-cta" data-aos="fade-up" data-aos-duration="700">
    <h2>{{ $s['about_cta_title'] ?? 'Ready to place your first order?' }}</h2>
    <p>{{ $s['about_cta_body'] ?? 'Browse our full range of trade print products and enjoy free next-day delivery on every order.' }}</p>
    <a href="{{ route('products') }}" class="btn-cta">Browse All Products</a>
</section>

@endsection
