@extends('layouts.admin')
@section('title','Site Settings')
@section('page_title','Site Settings')

@section('styles')
<style>
.settings-tabs { display:flex; gap:6px; margin-bottom:24px; flex-wrap:wrap; }
.stab { padding:9px 18px; border-radius:7px; font-size:12px; font-weight:700; cursor:pointer; border:1.5px solid #e0e0e0; background:#fff; color:#666; transition:all .2s; font-family:'Inter',sans-serif; }
.stab.active, .stab:hover { background:#1e3a6e; color:#fff; border-color:#1e3a6e; }
.settings-panel { display:none; }
.settings-panel.active { display:block; }
.color-row { display:flex; align-items:center; gap:12px; }
.color-row input[type=color] { width:44px; height:38px; border:1.5px solid #ddd; border-radius:6px; padding:2px; cursor:pointer; background:#fff; }
.color-row input[type=text] { flex:1; }
.preview-box { width:100%; height:180px; border-radius:10px; border:1px solid #e8e8e8; display:flex; align-items:center; justify-content:center; font-size:13px; color:#aaa; margin-bottom:16px; overflow:hidden; transition:all .3s; }
.img-preview { width:100%;height:100%;object-fit:cover;border-radius:9px; }
.section-divider { border:none;border-top:2px solid #f0f0f0;margin:20px 0; }
.gradient-row { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.gradient-preview { height:60px; border-radius:8px; border:1px solid #eee; transition:all .3s; }
</style>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="settingsForm">
@csrf

{{-- TABS --}}
<div class="settings-tabs">
    <button type="button" class="stab active" onclick="showTab('design')"><i class="fas fa-palette"></i> Design & Colors</button>
    <button type="button" class="stab" onclick="showTab('homepage')"><i class="fas fa-home"></i> Home Page</button>
    <button type="button" class="stab" onclick="showTab('header')"><i class="fas fa-heading"></i> Header</button>
    <button type="button" class="stab" onclick="showTab('footer')"><i class="fas fa-shoe-prints"></i> Footer</button>
    <button type="button" class="stab" onclick="showTab('general')"><i class="fas fa-cog"></i> General</button>
    <button type="button" class="stab" onclick="showTab('seo')"><i class="fas fa-search"></i> SEO</button>
    <button type="button" class="stab" onclick="showTab('pages')"><i class="fas fa-file-alt"></i> Pages</button>
    <button type="button" class="stab" onclick="showTab('payment')"><i class="fas fa-credit-card"></i> Payment</button>
    <button type="button" class="stab" onclick="showTab('email')"><i class="fas fa-envelope"></i> Email</button>
</div>

{{-- ════════════════════════════════════ --}}
{{-- TAB: DESIGN & COLORS --}}
{{-- ════════════════════════════════════ --}}
<div class="settings-panel active" id="tab-design">
@php $s = collect($grouped['design'] ?? [])->keyBy('key'); @endphp

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

  {{-- LEFT: Brand Colors --}}
  <div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-palette" style="color:#1e3a6e;margin-right:6px"></i>Brand Colors</h3></div>
    <div style="padding:20px">
      @foreach(['color_primary'=>'Primary (Navy)','color_accent'=>'Accent (Red)','color_yellow'=>'Highlight (Yellow)','color_bg'=>'Page Background','color_text'=>'Body Text','color_nav_active'=>'Nav Hover/Active'] as $key=>$label)
      @php $val = $s[$key]->value ?? '#000000'; @endphp
      <div class="form-group">
        <label>{{ $label }}</label>
        <div class="color-row">
          <input type="color" value="{{ $val }}" oninput="syncColor(this,'{{ $key }}')" id="cp_{{ $key }}">
          <input type="text" name="{{ $key }}" id="{{ $key }}" value="{{ $val }}" class="form-control"
            oninput="document.getElementById('cp_{{ $key }}').value=this.value" style="font-family:monospace">
        </div>
      </div>
      @endforeach
    </div>
  </div>

  {{-- RIGHT: Section Colors --}}
  <div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-layer-group" style="color:#1e3a6e;margin-right:6px"></i>Section Colors</h3></div>
    <div style="padding:20px">
      @foreach(['color_header_bg'=>'Header Background','color_footer_bg'=>'Footer Background','color_promo_bg'=>'Promo Bar Background','color_promo_text'=>'Promo Bar Text','color_hero_bg'=>'Hero Background','color_hero_right_bg'=>'Hero Right Panel','color_btn_primary_bg'=>'Button Background','color_btn_primary_text'=>'Button Text'] as $key=>$label)
      @php $val = $s[$key]->value ?? '#000000'; @endphp
      <div class="form-group">
        <label>{{ $label }}</label>
        <div class="color-row">
          <input type="color" value="{{ $val }}" oninput="syncColor(this,'{{ $key }}')" id="cp_{{ $key }}">
          <input type="text" name="{{ $key }}" id="{{ $key }}" value="{{ $val }}" class="form-control"
            oninput="document.getElementById('cp_{{ $key }}').value=this.value" style="font-family:monospace">
        </div>
      </div>
      @endforeach
    </div>
  </div>

</div>

{{-- Product Card Colors --}}
<div class="data-card" style="margin-top:20px">
  <div class="data-card-hdr"><h3><i class="fas fa-th-large" style="color:#1e3a6e;margin-right:6px"></i>Product Card Colors (Gradient)</h3></div>
  <div style="padding:20px">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px">
      @foreach(['color_pt1'=>'Card 1','color_pt2'=>'Card 2','color_pt3'=>'Card 3','color_pt4'=>'Card 4'] as $key=>$label)
      @php $val = $s[$key]->value ?? 'linear-gradient(135deg,#ccc,#888)'; @endphp
      <div>
        <div class="gradient-preview" id="prev_{{ $key }}" style="background:{{ $val }}"></div>
        <label style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;margin:8px 0 4px;display:block">{{ $label }}</label>
        <input type="text" name="{{ $key }}" id="{{ $key }}" value="{{ $val }}"
          class="form-control" style="font-size:11px;height:36px"
          oninput="document.getElementById('prev_{{ $key }}').style.background=this.value"
          placeholder="e.g. linear-gradient(135deg,#f00,#00f)">
      </div>
      @endforeach
    </div>
    <div style="margin-top:12px;padding:10px 14px;background:#f8f8f8;border-radius:6px;font-size:12px;color:#888">
      <i class="fas fa-info-circle" style="color:#1e3a6e"></i>
      Tip: Use CSS gradients like <code>linear-gradient(135deg,#ff6b6b,#ee5a24)</code> or solid colors like <code>#ff6b6b</code>
    </div>
  </div>
</div>

</div>

{{-- ════════════════════════════════════ --}}
{{-- TAB: HOME PAGE --}}
{{-- ════════════════════════════════════ --}}
<div class="settings-panel" id="tab-homepage">
@php $hp = collect($grouped['homepage'] ?? [])->keyBy('key'); @endphp

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

  {{-- Hero Text --}}
  <div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-star" style="color:#1e3a6e;margin-right:6px"></i>Hero Section</h3></div>
    <div style="padding:20px">

      {{-- Live preview --}}
      <div style="background:{{ $hp['hero_bg_color']->value ?? '#f8f4f0' }};border-radius:10px;padding:24px 28px;margin-bottom:20px;border:1px solid #eee">
        <div id="prev_hero_title" style="font-family:'Montserrat',sans-serif;font-size:32px;font-weight:900;color:#1e3a6e;line-height:1.1;margin-bottom:8px">
          {{ $hp['hero_title_line1']->value ?? 'Bound to' }}<br>{{ $hp['hero_title_line2']->value ?? 'Impress' }}
        </div>
        <div id="prev_hero_sub" style="font-size:14px;color:#666;margin-bottom:14px">{{ $hp['hero_subtitle']->value ?? '' }}</div>
        <div id="prev_hero_btn" style="display:inline-block;background:#1e3a6e;color:#fff;padding:10px 22px;border-radius:6px;font-size:13px;font-weight:700">{{ $hp['hero_btn_text']->value ?? 'Shop Now' }}</div>
      </div>

      <div class="form-group">
        <label>Title Line 1</label>
        <input type="text" name="hero_title_line1" class="form-control"
          value="{{ $hp['hero_title_line1']->value ?? 'Bound to' }}"
          oninput="updateHeroPreview()">
      </div>
      <div class="form-group">
        <label>Title Line 2</label>
        <input type="text" name="hero_title_line2" class="form-control"
          value="{{ $hp['hero_title_line2']->value ?? 'Impress' }}"
          oninput="updateHeroPreview()">
      </div>
      <div class="form-group">
        <label>Subtitle</label>
        <input type="text" name="hero_subtitle" class="form-control"
          value="{{ $hp['hero_subtitle']->value ?? '' }}"
          oninput="updateHeroPreview()">
      </div>
      <div class="form-group">
        <label>Button Text</label>
        <input type="text" name="hero_btn_text" class="form-control"
          value="{{ $hp['hero_btn_text']->value ?? 'Shop Now' }}"
          oninput="updateHeroPreview()">
      </div>
      <div class="form-group">
        <label>Hero Left Panel Color</label>
        <div class="color-row">
          @php $hleft = $hp['color_hero_left']->value ?? '#81C071'; @endphp
          <input type="color" value="{{ $hleft }}" oninput="syncColor(this,'color_hero_left')" id="cp_color_hero_left">
          <input type="text" name="color_hero_left" id="color_hero_left" value="{{ $hleft }}" class="form-control"
            oninput="document.getElementById('cp_color_hero_left').value=this.value" style="font-family:monospace">
        </div>
      </div>
      <div class="form-group">
        <label>Hero Right Panel Color</label>
        <div class="color-row">
          @php $hright = $hp['color_hero_right_bg']->value ?? '#fd6c99'; @endphp
          <input type="color" value="{{ $hright }}" oninput="syncColor(this,'color_hero_right_bg')" id="cp_color_hero_right_bg">
          <input type="text" name="color_hero_right_bg" id="color_hero_right_bg" value="{{ $hright }}" class="form-control"
            oninput="document.getElementById('cp_color_hero_right_bg').value=this.value" style="font-family:monospace">
        </div>
      </div>
      <div class="form-group">
        <label>Hero Background Color</label>
        <div class="color-row">
          @php $hbg = $hp['hero_bg_color']->value ?? '#f8f4f0'; @endphp
          <input type="color" value="{{ $hbg }}" oninput="syncColor(this,'hero_bg_color')" id="cp_hero_bg_color">
          <input type="text" name="hero_bg_color" id="hero_bg_color" value="{{ $hbg }}" class="form-control"
            oninput="document.getElementById('cp_hero_bg_color').value=this.value" style="font-family:monospace">
        </div>
      </div>
    </div>
  </div>

  {{-- Hero Image + Trustpilot --}}
  <div>
    <div class="data-card" style="margin-bottom:20px">
      <div class="data-card-hdr"><h3><i class="fas fa-image" style="color:#1e3a6e;margin-right:6px"></i>Hero Image</h3></div>
      <div style="padding:20px">
        @php $heroImg = $hp['hero_image']->value ?? 'images/hero-product.png'; @endphp
        <div class="preview-box" id="heroImgPreview">
          <img src="{{ asset($heroImg) }}" class="img-preview" id="heroImgEl" onerror="this.parentElement.innerHTML='<span>No image</span>'">
        </div>
        <input type="file" name="hero_image_upload" accept="image/*" class="form-control" style="height:auto;padding:8px"
          onchange="previewImg(this,'heroImgEl','heroImgPreview')">
        <input type="hidden" name="hero_image" id="hero_image" value="{{ $heroImg }}">
        <div style="font-size:11px;color:#aaa;margin-top:6px">Recommended: 600×500px PNG with transparent background</div>
      </div>
    </div>

    <div class="data-card">
      <div class="data-card-hdr"><h3><i class="fas fa-star" style="color:#f5c518;margin-right:6px"></i>Trustpilot</h3></div>
      <div style="padding:20px">
        <div class="form-group">
          <label>Rating (e.g. 4.8)</label>
          <input type="text" name="trustpilot_rating" class="form-control" value="{{ $hp['trustpilot_rating']->value ?? '4.8' }}">
        </div>
        <div class="form-group">
          <label>Review Count (e.g. 10,814)</label>
          <input type="text" name="trustpilot_reviews" class="form-control" value="{{ $hp['trustpilot_reviews']->value ?? '10,814' }}">
        </div>
      </div>
    </div>
  </div>

</div>
</div>

{{-- ════════════════════════════════════ --}}
{{-- TAB: HEADER --}}
{{-- ════════════════════════════════════ --}}
<div class="settings-panel" id="tab-header">
@php $hd = collect($grouped['header'] ?? [])->keyBy('key'); @endphp

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
  <div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-image" style="color:#1e3a6e;margin-right:6px"></i>Site Logo</h3></div>
    <div style="padding:20px">
      @php $logoVal = $hd['header_logo']->value ?? ''; @endphp
      <div class="preview-box" id="logoPreview" style="background:#f5f5f5;height:100px">
        @if($logoVal)
          <img src="{{ asset($logoVal) }}" class="img-preview" id="logoImgEl" style="object-fit:contain;padding:10px" onerror="this.style.display='none'">
        @else
          <img src="{{ asset('images/logo.png') }}" id="logoImgEl" style="height:60px;object-fit:contain" onerror="this.style.display='none'">
        @endif
      </div>
      <input type="file" name="header_logo_upload" accept="image/*" class="form-control" style="height:auto;padding:8px"
        onchange="previewImg(this,'logoImgEl','logoPreview')">
      <input type="hidden" name="header_logo" id="header_logo" value="{{ $logoVal }}">
      <div style="font-size:11px;color:#aaa;margin-top:6px">Leave empty to keep current logo. Recommended: PNG with transparent background.</div>
    </div>
  </div>

  <div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-cog" style="color:#1e3a6e;margin-right:6px"></i>Header Options</h3></div>
    <div style="padding:20px">
      <div class="form-group">
        <label>Header Background</label>
        @php $hbgVal = collect($grouped['design'] ?? [])->keyBy('key')['color_header_bg']->value ?? '#ffffff'; @endphp
        <div class="color-row">
          <input type="color" value="{{ $hbgVal }}" oninput="syncColor(this,'color_header_bg')" id="cp_color_header_bg2">
          <input type="text" name="color_header_bg" id="color_header_bg" value="{{ $hbgVal }}" class="form-control"
            oninput="document.getElementById('cp_color_header_bg2').value=this.value" style="font-family:monospace">
        </div>
      </div>
      <div class="form-group">
        <label>Phone in Header (optional)</label>
        <input type="text" name="header_phone" class="form-control"
          value="{{ $hd['header_phone']->value ?? '' }}" placeholder="+44 20 1234 5678">
      </div>
    </div>
  </div>
</div>
</div>

{{-- ════════════════════════════════════ --}}
{{-- TAB: FOOTER --}}
{{-- ════════════════════════════════════ --}}
<div class="settings-panel" id="tab-footer">
@php $ft = collect($grouped['footer'] ?? [])->keyBy('key'); @endphp

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
  <div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-address-book" style="color:#1e3a6e;margin-right:6px"></i>Contact Info</h3></div>
    <div style="padding:20px">
      <div class="form-group"><label>Business Hours</label>
        <input type="text" name="footer_hours" class="form-control" value="{{ $ft['footer_hours']->value ?? 'Monday to Friday 9am - 5:30pm' }}"></div>
      <div class="form-group"><label>Phone</label>
        <input type="text" name="footer_phone" class="form-control" value="{{ $ft['footer_phone']->value ?? '' }}"></div>
      <div class="form-group"><label>Email</label>
        <input type="text" name="footer_email" class="form-control" value="{{ $ft['footer_email']->value ?? '' }}"></div>
      <div class="form-group"><label>Address</label>
        <textarea name="footer_address" class="form-control">{{ $ft['footer_address']->value ?? '' }}</textarea></div>
      <div class="form-group"><label>Copyright Name</label>
        <input type="text" name="footer_copyright" class="form-control" value="{{ $ft['footer_copyright']->value ?? 'London InstantPrint' }}"></div>
    </div>
  </div>

  <div>
    <div class="data-card" style="margin-bottom:20px">
      <div class="data-card-hdr"><h3><i class="fas fa-share-alt" style="color:#1e3a6e;margin-right:6px"></i>Social Links</h3></div>
      <div style="padding:20px">
        <div class="form-group"><label><i class="fab fa-facebook" style="color:#1877f2"></i> Facebook URL</label>
          <input type="text" name="footer_facebook" class="form-control" value="{{ $ft['footer_facebook']->value ?? '#' }}"></div>
        <div class="form-group"><label><i class="fab fa-linkedin" style="color:#0077b5"></i> LinkedIn URL</label>
          <input type="text" name="footer_linkedin" class="form-control" value="{{ $ft['footer_linkedin']->value ?? '#' }}"></div>
        <div class="form-group"><label><i class="fab fa-twitter" style="color:#1da1f2"></i> Twitter/X URL</label>
          <input type="text" name="footer_twitter" class="form-control" value="{{ $ft['footer_twitter']->value ?? '#' }}"></div>
      </div>
    </div>

    <div class="data-card">
      <div class="data-card-hdr"><h3><i class="fas fa-paint-brush" style="color:#1e3a6e;margin-right:6px"></i>Footer Color</h3></div>
      <div style="padding:20px">
        @php $fbg = collect($grouped['design'] ?? [])->keyBy('key')['color_footer_bg']->value ?? '#1e3a6e'; @endphp
        <div class="form-group"><label>Footer Background</label>
          <div class="color-row">
            <input type="color" value="{{ $fbg }}" oninput="syncColor(this,'color_footer_bg')" id="cp_footer_bg">
            <input type="text" name="color_footer_bg" id="color_footer_bg" value="{{ $fbg }}" class="form-control"
              oninput="document.getElementById('cp_footer_bg').value=this.value" style="font-family:monospace">
          </div>
        </div>
        <div style="height:60px;border-radius:8px;transition:.3s" id="footerBgPreview" style="background:{{ $fbg }}"></div>
      </div>
    </div>
  </div>
</div>
</div>

{{-- ════════════════════════════════════ --}}
{{-- TAB: GENERAL --}}
{{-- ════════════════════════════════════ --}}
<div class="settings-panel" id="tab-general">
@php $gen = collect($grouped['general'] ?? [])->keyBy('key'); @endphp
<div class="data-card" style="max-width:700px">
  <div class="data-card-hdr"><h3><i class="fas fa-cog" style="color:#1e3a6e;margin-right:6px"></i>General Settings</h3></div>
  <div style="padding:20px">
    <div class="form-group">
      <label>Promo Bar Text</label>
      <input type="text" name="promo_bar_text" class="form-control" value="{{ $gen['promo_bar_text']->value ?? '' }}">
    </div>
    <div class="form-group">
      <label>Promo Bar Background</label>
      @php $promoBg = collect($grouped['design'] ?? [])->keyBy('key')['color_promo_bg']->value ?? '#f5c518'; @endphp
      <div class="color-row">
        <input type="color" value="{{ $promoBg }}" oninput="syncColor(this,'color_promo_bg')" id="cp_promo_bg">
        <input type="text" name="color_promo_bg" id="color_promo_bg" value="{{ $promoBg }}" class="form-control"
          oninput="document.getElementById('cp_promo_bg').value=this.value" style="font-family:monospace">
      </div>
    </div>
    <div class="form-group">
      <label>Promo Bar Text Color</label>
      @php $promoTxt = collect($grouped['design'] ?? [])->keyBy('key')['color_promo_text']->value ?? '#1a1a1a'; @endphp
      <div class="color-row">
        <input type="color" value="{{ $promoTxt }}" oninput="syncColor(this,'color_promo_text')" id="cp_promo_text">
        <input type="text" name="color_promo_text" id="color_promo_text" value="{{ $promoTxt }}" class="form-control"
          oninput="document.getElementById('cp_promo_text').value=this.value" style="font-family:monospace">
      </div>
    </div>
    <div class="form-group">
      <label>Show Promo Bar</label>
      <select name="promo_bar_enabled" class="form-control" style="width:auto">
        <option value="1" {{ ($gen['promo_bar_enabled']->value ?? '1')==='1'?'selected':'' }}>Enabled</option>
        <option value="0" {{ ($gen['promo_bar_enabled']->value ?? '1')==='0'?'selected':'' }}>Disabled</option>
      </select>
    </div>
  </div>
</div>
</div>

{{-- ════════════════════════════════════ --}}
{{-- TAB: SEO --}}
{{-- ════════════════════════════════════ --}}
<div class="settings-panel" id="tab-seo">
@php $seo = collect($grouped['seo'] ?? [])->keyBy('key'); @endphp
<div class="data-card" style="max-width:700px">
  <div class="data-card-hdr"><h3><i class="fas fa-search" style="color:#1e3a6e;margin-right:6px"></i>SEO Settings</h3></div>
  <div style="padding:20px">
    <div class="form-group"><label>Site Name</label>
      <input type="text" name="site_name" class="form-control" value="{{ $seo['site_name']->value ?? '' }}"></div>
    <div class="form-group"><label>Default Meta Description</label>
      <textarea name="meta_description" class="form-control">{{ $seo['meta_description']->value ?? '' }}</textarea></div>
    <div class="form-group"><label>Google Analytics ID (e.g. G-XXXXXXXX)</label>
      <input type="text" name="google_analytics" class="form-control" value="{{ $seo['google_analytics']->value ?? '' }}" placeholder="G-XXXXXXXXXX"></div>
  </div>
</div>
</div>

{{-- ════════════════════════════════════ --}}
{{-- TAB: PAGES --}}
{{-- ════════════════════════════════════ --}}
<div class="settings-panel" id="tab-pages">
@php $pg = collect($grouped['pages'] ?? [])->keyBy('key'); @endphp

{{-- ── Sub-tabs ── --}}
<div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap">
    <button type="button" class="stab active" onclick="showPageTab('ptab-about',this)" style="font-size:11px;padding:7px 14px"><i class="fas fa-info-circle"></i> About Us</button>
    <button type="button" class="stab" onclick="showPageTab('ptab-privacy',this)" style="font-size:11px;padding:7px 14px"><i class="fas fa-shield-alt"></i> Privacy Policy</button>
    <button type="button" class="stab" onclick="showPageTab('ptab-terms',this)" style="font-size:11px;padding:7px 14px"><i class="fas fa-file-contract"></i> Terms &amp; Conditions</button>
</div>

{{-- ── ABOUT US ── --}}
<div class="settings-panel active" id="ptab-about">
  <div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-info-circle" style="color:#1e3a6e;margin-right:6px"></i>About Us Page</h3></div>
    <div style="padding:20px">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label>Hero Title</label>
          <input type="text" name="about_hero_title" class="form-control" value="{{ $pg['about_hero_title']->value ?? 'About London InstantPrint' }}">
        </div>
        <div class="form-group">
          <label>Values Section Title</label>
          <input type="text" name="about_values_title" class="form-control" value="{{ $pg['about_values_title']->value ?? 'What We Stand For' }}">
        </div>
      </div>
      <div class="form-group">
        <label>Hero Subtitle</label>
        <textarea name="about_hero_subtitle" class="form-control" rows="2">{{ $pg['about_hero_subtitle']->value ?? '' }}</textarea>
      </div>
      <hr class="section-divider">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label>Our Story — Heading</label>
          <input type="text" name="about_story_title" class="form-control" value="{{ $pg['about_story_title']->value ?? 'Our Story' }}">
        </div>
        <div class="form-group">
          <label>Our Mission — Heading</label>
          <input type="text" name="about_mission_title" class="form-control" value="{{ $pg['about_mission_title']->value ?? 'Our Mission' }}">
        </div>
      </div>
      <div class="form-group">
        <label>Our Story — Body Text</label>
        <textarea name="about_story_body" class="form-control" rows="6">{{ $pg['about_story_body']->value ?? '' }}</textarea>
      </div>
      <div class="form-group">
        <label>Our Mission — Body Text</label>
        <textarea name="about_mission_body" class="form-control" rows="3">{{ $pg['about_mission_body']->value ?? '' }}</textarea>
      </div>
      <hr class="section-divider">
      <div style="margin-bottom:10px;font-weight:700;color:#555;font-size:13px"><i class="fas fa-chart-bar" style="color:#1e3a6e"></i> Stats Strip</div>
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px">
        @foreach([1,2,3,4] as $si)
        <div>
          <div class="form-group" style="margin-bottom:8px">
            <label style="font-size:11px">Stat {{ $si }} — Number</label>
            <input type="text" name="about_stat{{ $si }}_number" class="form-control" style="height:36px" value="{{ $pg['about_stat'.$si.'_number']->value ?? '' }}">
          </div>
          <div class="form-group" style="margin-bottom:0">
            <label style="font-size:11px">Stat {{ $si }} — Label</label>
            <input type="text" name="about_stat{{ $si }}_label" class="form-control" style="height:36px" value="{{ $pg['about_stat'.$si.'_label']->value ?? '' }}">
          </div>
        </div>
        @endforeach
      </div>
      <hr class="section-divider">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label>CTA Section — Title</label>
          <input type="text" name="about_cta_title" class="form-control" value="{{ $pg['about_cta_title']->value ?? 'Ready to place your first order?' }}">
        </div>
        <div class="form-group">
          <label>CTA Section — Body</label>
          <input type="text" name="about_cta_body" class="form-control" value="{{ $pg['about_cta_body']->value ?? '' }}">
        </div>
      </div>
      <div style="margin-top:8px;padding:8px 12px;background:#f0f5ff;border-radius:6px;font-size:12px;color:#555">
        <i class="fas fa-link" style="color:#1e3a6e"></i> Page URL: <a href="{{ url('/about-us') }}" target="_blank" style="color:#1e3a6e">{{ url('/about-us') }}</a>
      </div>
    </div>
  </div>
</div>

{{-- ── PRIVACY POLICY ── --}}
<div class="settings-panel" id="ptab-privacy">
  <div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-shield-alt" style="color:#1e3a6e;margin-right:6px"></i>Privacy Policy Page</h3></div>
    <div style="padding:20px">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label>Company Name</label>
          <input type="text" name="privacy_company_name" class="form-control" value="{{ $pg['privacy_company_name']->value ?? 'London InstantPrint Ltd' }}">
        </div>
        <div class="form-group">
          <label>Last Updated Date</label>
          <input type="text" name="privacy_last_updated" class="form-control" value="{{ $pg['privacy_last_updated']->value ?? '1 March 2025' }}" placeholder="e.g. 1 March 2025">
        </div>
      </div>
      <div class="form-group">
        <label>Registered Address</label>
        <textarea name="privacy_company_address" class="form-control" rows="2">{{ $pg['privacy_company_address']->value ?? '' }}</textarea>
      </div>
      <div class="form-group">
        <label>Privacy Contact Email</label>
        <input type="text" name="privacy_contact_email" class="form-control" value="{{ $pg['privacy_contact_email']->value ?? 'privacy@londoninstantprint.co.uk' }}">
      </div>
      <div class="form-group">
        <label>Opening Paragraph</label>
        <textarea name="privacy_intro" class="form-control" rows="3">{{ $pg['privacy_intro']->value ?? '' }}</textarea>
      </div>
      <div style="padding:10px 14px;background:#fff3cd;border-radius:6px;font-size:12px;color:#856404">
        <i class="fas fa-info-circle"></i> The Privacy Policy page contains detailed legal sections that are built-in. Edit the fields above to update your company details and key text.
        &nbsp; <a href="{{ url('/privacy-policy') }}" target="_blank" style="color:#856404;font-weight:700">View page →</a>
      </div>
    </div>
  </div>
</div>

{{-- ── TERMS ── --}}
<div class="settings-panel" id="ptab-terms">
  <div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-file-contract" style="color:#1e3a6e;margin-right:6px"></i>Terms &amp; Conditions Page</h3></div>
    <div style="padding:20px">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label>Company Name</label>
          <input type="text" name="terms_company_name" class="form-control" value="{{ $pg['terms_company_name']->value ?? 'London InstantPrint Ltd' }}">
        </div>
        <div class="form-group">
          <label>Last Updated Date</label>
          <input type="text" name="terms_last_updated" class="form-control" value="{{ $pg['terms_last_updated']->value ?? '1 March 2025' }}" placeholder="e.g. 1 March 2025">
        </div>
      </div>
      <div class="form-group">
        <label>Opening Paragraph</label>
        <textarea name="terms_intro" class="form-control" rows="3">{{ $pg['terms_intro']->value ?? '' }}</textarea>
      </div>
      <div style="padding:10px 14px;background:#fff3cd;border-radius:6px;font-size:12px;color:#856404">
        <i class="fas fa-info-circle"></i> The T&amp;C page contains 12 comprehensive legal sections that are built-in. Edit the fields above to update your company details and key text.
        &nbsp; <a href="{{ url('/terms-conditions') }}" target="_blank" style="color:#856404;font-weight:700">View page →</a>
      </div>
    </div>
  </div>
</div>

</div>

{{-- ════════════════════════════════════ --}}
{{-- TAB: PAYMENT --}}
{{-- ════════════════════════════════════ --}}
<div class="settings-panel" id="tab-payment">
@php $pay = collect($grouped['payment'] ?? [])->keyBy('key'); @endphp

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

  {{-- Payment Methods --}}
  <div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-credit-card" style="color:#1e3a6e;margin-right:6px"></i>Payment Methods</h3></div>
    <div style="padding:20px">
      <p style="font-size:12px;color:#888;margin-bottom:16px">Enable or disable payment methods shown on checkout page.</p>

      <div class="form-group">
        <label>Credit / Debit Card</label>
        <select name="payment_card_enabled" class="form-control">
          <option value="1" {{ ($pay['payment_card_enabled']->value ?? '1') == '1' ? 'selected' : '' }}>Enabled</option>
          <option value="0" {{ ($pay['payment_card_enabled']->value ?? '1') == '0' ? 'selected' : '' }}>Disabled</option>
        </select>
      </div>

      <div class="form-group">
        <label>PayPal</label>
        <select name="payment_paypal_enabled" class="form-control">
          <option value="1" {{ ($pay['payment_paypal_enabled']->value ?? '1') == '1' ? 'selected' : '' }}>Enabled</option>
          <option value="0" {{ ($pay['payment_paypal_enabled']->value ?? '1') == '0' ? 'selected' : '' }}>Disabled</option>
        </select>
      </div>

      <div class="form-group">
        <label>Pay by Invoice (Trade)</label>
        <select name="payment_invoice_enabled" class="form-control">
          <option value="1" {{ ($pay['payment_invoice_enabled']->value ?? '1') == '1' ? 'selected' : '' }}>Enabled</option>
          <option value="0" {{ ($pay['payment_invoice_enabled']->value ?? '1') == '0' ? 'selected' : '' }}>Disabled</option>
        </select>
      </div>
    </div>
  </div>

  {{-- PayPal Settings --}}
  <div class="data-card">
    <div class="data-card-hdr"><h3><i class="fab fa-paypal" style="color:#003087;margin-right:6px"></i>PayPal Configuration</h3></div>
    <div style="padding:20px">
      <div class="form-group">
        <label>PayPal Mode</label>
        <select name="paypal_mode" class="form-control">
          <option value="sandbox" {{ ($pay['paypal_mode']->value ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
          <option value="live" {{ ($pay['paypal_mode']->value ?? 'sandbox') == 'live' ? 'selected' : '' }}>Live (Production)</option>
        </select>
      </div>

      <div class="form-group">
        <label>PayPal Client ID</label>
        <input type="text" name="paypal_client_id" class="form-control" value="{{ $pay['paypal_client_id']->value ?? '' }}" placeholder="Enter PayPal Client ID">
      </div>

      <div class="form-group">
        <label>PayPal Secret</label>
        <input type="password" name="paypal_secret" class="form-control" value="{{ $pay['paypal_secret']->value ?? '' }}" placeholder="Enter PayPal Secret">
      </div>

      <div class="form-group">
        <label>PayPal Currency</label>
        <input type="text" name="paypal_currency" class="form-control" value="{{ $pay['paypal_currency']->value ?? 'GBP' }}" placeholder="GBP">
      </div>

      <div style="background:#f0f4ff;border:1px solid #c7d2fe;border-radius:8px;padding:12px 16px;font-size:12px;color:#3730a3;margin-top:8px">
        <strong>Note:</strong> Get your PayPal credentials from <a href="https://developer.paypal.com" target="_blank" style="color:#1e3a6e;font-weight:700">developer.paypal.com</a>. Use Sandbox mode for testing.
      </div>
    </div>
  </div>

</div>
</div>

{{-- ════════════════════════════════════ --}}
{{-- TAB: EMAIL --}}
{{-- ════════════════════════════════════ --}}
<div class="settings-panel" id="tab-email">
@php $em = collect($grouped['email'] ?? [])->keyBy('key'); @endphp
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
  <div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-envelope" style="color:#1e3a6e;margin-right:6px"></i>Email Configuration</h3></div>
    <div style="padding:20px">
      <div class="form-group"><label>From Name</label><input type="text" name="email_from_name" class="form-control" value="{{ $em['email_from_name']->value ?? 'London InstantPrint' }}"></div>
      <div class="form-group"><label>From Email</label><input type="email" name="email_from_address" class="form-control" value="{{ $em['email_from_address']->value ?? '' }}"></div>
      <div class="form-group"><label>Reply-To Email</label><input type="email" name="email_reply_to" class="form-control" value="{{ $em['email_reply_to']->value ?? '' }}"></div>
    </div>
  </div>
  <div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-file-alt" style="color:#1e3a6e;margin-right:6px"></i>Email Templates</h3></div>
    <div style="padding:20px">
      <div class="form-group"><label>Order Confirmation — Subject</label><input type="text" name="email_order_confirm_subject" class="form-control" value="{{ $em['email_order_confirm_subject']->value ?? 'Order Confirmed' }}"><div style="font-size:10px;color:#aaa;margin-top:3px">Use @verbatim{{ order_ref }}@endverbatim for order reference</div></div>
      <div class="form-group"><label>Order Confirmation — Header Text</label><input type="text" name="email_order_confirm_header" class="form-control" value="{{ $em['email_order_confirm_header']->value ?? 'Thank you for your order!' }}"></div>
      <div class="form-group"><label>Order Confirmation — Footer Text</label><textarea name="email_order_confirm_footer" class="form-control" rows="2">{{ $em['email_order_confirm_footer']->value ?? '' }}</textarea></div>
      <div class="form-group"><label>Status Update — Subject</label><input type="text" name="email_status_update_subject" class="form-control" value="{{ $em['email_status_update_subject']->value ?? 'Order Update' }}"></div>
    </div>
  </div>
</div>
</div>

{{-- SAVE BUTTON --}}
<div style="position:sticky;bottom:0;background:#fff;padding:16px 0;border-top:1px solid #eee;margin-top:24px;display:flex;gap:12px;align-items:center;z-index:10">
  <button type="submit" class="btn-yellow" style="padding:13px 36px;font-size:15px">
    <i class="fas fa-save"></i> Save All Settings
  </button>
  <span style="font-size:13px;color:#aaa">Changes apply immediately on the website.</span>
</div>

</form>
@endsection

@section('scripts')
<script>
function showTab(name) {
    document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.stab').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-'+name).classList.add('active');
    event.target.classList.add('active');
}

function showPageTab(id, btn) {
    // only panels inside #tab-pages
    document.querySelectorAll('#tab-pages .settings-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('#tab-pages .stab').forEach(b => b.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    btn.classList.add('active');
}

function syncColor(picker, fieldId) {
    const field = document.getElementById(fieldId);
    if (field) field.value = picker.value;
}

function updateHeroPreview() {
    const l1 = document.querySelector('[name=hero_title_line1]')?.value || '';
    const l2 = document.querySelector('[name=hero_title_line2]')?.value || '';
    const sub = document.querySelector('[name=hero_subtitle]')?.value || '';
    const btn = document.querySelector('[name=hero_btn_text]')?.value || '';
    const prev = document.getElementById('prev_hero_title');
    const subPrev = document.getElementById('prev_hero_sub');
    const btnPrev = document.getElementById('prev_hero_btn');
    if (prev) prev.innerHTML = l1 + '<br>' + l2;
    if (subPrev) subPrev.textContent = sub;
    if (btnPrev) btnPrev.textContent = btn;
}

function previewImg(input, imgId, wrapperId) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById(imgId);
        if (img) { img.src = e.target.result; img.style.display = 'block'; }
    };
    reader.readAsDataURL(file);
}

// Init preview on load
document.addEventListener('DOMContentLoaded', function() {});
</script>
@endsection
