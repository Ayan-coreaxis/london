{{-- resources/views/pages/product-details.blade.php --}}
@extends('layouts.app')

@section('title', ($product['name'] ?? 'Business Cards') . ' – London InstantPrint')
@section('meta_description', 'Order professional ' . ($product['name'] ?? 'Business Cards') . ' with free next day delivery. Trade customers only.')

@section('styles')
<style>
/* ===== PRODUCT DETAILS PAGE ===== */
.pd-breadcrumb {
    max-width: 1200px;
    margin: 0 auto;
    padding: 12px 24px;
    font-size: 13px;
    color: #888;
    font-family: 'Open Sans', sans-serif;
}
.pd-breadcrumb a { color: #888; text-decoration: none; }
.pd-breadcrumb a:hover { color: #1e3a6e; }
.pd-breadcrumb span { margin: 0 6px; }

.pd-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px 24px 60px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: start;
    font-family: 'Open Sans', sans-serif;
}

/* ---- Gallery ---- */
.pd-gallery {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    background: #e8f5e0;
    aspect-ratio: 4/3;
    margin-bottom: 12px;
}
.pd-gallery img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
    transition: opacity 0.3s;
}
.pd-gallery-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: #e8f0fe;
}
.pd-gallery-thumbs {
    display: flex;
    gap: 8px;
    margin-bottom: 24px;
}
.pd-gallery-thumb {
    width: 64px; height: 48px;
    border-radius: 6px;
    overflow: hidden;
    border: 2px solid transparent;
    cursor: pointer;
    background: #eee;
    flex-shrink: 0;
    transition: border-color 0.2s;
}
.pd-gallery-thumb img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
}
.pd-gallery-thumb.active { border-color: #1e3a6e; }
.pd-gallery-dots {
    display: flex;
    gap: 6px;
    justify-content: center;
    margin-bottom: 24px;
}
.pd-gallery-dots span {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #ccc;
    cursor: pointer;
}
.pd-gallery-dots span.active { background: #1e3a6e; }

/* ---- Accordion ---- */
.pd-accordion { border-top: 1px solid #e0e0e0; }
.pd-acc-item { border-bottom: 1px solid #e0e0e0; }
.pd-acc-trigger {
    width: 100%; background: none; border: none;
    padding: 14px 0;
    display: flex; justify-content: space-between; align-items: center;
    cursor: pointer;
    font-family: 'Open Sans', sans-serif;
    font-size: 14px; font-weight: 600; color: #222;
    text-align: left;
}
.pd-acc-trigger:hover { color: #1e3a6e; }
.pd-acc-trigger .acc-icon { font-size: 18px; color: #888; transition: transform 0.3s; line-height: 1; }
.pd-acc-item.open .acc-icon { transform: rotate(45deg); }
.pd-acc-body { display: none; padding: 0 0 14px; font-size: 13px; color: #555; line-height: 1.6; }
.pd-acc-item.open .pd-acc-body { display: block; }

.btn-quote {
    display: inline-block; margin-top: 20px;
    padding: 11px 24px; background: #f5c800; color: #222;
    font-weight: 700; font-size: 14px; border-radius: 6px;
    text-decoration: none; font-family: 'Open Sans', sans-serif;
    border: none; cursor: pointer; transition: background 0.2s;
}
.btn-quote:hover { background: #e0b400; }

/* ---- RIGHT COLUMN ---- */
.pd-title { font-family: 'Montserrat', sans-serif; font-size: 28px; font-weight: 800; color: #1e3a6e; margin: 0 0 14px; }
.pd-description { font-size: 13.5px; color: #444; line-height: 1.7; margin-bottom: 24px; }

/* Variant Tabs */
.pd-variants { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
.pd-variant-tab {
    border: 2px solid #e0e0e0; border-radius: 8px;
    padding: 8px 12px; cursor: pointer;
    text-align: center; font-size: 12px; font-weight: 600; color: #555;
    transition: border-color 0.2s; min-width: 110px;
    text-decoration: none; display: block;
}
.pd-variant-tab img {
    width: 80px; height: 54px; object-fit: cover;
    border-radius: 4px; display: block;
    margin: 0 auto 6px; background: #eee;
}
.pd-variant-tab.active, .pd-variant-tab:hover { border-color: #1e3a6e; color: #1e3a6e; }

/* Presets */
.pd-section-label { font-size: 13px; font-weight: 700; color: #222; margin-bottom: 10px; display: block; }
.pd-presets { 
    display: flex; 
    gap: 10px; 
    margin-bottom: 20px; 
    align-items: flex-start; 
    flex-wrap: wrap; 
    flex-direction: column;  /* presets alag, options grid mein */
}
.pd-preset-card {
    color: white; border-radius: 8px;
    padding: 10px 14px; font-size: 11px;
    line-height: 1.5; min-width: 100%; font-weight: 600;
}
.pd-preset-card .preset-badge {
    background: white; font-size: 10px; font-weight: 700;
    padding: 2px 6px; border-radius: 3px;
    display: inline-block; margin-bottom: 4px;
}

/* Options */
.pd-options-wrap { 
    flex: 1; 
    display: grid; 
    grid-template-columns: 1fr 1fr; 
    gap: 12px 16px; 
    align-items: start;
}
@media (max-width: 600px) {
    .pd-options-wrap { 
        grid-template-columns: 1fr; 
    }
}
.pd-option-label { font-size: 12px; font-weight: 700; color: #555; margin-bottom: 5px; display: block; }
.pd-select-group { display: flex; gap: 10px; flex: 1; }
.pd-select {
    flex: 1;
    width: 100%;  /* yeh add karo */
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 13px;
    font-family: 'Open Sans', sans-serif;
    color: #333;
    background: white url("data:image/svg+xml,...") no-repeat right 10px center;
    appearance: none;
    cursor: pointer;
}
.pd-options-wrap > div {
    width: 100%;
    min-width: 0;  /* grid overflow fix */
}

.pd-btn-group {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    width: 100%;  /* yeh add karo */
}

.pd-toggle-btn {
    flex: 1;          /* yeh add karo - equal width buttons */
    min-width: 0;     /* yeh bhi */
    padding: 8px 18px;
    border: 2px solid #ccc;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    background: white;
    color: #444;
    font-family: 'Open Sans', sans-serif;
    transition: all 0.2s;
}
.pd-btn-group { display: flex; gap: 8px; flex-wrap: wrap; }
.pd-toggle-btn {
    padding: 8px 18px; border: 2px solid #ccc; border-radius: 6px;
    font-size: 13px; font-weight: 600; cursor: pointer;
    background: white; color: #444;
    font-family: 'Open Sans', sans-serif; transition: all 0.2s;
}
.pd-toggle-btn.active, .pd-toggle-btn:hover { border-color: #1e3a6e; color: #1e3a6e; background: #f0f4ff; }

.pd-divider { border: none; border-top: 1px solid #eee; margin: 18px 0; }

/* Qty */
.pd-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
.pd-row-label { font-size: 13px; font-weight: 700; color: #222; }
.pd-row-price { font-size: 13px; font-weight: 700; color: #222; }
.pd-row-price .vat { font-size: 11px; color: #888; font-weight: 400; }
.pd-qty-input { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; font-family: 'Open Sans', sans-serif; margin-bottom: 4px; }
.pd-qty-hint { font-size: 11px; color: #888; margin-bottom: 16px; }

/* Delivery */
.pd-delivery-label { font-size: 13px; font-weight: 700; color: #222; margin-bottom: 6px; display: flex; justify-content: space-between; align-items: center; }
.pd-delivery-label a { font-size: 12px; color: #1e3a6e; text-decoration: none; font-weight: 400; }
.pd-delivery-label a:hover { text-decoration: underline; }
.pd-delivery-icon { color: #e53935; font-size: 12px; margin-right: 4px; }
.pd-delivery-option, .pd-delivery-day {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 14px; border: 1px solid #ccc;
    border-radius: 6px; margin-bottom: 16px;
    font-size: 13px; color: #333;
}
.pd-delivery-option .price, .pd-delivery-day .price { font-weight: 700; color: #d93025; }
.pd-time-slots { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 16px; }
.pd-time-slot {
    border: 1px solid #ddd; border-radius: 6px;
    padding: 10px 12px; font-size: 12px; color: #333;
    display: flex; justify-content: space-between; align-items: center;
    cursor: pointer; transition: border-color 0.2s;
}
.pd-time-slot:hover { border-color: #1e3a6e; }
.pd-time-slot .slot-price { font-weight: 700; color: #d93025; }
.pd-phone-row { display: flex; gap: 8px; align-items: center; margin-bottom: 20px; }
.pd-phone-flag { display: flex; align-items: center; gap: 4px; padding: 10px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; }
.pd-phone-input { flex: 1; padding: 10px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px; font-family: 'Open Sans', sans-serif; color: #888; }

/* Subtotal */
.pd-subtotal-box { background: #f9f9f9; border-radius: 8px; padding: 16px 18px; margin-bottom: 12px; }
.pd-subtotal-row { display: flex; justify-content: space-between; font-size: 13px; color: #333; margin-bottom: 4px; }
.pd-subtotal-row.total { font-weight: 700; font-size: 15px; color: #222; }
.pd-subtotal-row .vat-note { font-size: 11px; color: #888; font-weight: 400; }
.pd-subtotal-row .per-unit { font-size: 11px; color: #888; }
.pd-cta-group { display: flex; flex-direction: column; gap: 10px; }
.btn-upload { width: 100%; padding: 13px; background: #f5c800; color: #222; font-weight: 700; font-size: 14px; border: none; border-radius: 6px; cursor: pointer; font-family: 'Open Sans', sans-serif; transition: background 0.2s; }
.btn-upload:hover { background: #e0b400; }
.btn-basket { width: 100%; padding: 13px; background: #1e3a6e; color: white; font-weight: 700; font-size: 14px; border: none; border-radius: 6px; cursor: pointer; font-family: 'Open Sans', sans-serif; transition: background 0.2s; }
.btn-basket:hover { background: #162d56; }

/* More Products */
.more-products-section { padding: 60px 24px; max-width: 1200px; margin: 0 auto; }
.more-products-section h2 { font-family: 'Montserrat', sans-serif; font-size: 28px; font-weight: 900; color: #1e3a6e; text-align: center; margin-bottom: 32px; }
.more-products-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
.mp-card { text-decoration: none; color: inherit; display: block; }
.mp-card-thumb { border-radius: 10px; overflow: hidden; aspect-ratio: 1; margin-bottom: 10px; }
.mp-card-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.3s; }
.mp-card:hover .mp-card-thumb img { transform: scale(1.04); }
.mp-card-thumb.bg-teal { background: #00bcd4; }
.mp-card-thumb.bg-yellow { background: #fdd835; }
.mp-card-thumb.bg-navy { background: #1e3a6e; }
.mp-card-thumb.bg-green { background: #66bb6a; }
.mp-card h3 { font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 700; color: #222; margin: 0 0 4px; }
.mp-order-link { font-size: 13px; color: #1e3a6e; font-weight: 600; }

/* FAQ */
.faq-section { padding: 60px 24px; background: #fff; border-top: 1px solid #eee; }
.faq-inner { max-width: 1200px; margin: 0 auto; }
.faq-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; gap: 24px; }
.faq-header-left h2 { font-family: 'Montserrat', sans-serif; font-size: 32px; font-weight: 900; color: #1e3a6e; margin: 0 0 6px; }
.faq-header-left p { font-size: 13px; color: #555; margin: 0; }
.btn-contact { padding: 11px 28px; background: #f5c800; color: #222; font-weight: 700; font-size: 14px; border: none; border-radius: 6px; cursor: pointer; font-family: 'Open Sans', sans-serif; white-space: nowrap; text-decoration: none; transition: background 0.2s; }
.btn-contact:hover { background: #e0b400; }
.faq-body { display: grid; grid-template-columns: 280px 1fr; gap: 32px; }
.faq-list { border-top: 3px solid #1e3a6e; }
.faq-item { border-bottom: 1px solid #ddd; cursor: pointer; }
.faq-question { padding: 14px 0; font-size: 13px; font-weight: 600; color: #333; display: flex; justify-content: space-between; align-items: center; user-select: none; }
.faq-question:hover, .faq-item.active .faq-question { color: #1e3a6e; }
.faq-question .faq-arrow { font-size: 10px; color: #888; flex-shrink: 0; margin-left: 8px; }
.faq-answer-panel { padding: 12px 16px; background: #f4f6fa; border-radius: 6px; font-size: 13px; color: #444; line-height: 1.7; }
.faq-answer-panel h4 { font-size: 14px; font-weight: 700; color: #1e3a6e; margin: 0 0 8px; }

/* Responsive */
@media (max-width: 900px) {
    .pd-wrapper { grid-template-columns: 1fr; gap: 32px; }
    .more-products-grid { grid-template-columns: repeat(2, 1fr); }
    .faq-body { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .pd-title { font-size: 22px; }
    .more-products-grid { grid-template-columns: repeat(2, 1fr); }
    .pd-time-slots { grid-template-columns: 1fr; }
    .faq-header { flex-direction: column; }
}

/* Turnaround Cards */
.pd-turnaround-label { font-size: 13px; font-weight: 700; color: #222; margin-bottom: 8px; }
.pd-turnaround-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 10px; margin-bottom: 16px; }
.pd-ta-card {
    border: 2px solid #ddd; border-radius: 8px; padding: 12px 10px;
    cursor: pointer; transition: all 0.2s; text-align: center;
    background: #fff;
}
.pd-ta-card:hover { border-color: #1e3a6e; background: #f8faff; }
.pd-ta-card.active { border-color: #1e3a6e; background: #f0f4ff; }
.pd-ta-name { font-size: 12px; font-weight: 800; color: #1e3a6e; margin-bottom: 4px; }
.pd-ta-due { font-size: 11px; color: #333; margin-bottom: 3px; }
.pd-ta-deadline { font-size: 10px; color: #888; margin-bottom: 6px; }
.pd-ta-price { font-size: 13px; font-weight: 700; color: #d93025; }

/* Upload Modal */
.upl-overlay { display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); background: rgba(180,180,180,0.18); }
.upl-overlay.active { display: flex; }
.upl-modal { background: #fff; border-radius: 16px; width: 100%; max-width: 500px; margin: 16px; padding: 40px 44px 36px; position: relative; font-family: 'Open Sans', sans-serif; box-shadow: 0 8px 40px rgba(0,0,0,0.13); }
.upl-close { position: absolute; top: 16px; right: 20px; background: none; border: none; font-size: 22px; color: #aaa; cursor: pointer; line-height: 1; font-weight: 300; transition: color 0.2s; }
.upl-close:hover { color: #333; }
.upl-state { text-align: center; }
.upl-cloud-icon { width: 80px; height: 80px; background: #4FC3F7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; }
.upl-main-title { font-family: 'Open Sans', sans-serif; font-size: 20px; font-weight: 700; color: #222; margin: 0 0 10px; }
.upl-formats-text { font-size: 13px; color: #444; margin-bottom: 4px; line-height: 1.5; }
.upl-formats-text strong { color: #222; }
.upl-maxsize { font-size: 12px; color: #888; margin-bottom: 22px; display: block; }
.btn-upload-device { display: inline-block; background: #f5c800; color: #1a1a1a; font-family: 'Open Sans', sans-serif; font-weight: 700; font-size: 14px; padding: 13px 36px; border-radius: 7px; cursor: pointer; margin-bottom: 22px; transition: background 0.2s; border: none; text-decoration: none; }
.btn-upload-device:hover { background: #e0b400; }
.upl-file-list { text-align: left; margin-bottom: 16px; }
.upl-file-item { display: flex; align-items: center; gap: 8px; padding: 6px 0; font-size: 12.5px; color: #333; border-bottom: 1px solid #f0f0f0; }
.upl-file-item:last-child { border-bottom: none; }
.upl-file-dot { width: 8px; height: 8px; border-radius: 50%; background: #4CAF50; flex-shrink: 0; }
.upl-file-name { flex: 1; font-weight: 600; }
.upl-file-label { font-size: 11px; color: #888; background: #f0f0f0; padding: 2px 8px; border-radius: 10px; }
.upl-file-remove { background: none; border: none; color: #bbb; cursor: pointer; font-size: 16px; line-height: 1; padding: 0 2px; }
.upl-file-remove:hover { color: #e53935; }
.upl-template-note { font-size: 11.5px; color: #777; line-height: 1.65; margin-bottom: 16px; text-align: left; }
.upl-template-note a { color: #1e3a6e; text-decoration: underline; }
.upl-check-row { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #333; text-align: left; padding: 10px 14px; background: #f7f7f7; border-radius: 8px; cursor: pointer; }
.upl-checkbox { width: 16px; height: 16px; accent-color: #1e3a6e; flex-shrink: 0; cursor: pointer; }
.upl-check-row label { cursor: pointer; line-height: 1.4; }
.upl-success-circle { width: 80px; height: 80px; background: #e53935; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; }
.upl-uploaded-title { font-family: 'Open Sans', sans-serif; font-size: 20px; font-weight: 700; color: #222; margin: 0 0 20px; }
.btn-add-basket-yellow { display: block; width: 100%; background: #f5c800; color: #1a1a1a; font-family: 'Open Sans', sans-serif; font-weight: 700; font-size: 14px; padding: 13px; border-radius: 7px; cursor: pointer; border: none; margin-bottom: 22px; transition: background 0.2s; }
.btn-add-basket-yellow:hover { background: #e0b400; }
.upl-state.preview-state { text-align: left; }
.upl-preview-heading { font-family: 'Open Sans', sans-serif; font-size: 18px; font-weight: 800; color: #222; margin: 0 0 4px; }
.upl-preview-tagline { font-size: 12.5px; color: #666; margin-bottom: 22px; }
.upl-sides-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 22px; }
.upl-side-label { font-size: 13px; font-weight: 700; color: #222; display: block; margin-bottom: 8px; }
.upl-side-frame { border: 2px solid #1e3a6e; border-radius: 8px; aspect-ratio: 16/10; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f8f8f8; }
.upl-side-frame img { width: 100%; height: 100%; object-fit: contain; }
.upl-side-frame.back-frame { border-color: #ddd; }
.upl-side-frame-back-label { font-size: 11.5px; color: #bbb; text-align: center; padding: 8px; }
.upl-preview-confirm { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
.upl-confirm-check { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #333; flex: 1; min-width: 0; }
.upl-confirm-check label { cursor: pointer; line-height: 1.4; }
.btn-basket-preview { background: #f5c800; color: #1a1a1a; font-family: 'Open Sans', sans-serif; font-weight: 700; font-size: 14px; padding: 12px 28px; border-radius: 7px; cursor: pointer; border: none; white-space: nowrap; transition: background 0.2s; flex-shrink: 0; }
.btn-basket-preview:hover { background: #e0b400; }
@media (max-width: 560px) {
    .upl-modal { padding: 28px 20px 24px; }
    .upl-sides-grid { grid-template-columns: 1fr; }
    .upl-preview-confirm { flex-direction: column; align-items: flex-start; }
    .btn-basket-preview { width: 100%; }
}
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<div class="pd-breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <span>›</span>
    <a href="{{ route('products') }}">Products</a>
    <span>›</span>
    <span>{{ $product['name'] ?? 'Business Cards' }}</span>
</div>

{{-- ===== MAIN PRODUCT DETAIL ===== --}}
<div class="pd-wrapper" data-aos="fade-up" data-aos-duration="600">

    {{-- LEFT: Image Gallery + Accordion --}}
    <div class="pd-left">

        {{-- Main Gallery Image --}}
        <div class="pd-gallery" id="mainGallery">
            @php
                $galleryImages = [];
                for ($gi = 1; $gi <= 4; $gi++) {
                    if (!empty($product["image$gi"])) {
                        $galleryImages[] = asset($product["image$gi"]);
                    }
                }
            @endphp
            @if(count($galleryImages) > 0)
                <img src="{{ $galleryImages[0] }}" alt="{{ $product['name'] }}" id="mainGalleryImg">
            @else
                <div class="pd-gallery-placeholder">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#1e3a6e" stroke-width="1.2">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Thumbnail Strip (if multiple images) --}}
        @if(count($galleryImages) > 1)
        <div class="pd-gallery-thumbs">
            @foreach($galleryImages as $gi => $imgUrl)
            <div class="pd-gallery-thumb {{ $gi === 0 ? 'active' : '' }}" onclick="switchGallery('{{ $imgUrl }}', this)">
                <img src="{{ $imgUrl }}" alt="Image {{ $gi + 1 }}">
            </div>
            @endforeach
        </div>
        @else
        <div class="pd-gallery-dots">
            <span class="active"></span><span></span><span></span><span></span>
        </div>
        @endif

        {{-- Accordion --}}
        <div class="pd-accordion">
            @php
                $accItems = [
                    ['title' => 'Artwork Setup', 'body' => $product['artwork_setup_text'] ?? 'Upload your print-ready artwork in PDF, AI, EPS, or TIFF format. Ensure bleed and crop marks are included. Minimum resolution 300 DPI.'],
                    ['title' => 'Artwork Templates', 'body' => $product['artwork_templates_text'] ?? 'Download our free artwork templates for accurate sizing. Templates are available in Adobe Illustrator, InDesign, and PDF formats.'],
                    ['title' => 'Technical Specification', 'body' => $product['technical_spec_text'] ?? 'Standard size: 85mm × 55mm. Print area: 81mm × 51mm (with 2mm bleed on each side). Colour mode: CMYK. File format: PDF preferred.'],
                    ['title' => 'Key Information', 'body' => $product['key_info_text'] ?? 'All orders are checked by our 30-point artwork review team before going to print. Proofs available on request. Trade customers only.'],
                ];
            @endphp
            @foreach($accItems as $acc)
            <div class="pd-acc-item">
                <button class="pd-acc-trigger" onclick="toggleAcc(this)">
                    {{ $acc['title'] }} <span class="acc-icon">+</span>
                </button>
                <div class="pd-acc-body">{{ $acc['body'] }}</div>
            </div>
            @endforeach
        </div>

        <a href="{{ route('contact') }}?product={{ urlencode($product['name']) }}&quote=1" class="btn-quote">Request a Quote</a>
    </div>

    {{-- RIGHT: Product Config --}}
    <div class="pd-right">

        @if($errors->any())
            <div style="background:#fff3f2;border:1px solid #fbc9c6;border-radius:8px;padding:13px 16px;font-size:14px;color:#c0392b;margin-bottom:20px;">
                ⚠️ <strong>Error:</strong>
                <ul style="margin:5px 0 0 20px;padding:0;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('error'))
            <div style="background:#fff3f2;border:1px solid #fbc9c6;border-radius:8px;padding:13px 16px;font-size:14px;color:#c0392b;margin-bottom:20px;">⚠️ {{ session('error') }}</div>
        @endif

        <h1 class="pd-title">{{ $product['name'] ?? 'Business Cards' }}</h1>

        <p class="pd-description">{{ $product['description'] ?? '' }}</p>

        {{-- Variant Tabs --}}
        @if(count($variants) > 0)
        <div class="pd-variants">
            <span class="pd-variant-tab active">{{ $product['name'] }}</span>
            @foreach($variants as $v)
                <a href="{{ $v->link_slug ? route('product.show', $v->link_slug) : '#' }}" class="pd-variant-tab">{{ $v->name }}</a>
            @endforeach
        </div>
        @endif

        {{-- Presets + Options --}}
        @if(count($presets) > 0 || count($options) > 0)
        <span class="pd-section-label">Select from Our product presets</span>
        <div class="pd-presets">
            @foreach($presets as $preset)
            <div class="pd-preset-card" style="background:{{ $preset->badge_color ?? '#d93025' }}">
                <span class="preset-badge" style="color:{{ $preset->badge_color ?? '#d93025' }}">{{ $preset->label }}</span><br>
                {{ $preset->description }}
            </div>
            @endforeach

            <div class="pd-options-wrap">
                @foreach($options as $opt)
                    @if($opt['display_type'] === 'dropdown')
                    <div>
                        <span class="pd-option-label">{{ $opt['option_name'] }}</span>
                        <select class="pd-select" onchange="updatePrice()">
                            @foreach($opt['values'] as $val)
                            <option data-extra="{{ $val['extra_price'] }}">
                                {{ $val['value_label'] }}{{ $val['extra_price'] > 0 ? ' (+£' . number_format($val['extra_price'],2) . ')' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div>
                        <span class="pd-option-label">{{ $opt['option_name'] }}</span>
                        <div class="pd-btn-group">
                            @foreach($opt['values'] as $vi => $val)
                            <button type="button"
                                class="pd-toggle-btn {{ $vi === 0 ? 'active' : '' }}"
                                data-extra="{{ $val['extra_price'] }}"
                                data-group="opt{{ $opt['id'] }}"
                                onclick="selectToggle(this)">
                                {{ $val['value_label'] }}{{ $val['extra_price'] > 0 ? ' (+£' . number_format($val['extra_price'],2) . ')' : '' }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- ═══ VARIATION-BASED PRICING ═══ --}}
        @if(!empty($hasVariations))
        <div id="variationSection">
            <hr class="pd-divider">
            @foreach($variationData['attributes'] as $attr)
                @if($attr->visible && count($attr->values) > 0)
                <div style="margin-bottom:14px">
                    <span class="pd-option-label">{{ $attr->name }}</span>
                    <select class="pd-select var-selector" data-attr-id="{{ $attr->id }}" onchange="updateVariationPrice()">
                        @foreach($attr->values as $val)
                            <option value="{{ $val->id }}">{{ $val->value }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            @endforeach
            <hr class="pd-divider">
            <div id="varNoMatch" style="display:none;background:#fef3c7;border:1px solid #fbbf24;border-radius:8px;padding:12px 16px;font-size:13px;color:#92400e;margin-bottom:16px">⚠ This combination is not available.</div>
            <div id="varPriceTable" style="margin-bottom:16px"></div>
            <div id="varSummary" style="display:none;background:linear-gradient(135deg,#1e3a6e,#2d4a8e);border-radius:10px;padding:16px 20px;color:#fff;margin-bottom:16px">
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
                    <div>
                        <div style="font-size:12px;opacity:.7;margin-bottom:4px">Your Selection</div>
                        <div style="font-size:14px;font-weight:600" id="varSummaryText"></div>
                        <div style="font-size:11px;opacity:.6;margin-top:4px" id="varSummarySpecs"></div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-size:28px;font-weight:800" id="varSummaryPrice"></div>
                        <div style="font-size:12px;opacity:.7" id="varSummaryVat"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <hr class="pd-divider">

        <div id="oldPricingSection" @if(!empty($hasVariations)) style="display:none" @endif>
        {{-- Quantity --}}
        <div class="pd-row">
            <span class="pd-row-label">Your quantity</span>
            <span class="pd-row-price" id="priceDisplay">
                £{{ number_format($product['base_price'], 2) }}
                <span class="vat">(£{{ number_format($product['base_price'] * 1.2, 2) }} inc. VAT)</span>
            </span>
        </div>
        <input type="number" class="pd-qty-input" id="qtyInput" placeholder="Enter Qty" min="1" oninput="updatePrice()">
        <p class="pd-qty-hint">Min qty: 50</p>

        <hr class="pd-divider">

        {{-- ===== TURNAROUND & DELIVERY ===== --}}
        @if(count($turnarounds) > 0)

        <div class="pd-delivery-label">
            <span>Delivery Type <span class="pd-delivery-icon">●</span> Delivery options for <strong>Mainland UK</strong> <a href="#">(Change)</a></span>
        </div>
        <div class="pd-delivery-option">
            <span>Tracked Delivery</span>
            <span class="price">Included in price</span>
        </div>

        <div class="pd-turnaround-label">Select Turnaround</div>
        <div class="pd-turnaround-grid" id="turnaroundGrid">
            @foreach($turnarounds as $ti => $ta)
            @php
                $minDay = $ta['working_days_min'];
                $maxDay = $ta['working_days_max'];
                $dueMin = \Carbon\Carbon::now()->addWeekdays($minDay);
                $dueMax = \Carbon\Carbon::now()->addWeekdays($maxDay);
                $dueStr = $minDay === $maxDay
                    ? $dueMin->format('D d M')
                    : $dueMin->format('D d M') . ' - ' . $dueMax->format('D d M');
            @endphp
            <div class="pd-ta-card {{ $ti === 0 ? 'active' : '' }}"
                 data-ta-index="{{ $ti }}"
                 onclick="selectTurnaround(this)">
                <div class="pd-ta-name">{{ $ta['label'] }}</div>
                <div class="pd-ta-due">Due: <strong>{{ $dueStr }}</strong></div>
                <div class="pd-ta-deadline">Artwork by {{ $ta['artwork_deadline'] }}</div>
                <div class="pd-ta-price" id="taPrice{{ $ti }}">From £{{ number_format($ta['pricing'][0]['price'] ?? $product['base_price'], 2) }}</div>
            </div>
            @endforeach
        </div>

        @else
        {{-- Fallback static if no turnarounds configured --}}
        {{-- <div class="pd-delivery-label">
            <span>Delivery Type <span class="pd-delivery-icon">●</span> Mainland UK</span>
        </div> --}}
        {{-- <div class="pd-delivery-option">
            <span>Tracked Delivery</span>
            <span class="price">Included in price</span>
        </div> --}}
        @endif

        {{-- Delivery Notification --}}
        {{-- <div class="pd-delivery-label">Delivery Notification</div>
        <div class="pd-phone-row">
            <div class="pd-phone-flag">🇬🇧 +44</div>
            <input type="tel" class="pd-phone-input" placeholder="Mobile number - SMS Updates">
        </div> --}}

        <hr class="pd-divider">

        {{-- Subtotal --}}
        <div class="pd-subtotal-box">
            <div class="pd-subtotal-row total">
                <span>Subtotal <span class="vat-note" id="subtotalVat">(£{{ number_format($product['base_price'] * 1.2, 2) }} inc VAT)</span></span>
                <span id="subtotalPrice">£{{ number_format($product['base_price'], 2) }}</span>
            </div>
            <div class="pd-subtotal-row">
                <span>Price Per Unit</span>
                <span class="per-unit" id="perUnitPrice">£{{ number_format($product['base_price'], 2) }}</span>
            </div>
        </div>
        </div> {{-- end oldPricingSection --}}

        {{-- CTAs --}}
        <div class="pd-cta-group">
            <button class="btn-upload" onclick="openUploadModal()">Upload Artwork</button>
            {{-- ADD TO BASKET FORM --}}
            <form method="POST" action="{{ route('cart.add') }}" id="addToBasketForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                <input type="hidden" name="price" id="hiddenPrice" value="{{ $product['base_price'] ?? 0 }}">
                <input type="hidden" name="turnaround_price" id="hiddenTurnaroundPrice" value="0">
                <input type="hidden" name="variation_price" id="hiddenVariationPrice" value="0">
                <input type="hidden" name="quantity" id="hiddenQty" value="1">
                <input type="hidden" name="options" id="hiddenOptions" value="">
                {{-- Artwork file input — linked to upload UI --}}
                <input type="file" name="artwork_file" id="artworkFileInput" style="display:none;" accept=".pdf,.jpg,.jpeg,.png,.ai,.eps,.tiff">
                <button type="submit" class="btn-basket" onclick="return syncAndSubmit()">Add to Basket</button>
            </form>
        </div>

    </div>
</div>

{{-- ===== MORE PRODUCTS ===== --}}
@if(count($relatedProducts) > 0)
<section class="more-products-section">
    <h2 data-aos="fade-up" data-aos-duration="600">More Products You Might Like</h2>
    <div class="more-products-grid">
        @foreach($relatedProducts as $index => $rp)
        <a href="{{ $rp['url'] }}" class="mp-card" data-aos="fade-up" data-aos-delay="{{ $index * 80 }}" data-aos-duration="600">
            <div class="mp-card-thumb {{ $rp['thumb_class'] }}">
                @if(!empty($rp['image']))
                    <img src="{{ asset($rp["image"]) }}" alt="{{ $rp['name'] }}" onerror="this.style.display='none'">
                @endif
            </div>
            <h3>{{ $rp['name'] }}</h3>
            <span class="mp-order-link">Order Now ›</span>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ===== FAQ SECTION ===== --}}
@if(count($faqs) > 0)
<section class="faq-section">
    <div class="faq-inner">
        <div class="faq-header">
            <div class="faq-header-left">
                <h2>Frequently asked questions</h2>
                <p>Got a question? We might have answered it here. If not, feel free to get in touch with us, we're here to help!</p>
            </div>
            <a href="{{ route('contact') }}" class="btn-contact">Contact</a>
        </div>
        <div class="faq-body">
            <div class="faq-list" id="faqList">
                @foreach($faqs as $i => $faq)
                <div class="faq-item {{ $i === 0 ? 'active' : '' }}" onclick="toggleFaq(this, {{ $i }})">
                    <div class="faq-question">
                        {{ $faq['q'] }}
                        <span class="faq-arrow">▶</span>
                    </div>
                </div>
                @endforeach
            </div>
            <div id="faqAnswer" class="faq-answer-panel">
                @if(count($faqs) > 0)
                    <h4>{{ $faqs[0]['q'] }}</h4>{{ $faqs[0]['a'] }}
                @endif
            </div>
        </div>
    </div>
</section>
@endif

{{-- ===== UPLOAD MODAL ===== --}}
<div id="uploadModal" class="upl-overlay" onclick="handleOverlayClick(event)">
    <div class="upl-modal">
        <button class="upl-close" onclick="closeUploadModal()">&#x2715;</button>

        {{-- STATE 1: Upload --}}
        <div id="stateUpload" class="upl-state">
            <div class="upl-cloud-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="16 16 12 12 8 16"/>
                    <line x1="12" y1="12" x2="12" y2="21"/>
                    <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                </svg>
            </div>
            <h3 class="upl-main-title">Upload</h3>
            <p class="upl-formats-text">Accepted file types <strong>.PDF, .JPEG, .PNG, .AI</strong></p>
            <span class="upl-maxsize">(Max file size 1500mb.)</span>
            <div class="upl-file-list" id="fileListContainer" style="display:none;"></div>
            <label class="btn-upload-device" for="fileInput">Upload from Device</label>
            <input type="file" id="fileInput" accept=".pdf,.jpg,.jpeg,.png" style="display:none;" multiple onchange="handleFileUpload(this)">
            <p class="upl-template-note">*Please ensure your uploaded file matches the <a href="#">artwork template</a> provided. If your file does not follow the supplied template, it may be rejected.</p>
            <div class="upl-check-row">
                <input type="checkbox" id="fileCheckPaid" class="upl-checkbox">
                <label for="fileCheckPaid">Get a professional file check for <strong>£2.99 + VAT</strong></label>
            </div>
        </div>

        {{-- STATE 2: File Uploaded --}}
        <div id="stateUploaded" class="upl-state" style="display:none;">
            <div class="upl-success-circle">
                <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <h3 class="upl-uploaded-title">File Uploaded</h3>
            <button class="btn-add-basket-yellow" onclick="showPreviewState()">Preview & Add to Basket</button>
            <p class="upl-template-note">*Please ensure your uploaded file matches the <a href="#">artwork template</a> provided. If your file does not follow the supplied template, it may be rejected.</p>
            <div class="upl-check-row">
                <input type="checkbox" id="fileCheckPaid2" class="upl-checkbox">
                <label for="fileCheckPaid2">Get a professional file check for <strong>£2.99 + VAT</strong></label>
            </div>
        </div>

        {{-- STATE 3: Preview --}}
        <div id="statePreview" class="upl-state preview-state" style="display:none;">
            <h3 class="upl-preview-heading">Preview</h3>
            <p class="upl-preview-tagline">This is your chance to ensure everything is just as you want it to be.</p>
            <div class="upl-sides-grid">
                <div class="upl-side">
                    <span class="upl-side-label">Front</span>
                    <div class="upl-side-frame">
                        <img id="previewFrontImg" src="" alt="Front Preview" style="display:none;">
                        <span class="upl-side-frame-back-label" id="frontPlaceholder">No front artwork</span>
                    </div>
                </div>
                <div class="upl-side">
                    <span class="upl-side-label">Back</span>
                    <div class="upl-side-frame back-frame">
                        <img id="previewBackImg" src="" alt="Back Preview" style="display:none;">
                        <span class="upl-side-frame-back-label" id="backPlaceholder">No back artwork uploaded</span>
                    </div>
                </div>
            </div>
            <div class="upl-preview-confirm">
                <div class="upl-confirm-check">
                    <input type="checkbox" id="confirmCheck" class="upl-checkbox">
                    <label for="confirmCheck">I confirm I have checked my print and it's all correct</label>
                </div>
                <button class="btn-basket-preview" onclick="submitWithArtwork()">Add to Basket</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const BASE_PRICE = {{ (float)($product['base_price'] ?? 0) }};
const faqData = @json($faqs);

// Gallery switcher
function switchGallery(url, thumb) {
    document.getElementById('mainGalleryImg').src = url;
    document.querySelectorAll('.pd-gallery-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}

// Accordion
function toggleAcc(btn) {
    btn.closest('.pd-acc-item').classList.toggle('open');
}

// FAQ
function toggleFaq(el, i) {
    document.querySelectorAll('.faq-item').forEach(x => x.classList.remove('active'));
    el.classList.add('active');
    var f = faqData[i] || {};
    document.getElementById('faqAnswer').innerHTML = '<h4>' + (f.q || '') + '</h4>' + (f.a || '');
}

// Toggle buttons
function selectToggle(btn) {
    var g = btn.getAttribute('data-group');
    document.querySelectorAll('.pd-toggle-btn[data-group="' + g + '"]').forEach(function(b) {
        b.classList.remove('active');
    });
    btn.classList.add('active');
    updatePrice();
}

// Price calculator
function updatePrice() {
    var extra = 0;
    document.querySelectorAll('.pd-select').forEach(function(s) {
        var o = s.options[s.selectedIndex];
        if (o) extra += parseFloat(o.getAttribute('data-extra') || 0);
    });
    document.querySelectorAll('.pd-toggle-btn.active').forEach(function(b) {
        extra += parseFloat(b.getAttribute('data-extra') || 0);
    });
    var qty = parseInt(document.getElementById('qtyInput').value) || 1;

    // Check turnaround pricing table first
    var unit = BASE_PRICE + extra;
    if (TURNAROUNDS && TURNAROUNDS[selectedTaIndex]) {
        var ta = TURNAROUNDS[selectedTaIndex];
        var pricing = ta.pricing || [];
        var bestPrice = null;
        // Find closest quantity (exact match or nearest lower)
        for (var i = 0; i < pricing.length; i++) {
            if (pricing[i].quantity <= qty) {
                bestPrice = pricing[i].price;
            }
        }
        if (bestPrice !== null) {
            unit = bestPrice + extra;
        }
        // Update price tag on turnaround card
        var taCard = document.querySelector('[data-ta-index="' + selectedTaIndex + '"] .pd-ta-price');
        if (taCard && pricing.length > 0) {
            var fromPrice = pricing[0].price;
            for (var j = 0; j < pricing.length; j++) {
                if (pricing[j].quantity <= qty) fromPrice = pricing[j].price;
            }
            taCard.textContent = '£' + (fromPrice + extra).toFixed(2);
        }
    }

    var total = unit * qty;
    document.getElementById('priceDisplay').innerHTML = '£' + unit.toFixed(2) + ' <span class="vat">(£' + (unit * 1.2).toFixed(2) + ' inc. VAT)</span>';
    document.getElementById('subtotalPrice').textContent = '£' + total.toFixed(2);
    document.getElementById('subtotalVat').textContent = '(£' + (total * 1.2).toFixed(2) + ' inc VAT)';
    document.getElementById('perUnitPrice').textContent = '£' + unit.toFixed(2);
}

// AOS
document.addEventListener('DOMContentLoaded', function() {
    if (typeof AOS !== 'undefined') AOS.init();
});

// ===== TURNAROUND PRICING =====
var TURNAROUNDS = @json($turnarounds);
var selectedTaIndex = 0;

function selectTurnaround(card) {
    document.querySelectorAll('.pd-ta-card').forEach(function(c){ c.classList.remove('active'); });
    card.classList.add('active');
    selectedTaIndex = parseInt(card.getAttribute('data-ta-index'));
    updatePrice();
}

// ===== UPLOAD MODAL =====
var uploadedFiles = [];

function openUploadModal() {
    showState('stateUpload');
    document.getElementById('uploadModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeUploadModal() {
    document.getElementById('uploadModal').classList.remove('active');
    document.body.style.overflow = '';
    setTimeout(function() {
        showState('stateUpload');
        uploadedFiles = [];
        renderFileList();
        document.getElementById('fileInput').value = '';
    }, 300);
}
function handleOverlayClick(e) {
    if (e.target === document.getElementById('uploadModal')) closeUploadModal();
}
function showState(id) {
    ['stateUpload', 'stateUploaded', 'statePreview'].forEach(function(s) {
        document.getElementById(s).style.display = 'none';
    });
    document.getElementById(id).style.display = 'block';
}
function handleFileUpload(input) {
    if (!input.files || !input.files.length) return;
    var allowed = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    var allowedExt = ['.pdf', '.jpg', '.jpeg', '.png'];
    Array.from(input.files).forEach(function(file) {
        var ext = '.' + file.name.split('.').pop().toLowerCase();
        if (!allowed.includes(file.type) && !allowedExt.includes(ext)) return;
        if (uploadedFiles.length >= 2) return;
        var reader = new FileReader();
        reader.onload = function(e) {
            uploadedFiles.push({ name: file.name, dataUrl: e.target.result, type: file.type, label: uploadedFiles.length === 0 ? 'Front' : 'Back', _file: file });
            renderFileList();
            if (uploadedFiles.length >= 1) showState('stateUploaded');
        };
        reader.readAsDataURL(file);
    });
}
function removeFile(i) {
    uploadedFiles.splice(i, 1);
    uploadedFiles.forEach(function(f, j) { f.label = j === 0 ? 'Front' : 'Back'; });
    renderFileList();
    if (!uploadedFiles.length) showState('stateUpload');
}
function renderFileList() {
    var c = document.getElementById('fileListContainer');
    if (!c) return;
    if (!uploadedFiles.length) { c.style.display = 'none'; c.innerHTML = ''; return; }
    c.style.display = 'block';
    c.innerHTML = uploadedFiles.map(function(f, i) {
        var name = f.name.length > 28 ? f.name.substring(0, 25) + '...' : f.name;
        return '<div class="upl-file-item"><span class="upl-file-dot"></span><span class="upl-file-name">' + name + '</span><span class="upl-file-label">' + f.label + '</span><button class="upl-file-remove" onclick="removeFile(' + i + ')">&#x2715;</button></div>';
    }).join('');
}
function showPreviewState() {
    var fi = document.getElementById('previewFrontImg'), fp = document.getElementById('frontPlaceholder');
    var bi = document.getElementById('previewBackImg'),  bp = document.getElementById('backPlaceholder');
    if (uploadedFiles[0]) {
        if (uploadedFiles[0].type === 'application/pdf') { fi.style.display='none'; fp.style.display='block'; fp.textContent='PDF: '+uploadedFiles[0].name; }
        else { fi.src = uploadedFiles[0].dataUrl; fi.style.display='block'; fp.style.display='none'; }
    }
    if (uploadedFiles[1]) {
        if (uploadedFiles[1].type === 'application/pdf') { bi.style.display='none'; bp.style.display='block'; bp.textContent='PDF: '+uploadedFiles[1].name; }
        else { bi.src = uploadedFiles[1].dataUrl; bi.style.display='block'; bp.style.display='none'; }
    } else { bi.style.display='none'; bp.style.display='block'; bp.textContent='No back artwork uploaded'; }
    showState('statePreview');
}
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeUploadModal(); });

// ── Add to Basket: sync selected options + price + qty ──
function syncHiddenFields() {
    // Check if variation system is active
    var varSection = document.getElementById('variationSection');
    if (varSection && varSection.style.display !== 'none') {
        // Variation mode — price and qty already set by variation JS
        // Just sync options from variation selectors
        var opts = {};
        document.querySelectorAll('.var-selector').forEach(function(sel) {
            var label = sel.closest('div').querySelector('.pd-option-label');
            var key = label ? label.textContent.trim().replace(/\s+/g,'_').toLowerCase() : 'option';
            opts[key] = sel.options[sel.selectedIndex].text;
        });
        document.getElementById('hiddenOptions').value = JSON.stringify(opts);
        return;
    }

    // Old system — Price
    var priceEl = document.getElementById('subtotalValue') || document.getElementById('perUnitPrice');
    if (priceEl) {
        var priceText = priceEl.textContent.replace(/[^0-9.]/g, '');
        document.getElementById('hiddenPrice').value = priceText || 0;
    }
    // Turnaround price for server-side verification
    if (typeof TURNAROUNDS !== 'undefined' && TURNAROUNDS[selectedTaIndex]) {
        var ta = TURNAROUNDS[selectedTaIndex];
        var qty = parseInt(document.getElementById('qtyInput').value) || 1;
        var pricing = ta.pricing || [];
        var bestPrice = null;
        for (var i = 0; i < pricing.length; i++) {
            if (pricing[i].quantity <= qty) bestPrice = pricing[i].price;
        }
        if (bestPrice !== null) {
            document.getElementById('hiddenTurnaroundPrice').value = bestPrice;
        }
    }
    // Quantity
    var qtyEl = document.querySelector('.pd-qty-input') || document.querySelector('[name="quantity"]');
    if (qtyEl) {
        document.getElementById('hiddenQty').value = qtyEl.value || 1;
    }
    // Options
    var opts = {};
    document.querySelectorAll('.pd-btn-group').forEach(function(group) {
        var activeBtn = group.querySelector('.pd-toggle-btn.active');
        if (activeBtn) {
            var label = group.previousElementSibling;
            var key = label ? label.textContent.trim().replace(/\s+/g,'_').toLowerCase() : 'option';
            opts[key] = activeBtn.textContent.trim();
        }
    });
    document.getElementById('hiddenOptions').value = JSON.stringify(opts);
}

function syncAndSubmit() {
    syncHiddenFields();

    // Artwork file — transfer uploaded file to form input
    var artworkInput = document.getElementById('artworkFileInput');
    if (uploadedFiles.length > 0 && uploadedFiles[0]._file) {
        var dataTransfer = new DataTransfer();
        uploadedFiles.forEach(function(f) {
            if (f._file) dataTransfer.items.add(f._file);
        });
        artworkInput.files = dataTransfer.files;
    }

    return true; // allow form submit
}

// Called from Preview modal "Add to Basket" button
function submitWithArtwork() {
    // Check confirm checkbox
    var confirmCheck = document.getElementById('confirmCheck');
    if (confirmCheck && !confirmCheck.checked) {
        alert('Please confirm you have checked your artwork before adding to basket.');
        return;
    }

    // Sync all fields
    syncHiddenFields();

    // Transfer artwork files to hidden form input
    var artworkInput = document.getElementById('artworkFileInput');
    if (uploadedFiles.length > 0) {
        var dataTransfer = new DataTransfer();
        uploadedFiles.forEach(function(f) {
            if (f._file) dataTransfer.items.add(f._file);
        });
        if (dataTransfer.files.length > 0) {
            artworkInput.files = dataTransfer.files;
        }
    }

    // Close modal and submit form
    closeUploadModal();
    document.getElementById('addToBasketForm').submit();
}

// ═══ VARIATION PRICING ═══
@if(!empty($hasVariations))
(function(){
    var VD = @json($variationData);
    var PN = @json($product['name']);
    var va = (VD.attributes||[]).filter(function(a){return a.used_for_variations&&a.values&&a.values.length>0;});
    var vt = VD.turnarounds_v||[], vq = VD.quantities||[], vars = VD.variations||[];
    var sqty=null, sturn=null;

    function opts(){var o={};document.querySelectorAll('.var-selector').forEach(function(s){o[s.dataset.attrId]=parseInt(s.value)});return o;}
    function findVar(o){for(var i=0;i<vars.length;i++){var v=vars[i];if(!v.enabled)continue;var s=v.selections||[],m=true;for(var j=0;j<va.length;j++){var a=va[j],sl=s.find(function(x){return x.attribute_id==a.id});if(!sl||sl.attribute_value_id!=o[a.id]){m=false;break;}}if(m)return v;}return null;}
    function qd(v,q){return(v.disabled_quantities||[]).indexOf(q)!==-1;}
    function gc(v,q,t){var p=v.pricing||[];for(var i=0;i<p.length;i++)if(p[i].quantity==q&&String(p[i].turnaround_id)==String(t))return p[i];return null;}

    window.updateVariationPrice=function(){
        var o=opts(),mv=findVar(o),nm=document.getElementById('varNoMatch'),tb=document.getElementById('varPriceTable'),sm=document.getElementById('varSummary');
        if(!mv){nm.style.display='block';tb.innerHTML='';sm.style.display='none';return;}
        nm.style.display='none';
        if(!vt.length||!vq.length){tb.innerHTML='<div style="color:#999;font-size:13px;padding:12px;text-align:center">No turnarounds/quantities configured.</div>';return;}
        var h='<div style="overflow-x:auto"><table style="width:100%;border-collapse:separate;border-spacing:0 6px;font-size:14px"><thead><tr>';
        h+='<th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase">Quantity</th>';
        vt.forEach(function(t){h+='<th style="padding:10px 14px;text-align:center;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase">'+t.label+'<div style="font-size:9px;font-weight:400;color:#9ca3af;margin-top:2px">'+(t.working_days_min==t.working_days_max?t.working_days_min+' Working Day'+(t.working_days_min>1?'s':''):t.working_days_min+'-'+t.working_days_max+' Days')+'</div></th>';});
        h+='</tr></thead><tbody>';
        vq.forEach(function(q){if(qd(mv,q.quantity))return;h+='<tr>';var qa=sqty==q.quantity;h+='<td style="padding:10px 14px;font-weight:700;cursor:pointer;border-radius:6px 0 0 6px;transition:all .15s;'+(qa?'background:#1e3a6e;color:#fff':'background:#f9fafb;color:#1e3a6e')+'" onclick="selectVarQty('+q.quantity+')">'+q.quantity+'</td>';
        vt.forEach(function(t){var c=gc(mv,q.quantity,t.id),cd=c?c.disabled:false,p=c?c.price:null,is=sqty==q.quantity&&sturn==t.id;h+='<td style="padding:3px">';if(cd)h+='<div style="padding:10px 14px;text-align:center;background:#f9fafb;color:#9ca3af;font-size:12px;border-radius:6px;border:2px solid #e5e7eb">Not Available</div>';else h+='<div style="padding:10px 14px;text-align:center;font-weight:600;cursor:pointer;border-radius:6px;transition:all .15s;'+(is?'background:#e63946;color:#fff;border:2px solid #e63946':'background:#fff;color:#1e3a6e;border:2px solid #e5e7eb')+'" onclick="selectVarCell('+q.quantity+','+t.id+')">'+(p?'£'+parseFloat(p).toFixed(2):'—')+'</div>';h+='</td>';});h+='</tr>';});
        h+='</tbody></table></div>';tb.innerHTML=h;updateVS(mv);
    };
    window.selectVarQty=function(q){sqty=q;updateVariationPrice();};
    window.selectVarCell=function(q,t){sqty=q;sturn=t;updateVariationPrice();};
    function updateVS(mv){
        var sm=document.getElementById('varSummary');
        if(!mv||!sqty||!sturn){sm.style.display='none';return;}
        var c=gc(mv,sqty,sturn);if(!c||c.disabled){sm.style.display='none';return;}
        var p=c.price,t=vt.find(function(x){return x.id==sturn;});
        document.getElementById('varSummaryText').textContent=sqty+' × '+PN+' — '+(t?t.label:'')+' delivery';
        var sp=[];document.querySelectorAll('.var-selector').forEach(function(s){sp.push(s.options[s.selectedIndex].text)});
        document.getElementById('varSummarySpecs').textContent=sp.join(' · ');
        document.getElementById('varSummaryPrice').textContent=p?'£'+parseFloat(p).toFixed(2):'N/A';
        document.getElementById('varSummaryVat').textContent=p?'(£'+(parseFloat(p)*1.2).toFixed(2)+' inc. VAT) × '+sqty+' = £'+(parseFloat(p)*sqty).toFixed(2):'';
        sm.style.display='block';
        var hp=document.getElementById('hiddenPrice'),hq=document.getElementById('hiddenQty');
        if(hp)hp.value=p||0;if(hq)hq.value=sqty||1;
        var hvp=document.getElementById('hiddenVariationPrice');if(hvp)hvp.value=p||0;
    }
    document.addEventListener('DOMContentLoaded',function(){updateVariationPrice();});
})();
@endif
</script>
@endsection