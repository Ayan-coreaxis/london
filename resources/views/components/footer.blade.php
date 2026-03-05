@php
    try { $fs = \App\Models\SiteSetting::allKeyed(); } catch(\Exception $e) { $fs = []; }
    $f_hours     = $fs['footer_hours']     ?? 'Monday to Friday 9am - 5:30pm';
    $f_phone     = $fs['footer_phone']     ?? '0114 294 5026';
    $f_email     = $fs['footer_email']     ?? 'sales@Londoninstantprint.co.uk';
    $f_address   = $fs['footer_address']   ?? "Unit A Brookfields Park, Manvers Way,\nManvers, Rotherham, S63 5DR";
    $f_facebook  = $fs['footer_facebook']  ?? '#';
    $f_linkedin  = $fs['footer_linkedin']  ?? '#';
    $f_twitter   = $fs['footer_twitter']   ?? '#';
    $f_copyright = $fs['footer_copyright'] ?? 'London InstantPrint';
    $f_bg        = $fs['color_footer_bg']  ?? '#1e3a6e';
@endphp

<section class="bottom"></section>
<footer style="background:{{ $f_bg }}">
    <div class="footer-grid">

        {{-- Col 1: Contact --}}
        <div class="footer-col">
            <h4>Get in touch</h4>
            <address>
                {{ $f_hours }}<br>
                <a href="tel:{{ preg_replace('/\s+/','',$f_phone) }}" style="color:inherit;text-decoration:none">{{ $f_phone }}</a><br>
                <a href="mailto:{{ $f_email }}" style="color:inherit;text-decoration:none">{{ $f_email }}</a><br><br>
                {!! nl2br(e($f_address)) !!}
            </address>
            <div class="social-row">
                <a class="social-btn" href="{{ $f_facebook }}" aria-label="Facebook" target="_blank" rel="noopener">f</a>
                <a class="social-btn" href="{{ $f_linkedin }}" aria-label="LinkedIn"  target="_blank" rel="noopener">in</a>
                <a class="social-btn" href="{{ $f_twitter  }}" aria-label="Twitter/X" target="_blank" rel="noopener">𝕏</a>
            </div>
        </div>

        {{-- Col 2: Payment --}}
        <div class="footer-col">
            <h4>Payment Options</h4>
            <div class="payment-grid">
                <span class="pay-chip">Visa</span>
                <span class="pay-chip">Visa Debit</span>
                <span class="pay-chip">Master Card</span>
                <span class="pay-chip">Maestro</span>
                <span class="pay-chip">American Express</span>
                <span class="pay-chip">PayPal</span>
            </div>
        </div>

        {{-- Col 3: Links --}}
        <div class="footer-col">
            <h4>Useful Links</h4>
            <a href="{{ route('login') }}">Login / Register</a>
            <a href="{{ route('about') }}">About Us</a>
            <a href="#">Sustainable Printing</a>
            <a href="#">FAQ's</a>
            <a href="{{ route('terms') }}">Terms &amp; Conditions</a>
            <a href="{{ route('privacy') }}">Privacy Policy</a>
            <a href="{{ route('products') }}">All Products</a>
            <a href="#">Request a Quote</a>
            <a href="{{ route('admin.login') }}">Admin Login</a>
        </div>

    </div>

    {{-- Bottom Bar --}}
    <div class="footer-bottom">
        <div class="footer-logo">
            @php $logo = $fs['header_logo'] ?? ''; @endphp
            @if($logo)
                <img src="{{ asset($logo) }}" alt="{{ $f_copyright }}" style="height:48px;width:auto;" onerror="this.style.display='none'">
            @else
                <img src="{{ asset('images/logo.png') }}" alt="{{ $f_copyright }}" style="height:48px;width:auto;">
            @endif
        </div>
        <p class="footer-copy">&copy; Copyright {{ date('Y') }} {{ $f_copyright }}</p>
    </div>
</footer>
