{{-- resources/views/pages/terms.blade.php --}}
@extends('layouts.app')

@section('title', 'Terms & Conditions – London InstantPrint')
@section('meta_description', 'Read the Terms & Conditions for London InstantPrint. Everything you need to know about ordering, delivery, artwork, and returns.')

@section('styles')
<style>
.legal-hero {
    background: var(--navy);
    color: #fff;
    padding: 52px 40px 44px;
    text-align: center;
}
.legal-hero h1 {
    font-family: var(--font);
    font-size: clamp(28px, 4vw, 48px);
    font-weight: 900;
    margin-bottom: 10px;
}
.legal-hero .updated {
    font-size: 13px;
    color: rgba(255,255,255,.6);
    margin-top: 8px;
}
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
.legal-wrap {
    max-width: 860px;
    margin: 0 auto;
    padding: 56px 40px 72px;
}
.legal-intro {
    background: #fff9e6;
    border-left: 5px solid var(--yellow);
    border-radius: 0 10px 10px 0;
    padding: 20px 24px;
    font-size: 15px;
    color: #444;
    line-height: 1.75;
    margin-bottom: 40px;
}
.legal-section {
    margin-bottom: 40px;
}
.legal-section h2 {
    font-family: var(--font);
    font-size: 20px;
    font-weight: 800;
    color: var(--navy);
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.legal-section h2 .sec-num {
    background: var(--yellow);
    color: var(--navy);
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 800;
    flex-shrink: 0;
}
.legal-section p, .legal-section li {
    font-size: 15px;
    color: #444;
    line-height: 1.8;
    margin-bottom: 10px;
}
.legal-section ul, .legal-section ol {
    padding-left: 22px;
    margin-bottom: 14px;
}
.legal-section li { margin-bottom: 6px; }
.legal-section strong { color: #222; }
.toc-box {
    background: #f4f7fb;
    border: 1px solid #dce6f5;
    border-radius: 10px;
    padding: 24px 28px;
    margin-bottom: 40px;
}
.toc-box h3 {
    font-family: var(--font);
    font-size: 15px;
    font-weight: 800;
    color: var(--navy);
    margin-bottom: 14px;
}
.toc-box ol {
    padding-left: 20px;
    margin: 0;
    columns: 2;
    column-gap: 24px;
}
.toc-box li { font-size: 14px; margin-bottom: 8px; }
.toc-box a { color: var(--navy); text-decoration: none; }
.toc-box a:hover { text-decoration: underline; }
.legal-contact-box {
    background: var(--navy);
    color: #fff;
    border-radius: 12px;
    padding: 28px 32px;
    margin-top: 48px;
}
.legal-contact-box h3 {
    font-family: var(--font);
    font-size: 20px;
    font-weight: 800;
    margin-bottom: 12px;
}
.legal-contact-box p { font-size: 14px; color: rgba(255,255,255,.8); margin-bottom: 6px; }
.legal-contact-box a { color: var(--yellow); text-decoration: none; }
@media (max-width: 768px) {
    .legal-wrap { padding: 36px 20px 56px; }
    .legal-hero { padding: 40px 20px 36px; }
    .page-breadcrumb { padding: 10px 20px; }
    .toc-box ol { columns: 1; }
}
</style>
@endsection

@section('content')

@php
    try { $s = \App\Models\SiteSetting::allKeyed(); } catch(\Exception $e) { $s = []; }
    $company = $s['terms_company_name'] ?? 'London InstantPrint Ltd';
    $updated = $s['terms_last_updated'] ?? '1 March 2025';
    $email   = $s['footer_email'] ?? 'sales@londoninstantprint.co.uk';
    $phone   = $s['footer_phone'] ?? '0114 294 5026';
    $address = $s['footer_address'] ?? 'Unit A Brookfields Park, Manvers Way, Manvers, Rotherham, S63 5DR';
@endphp

{{-- BREADCRUMB --}}
<div class="page-breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <span>›</span>
    <strong style="color:#333">Terms &amp; Conditions</strong>
</div>

{{-- HERO --}}
<div class="legal-hero">
    <h1>Terms &amp; Conditions</h1>
    <div class="updated">Last updated: {{ $updated }}</div>
</div>

<div class="legal-wrap">

    {{-- Intro --}}
    <div class="legal-intro">
        {{ $s['terms_intro'] ?? 'Please read these terms and conditions carefully before using our website or placing an order. By accessing our site or placing an order, you agree to be bound by these terms.' }}
    </div>

    {{-- Table of Contents --}}
    <div class="toc-box">
        <h3>Contents</h3>
        <ol>
            <li><a href="#t1">About Us</a></li>
            <li><a href="#t2">Use of Our Website</a></li>
            <li><a href="#t3">Placing an Order</a></li>
            <li><a href="#t4">Pricing &amp; Payment</a></li>
            <li><a href="#t5">Artwork &amp; Files</a></li>
            <li><a href="#t6">Production &amp; Turnaround</a></li>
            <li><a href="#t7">Delivery</a></li>
            <li><a href="#t8">Quality &amp; Colour</a></li>
            <li><a href="#t9">Returns &amp; Complaints</a></li>
            <li><a href="#t10">Intellectual Property</a></li>
            <li><a href="#t11">Limitation of Liability</a></li>
            <li><a href="#t12">Governing Law</a></li>
        </ol>
    </div>

    {{-- 1 --}}
    <div class="legal-section" id="t1" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">1</span> About Us</h2>
        <p><strong>{{ $company }}</strong> operates the website <strong>londoninstantprint.co.uk</strong>. We are a trade-only print supplier. By placing an order with us, you confirm that you are a business customer (trader) and not a consumer.</p>
        <p>Registered address: {{ $address }}</p>
        <p>Email: <a href="mailto:{{ $email }}" style="color:var(--navy);">{{ $email }}</a> &nbsp;|&nbsp; Phone: {{ $phone }}</p>
    </div>

    {{-- 2 --}}
    <div class="legal-section" id="t2" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">2</span> Use of Our Website</h2>
        <p>By accessing our website you agree to use it only for lawful purposes. You must not:</p>
        <ul>
            <li>Use the site in any way that violates applicable laws or regulations</li>
            <li>Transmit unsolicited commercial communications</li>
            <li>Attempt to gain unauthorised access to any part of the website or its systems</li>
            <li>Upload or submit content that infringes third-party intellectual property rights</li>
        </ul>
        <p>We reserve the right to suspend or terminate access to any user who breaches these conditions.</p>
    </div>

    {{-- 3 --}}
    <div class="legal-section" id="t3" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">3</span> Placing an Order</h2>
        <p>All orders placed through our website constitute an offer to purchase our products. An order is only accepted when we send you an <strong>order confirmation email</strong>. We reserve the right to decline any order at our discretion.</p>
        <ul>
            <li>You must be 18 or over to place an order</li>
            <li>You must be trading as a business (sole trader, partnership, limited company, or other entity)</li>
            <li>All orders are subject to artwork approval by our pre-press team</li>
            <li>Production begins only after artwork has been approved and payment has cleared</li>
        </ul>
    </div>

    {{-- 4 --}}
    <div class="legal-section" id="t4" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">4</span> Pricing &amp; Payment</h2>
        <p>All prices shown on our website are in <strong>GBP (£)</strong> and are <strong>exclusive of VAT</strong> unless stated otherwise. The applicable VAT rate will be added at checkout.</p>
        <ul>
            <li>Prices are subject to change without notice, but confirmed order prices will not change</li>
            <li>Full payment is required before production commences</li>
            <li>We accept Visa, Visa Debit, Mastercard, Maestro, American Express, and PayPal</li>
            <li>We do not store card details — all payments are processed securely by our payment provider</li>
        </ul>
    </div>

    {{-- 5 --}}
    <div class="legal-section" id="t5" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">5</span> Artwork &amp; Files</h2>
        <p>You are responsible for supplying print-ready artwork. Our 30-point artwork check reviews files for common technical errors but does not check for spelling, grammar, or design accuracy.</p>
        <ul>
            <li>Files must be supplied to our specified dimensions and bleed requirements</li>
            <li>We accept PDF, AI, EPS, TIFF, and high-resolution JPEG/PNG formats</li>
            <li>Minimum resolution: 300 DPI at print size</li>
            <li>Images and fonts must be embedded or outlined</li>
            <li>Colours should be set to CMYK. RGB files will be converted and colour variance may occur</li>
            <li>We accept no liability for errors in content that you have approved (including proofs)</li>
            <li>By uploading artwork, you confirm that you own or have the right to use all content therein</li>
        </ul>
    </div>

    {{-- 6 --}}
    <div class="legal-section" id="t6" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">6</span> Production &amp; Turnaround</h2>
        <p>Production times begin once artwork has been approved and payment has been received. Standard turnaround times are indicated on each product page.</p>
        <ul>
            <li>Turnaround times are estimates, not guarantees, unless expressly stated in writing</li>
            <li>Orders placed before our daily cut-off time (usually 5:00 PM) are processed that working day</li>
            <li>Working days exclude weekends and UK public holidays</li>
            <li>We are not liable for production delays caused by artwork rejection or resubmission</li>
        </ul>
    </div>

    {{-- 7 --}}
    <div class="legal-section" id="t7" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">7</span> Delivery</h2>
        <p>We offer free next-day delivery as standard on all orders to mainland UK addresses.</p>
        <ul>
            <li>Deliveries are made by our courier partners Monday–Friday</li>
            <li>Delivery addresses must be in mainland UK. Surcharges may apply for Scottish Highlands, islands, and Northern Ireland</li>
            <li>We are not responsible for delays caused by courier services once your order has been dispatched</li>
            <li>Risk in goods passes to you upon delivery. Title passes upon receipt of full payment</li>
            <li>If a delivery is missed, the courier will attempt redelivery or leave a card with collection instructions</li>
        </ul>
    </div>

    {{-- 8 --}}
    <div class="legal-section" id="t8" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">8</span> Quality &amp; Colour Variance</h2>
        <p>We produce all print to industry-standard tolerances. Please note:</p>
        <ul>
            <li>A colour variance of up to <strong>±15%</strong> from screen previews is within industry-accepted tolerance and does not constitute a defect</li>
            <li>Minor variation in cutting, folding, and finishing (up to ±2 mm) is standard and accepted</li>
            <li>Paper stock colours (e.g. Uncoated White vs Silk) will affect the final appearance of printed colours</li>
            <li>Over-printing on recycled or uncoated stocks may result in reduced vibrancy</li>
        </ul>
    </div>

    {{-- 9 --}}
    <div class="legal-section" id="t9" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">9</span> Returns, Reprints &amp; Complaints</h2>
        <p>As all products are custom-produced to your specification, we do not accept returns or offer refunds unless the product is defective or significantly different from what was ordered.</p>
        <ul>
            <li>Complaints must be raised within <strong>7 days</strong> of delivery, accompanied by clear photographic evidence</li>
            <li>If we determine that a fault is our responsibility, we will offer a reprint or credit at our discretion</li>
            <li>We do not accept responsibility for errors in approved artwork, including spelling, layout, or colour choices made by the customer</li>
            <li>To raise a complaint, contact us at <a href="mailto:{{ $email }}" style="color:var(--navy);">{{ $email }}</a></li>
        </ul>
    </div>

    {{-- 10 --}}
    <div class="legal-section" id="t10" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">10</span> Intellectual Property</h2>
        <p>All content on this website — including text, graphics, logos, and product images — is owned by or licensed to {{ $company }} and is protected by UK and international copyright law.</p>
        <p>By submitting artwork, you grant us a limited licence to reproduce that artwork solely for the purpose of fulfilling your order. You warrant that you have the right to reproduce all content in your artwork.</p>
    </div>

    {{-- 11 --}}
    <div class="legal-section" id="t11" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">11</span> Limitation of Liability</h2>
        <p>To the fullest extent permitted by law, our total liability to you in connection with any order shall not exceed the value of that order.</p>
        <p>We shall not be liable for any:</p>
        <ul>
            <li>Indirect or consequential loss</li>
            <li>Loss of profits, revenue, business, or goodwill</li>
            <li>Loss arising from third-party courier delays</li>
            <li>Loss arising from approved artwork errors</li>
        </ul>
        <p>Nothing in these terms excludes or limits our liability for death or personal injury caused by negligence, fraud, or any other matter that cannot be excluded by law.</p>
    </div>

    {{-- 12 --}}
    <div class="legal-section" id="t12" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">12</span> Governing Law</h2>
        <p>These terms and conditions are governed by and construed in accordance with the laws of <strong>England and Wales</strong>. Any disputes shall be subject to the exclusive jurisdiction of the courts of England and Wales.</p>
        <p>We reserve the right to update these terms at any time. The current version will always be published on this page.</p>
    </div>

    {{-- Contact --}}
    <div class="legal-contact-box" data-aos="fade-up" data-aos-duration="600">
        <h3>Need Help? Contact Us</h3>
        <p>If you have any questions about these terms, please get in touch:</p>
        <p><strong style="color:#fff;">{{ $company }}</strong></p>
        <p>{{ $address }}</p>
        <p>Email: <a href="mailto:{{ $email }}">{{ $email }}</a> &nbsp;|&nbsp; Phone: {{ $phone }}</p>
    </div>

</div>

@endsection
