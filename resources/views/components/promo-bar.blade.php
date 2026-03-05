{{-- Dynamic promo bar from site_settings --}}
@php
    try {
        $promoEnabled = \App\Models\SiteSetting::get('promo_bar_enabled', '1');
        $promoText    = \App\Models\SiteSetting::get('promo_bar_text', '🎉 Free Next Day Delivery on ALL orders! Trade customers only.');
    } catch (\Exception $e) {
        $promoEnabled = '1';
        $promoText    = '🎉 Free Next Day Delivery on ALL orders! Trade customers only.';
    }
@endphp
@if($promoEnabled === '1')
<div class="promo-bar">
    <div class="promo-bar-inner">
        <div class="promo-left"></div>
        <div class="promo-center">
            <span class="promo-main-text">{{ $promoText }}</span>
        </div>
        <div class="promo-right"></div>
    </div>
</div>
@endif
