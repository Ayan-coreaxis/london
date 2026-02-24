{{-- resources/views/components/footer.blade.php --}}
<footer>
    <div class="footer-grid">

        {{-- Col 1: Contact --}}
        <div class="footer-col">
            <h4>Get in touch</h4>
            <address>
                Monday to Friday 9am - 5:30pm<br>
                0114 294 5026<br>
                sales@Londoninstantprint.co.uk<br><br>
                Unit A Brookfields Park, Manvers Way,<br>
                Manvers, Rotherham, S63 5DR
            </address>
            <div class="social-row">
                <a class="social-btn" href="#" aria-label="Facebook">f</a>
                <a class="social-btn" href="#" aria-label="LinkedIn">in</a>
                <a class="social-btn" href="#" aria-label="Twitter/X">𝕏</a>
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
            <a href="#">Login / Register</a>
            <a href="#">About Us</a>
            <a href="#">Sustainable Printing</a>
            <a href="#">FAQ's</a>
            <a href="#">Terms &amp; Conditions</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Site Map</a>
            <a href="#">Request a Quote</a>
        </div>

    </div>

    {{-- Bottom Bar --}}
    <div class="footer-bottom">
        <div class="footer-logo">
            <img src="{{ asset('images/logo.png') }}" alt="London InstantPrint" style="height: 48px; width: auto;">
        </div>
        <p class="footer-copy">&copy; Copyright {{ date('Y') }}</p>
    </div>
</footer>
