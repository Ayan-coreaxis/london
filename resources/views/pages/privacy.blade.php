{{-- resources/views/pages/privacy.blade.php --}}
@extends('layouts.app')

@section('title', 'Privacy Policy – London InstantPrint')
@section('meta_description', 'Read London InstantPrint\'s Privacy Policy. We are committed to protecting your personal data in line with UK GDPR.')

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
    background: #f0f5ff;
    border-left: 5px solid var(--navy);
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
    background: var(--navy);
    color: #fff;
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
}
</style>
@endsection

@section('content')

@php
    try { $s = \App\Models\SiteSetting::allKeyed(); } catch(\Exception $e) { $s = []; }
    $company = $s['privacy_company_name'] ?? 'London InstantPrint Ltd';
    $updated = $s['privacy_last_updated'] ?? '1 March 2025';
    $address = $s['privacy_company_address'] ?? 'Unit A Brookfields Park, Manvers Way, Manvers, Rotherham, S63 5DR';
    $email   = $s['privacy_contact_email'] ?? 'privacy@londoninstantprint.co.uk';
@endphp

{{-- BREADCRUMB --}}
<div class="page-breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <span>›</span>
    <strong style="color:#333">Privacy Policy</strong>
</div>

{{-- HERO --}}
<div class="legal-hero">
    <h1>Privacy Policy</h1>
    <div class="updated">Last updated: {{ $updated }}</div>
</div>

<div class="legal-wrap">

    {{-- Intro --}}
    <div class="legal-intro">
        {{ $s['privacy_intro'] ?? 'We are committed to protecting your personal information and your right to privacy. This policy explains what information we collect, how we use it, and what rights you have in relation to it.' }}
    </div>

    {{-- 1. Who We Are --}}
    <div class="legal-section" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">1</span> Who We Are</h2>
        <p><strong>{{ $company }}</strong> is the data controller responsible for your personal data. We operate the website <strong>londoninstantprint.co.uk</strong> and process your data in accordance with the UK General Data Protection Regulation (UK GDPR) and the Data Protection Act 2018.</p>
        <p><strong>Registered address:</strong> {{ $address }}</p>
    </div>

    {{-- 2. What Data We Collect --}}
    <div class="legal-section" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">2</span> What Data We Collect</h2>
        <p>We may collect and process the following categories of personal data:</p>
        <ul>
            <li><strong>Identity data:</strong> first name, last name, company name</li>
            <li><strong>Contact data:</strong> billing address, delivery address, email address, phone number</li>
            <li><strong>Transaction data:</strong> details about payments and the products you have ordered</li>
            <li><strong>Technical data:</strong> IP address, browser type, time zone, operating system, and other technology on the devices you use to access our website</li>
            <li><strong>Usage data:</strong> information about how you use our website and services</li>
            <li><strong>Marketing data:</strong> your preferences in receiving marketing from us</li>
            <li><strong>Artwork/file data:</strong> print-ready files and artwork you upload to place an order</li>
        </ul>
    </div>

    {{-- 3. How We Collect Data --}}
    <div class="legal-section" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">3</span> How We Collect Your Data</h2>
        <ul>
            <li><strong>Direct interactions:</strong> when you register an account, place an order, contact us, or subscribe to our newsletter</li>
            <li><strong>Automated technologies:</strong> cookies and similar tracking technologies as you interact with our website</li>
            <li><strong>Third parties:</strong> analytics providers (e.g. Google Analytics), payment processors, and delivery partners</li>
        </ul>
    </div>

    {{-- 4. How We Use Your Data --}}
    <div class="legal-section" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">4</span> How We Use Your Data</h2>
        <p>We use your personal data only where the law allows. Most commonly we use it to:</p>
        <ul>
            <li>Process and fulfil your orders (including production and delivery)</li>
            <li>Manage your account and relationship with us</li>
            <li>Process payments and prevent fraud</li>
            <li>Notify you about changes to our services or your orders</li>
            <li>Improve our website, products, and services</li>
            <li>Send marketing communications where you have opted in (you can opt out at any time)</li>
            <li>Comply with our legal obligations</li>
        </ul>
    </div>

    {{-- 5. Data Retention --}}
    <div class="legal-section" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">5</span> How Long We Keep Your Data</h2>
        <p>We retain personal data only as long as necessary for the purposes described in this policy, or as required by law. In general:</p>
        <ul>
            <li>Order and transaction records: 7 years (for accounting and legal compliance)</li>
            <li>Account data: for as long as your account is active, plus 2 years after closure</li>
            <li>Marketing preferences: until you withdraw consent</li>
            <li>Uploaded artwork files: 90 days after order completion, then securely deleted</li>
        </ul>
    </div>

    {{-- 6. Data Sharing --}}
    <div class="legal-section" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">6</span> Who We Share Your Data With</h2>
        <p>We do not sell your personal data. We share it only with:</p>
        <ul>
            <li><strong>Payment processors</strong> (e.g. Stripe, PayPal) to handle transactions securely</li>
            <li><strong>Delivery partners</strong> to fulfil your orders</li>
            <li><strong>IT and hosting providers</strong> who support our website and systems</li>
            <li><strong>Analytics providers</strong> (e.g. Google Analytics) on an anonymised basis</li>
            <li><strong>Legal authorities</strong> if required by law or court order</li>
        </ul>
        <p>All third-party processors are required to handle your data securely and in accordance with UK GDPR.</p>
    </div>

    {{-- 7. Cookies --}}
    <div class="legal-section" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">7</span> Cookies</h2>
        <p>We use cookies and similar technologies to enhance your browsing experience, analyse site traffic, and personalise content. These include:</p>
        <ul>
            <li><strong>Essential cookies:</strong> required for the website to function (e.g. session management, shopping cart)</li>
            <li><strong>Analytics cookies:</strong> help us understand how visitors use our site (e.g. Google Analytics)</li>
            <li><strong>Marketing cookies:</strong> used to deliver relevant advertising (only where you have consented)</li>
        </ul>
        <p>You can manage your cookie preferences through your browser settings at any time.</p>
    </div>

    {{-- 8. Your Rights --}}
    <div class="legal-section" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">8</span> Your Rights</h2>
        <p>Under UK GDPR, you have the following rights regarding your personal data:</p>
        <ul>
            <li><strong>Right of access:</strong> request a copy of the data we hold about you</li>
            <li><strong>Right to rectification:</strong> request correction of inaccurate data</li>
            <li><strong>Right to erasure:</strong> request deletion of your data (subject to legal obligations)</li>
            <li><strong>Right to restrict processing:</strong> ask us to pause processing in certain circumstances</li>
            <li><strong>Right to data portability:</strong> receive your data in a structured, machine-readable format</li>
            <li><strong>Right to object:</strong> object to processing based on legitimate interests or for direct marketing</li>
        </ul>
        <p>To exercise any of these rights, please contact us at <a href="mailto:{{ $email }}" style="color:var(--navy);">{{ $email }}</a>. We will respond within 30 days.</p>
        <p>You also have the right to lodge a complaint with the <strong>Information Commissioner's Office (ICO)</strong> at <a href="https://ico.org.uk" target="_blank" style="color:var(--navy);">ico.org.uk</a>.</p>
    </div>

    {{-- 9. Security --}}
    <div class="legal-section" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">9</span> Data Security</h2>
        <p>We have put in place appropriate security measures to prevent your personal data from being accidentally lost, used, or accessed in an unauthorised way. These include SSL encryption, access controls, and regular security reviews. In the event of a data breach that affects your rights, we will notify you and the ICO as required by law.</p>
    </div>

    {{-- 10. Changes --}}
    <div class="legal-section" data-aos="fade-up" data-aos-duration="500">
        <h2><span class="sec-num">10</span> Changes to This Policy</h2>
        <p>We may update this Privacy Policy from time to time. The latest version will always be available on this page. We will notify you of significant changes by email or by a prominent notice on our website.</p>
    </div>

    {{-- Contact Box --}}
    <div class="legal-contact-box" data-aos="fade-up" data-aos-duration="600">
        <h3>Questions About This Policy?</h3>
        <p>If you have any questions, concerns, or requests relating to your personal data, please contact our Data Protection Officer:</p>
        <p><strong style="color:#fff;">{{ $company }}</strong></p>
        <p>{{ $address }}</p>
        <p>Email: <a href="mailto:{{ $email }}">{{ $email }}</a></p>
    </div>

</div>

@endsection
