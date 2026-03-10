// London InstantPrint — Main JS

document.addEventListener('DOMContentLoaded', function () {

    // =============================
    // AOS Init
    // =============================
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 650,
            once: true,
            easing: 'ease-out-quad',
            offset: 60
        });
    }

    // =============================
    // Promo Bar: Copy Code on Click
    // =============================
    const promoBtn = document.querySelector('.promo-code-btn');
    if (promoBtn) {
        promoBtn.addEventListener('click', function () {
            if (navigator.clipboard) {
                navigator.clipboard.writeText('WELCOME10').then(() => {
                    const original = promoBtn.textContent;
                    promoBtn.textContent = '✓ Copied!';
                    promoBtn.style.background = '#222';
                    promoBtn.style.color = '#f5c518';
                    setTimeout(() => {
                        promoBtn.textContent = original;
                        promoBtn.style.background = '';
                        promoBtn.style.color = '';
                    }, 2000);
                });
            }
        });
    }

    // =============================
    // Sticky Header Shadow on Scroll
    // =============================
    const header = document.querySelector('header');
    if (header) {
        window.addEventListener('scroll', () => {
            header.style.boxShadow = window.scrollY > 10
                ? '0 2px 12px rgba(0,0,0,0.1)'
                : '0 1px 4px rgba(0,0,0,0.05)';
        });
    }

});