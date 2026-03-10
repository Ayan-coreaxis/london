<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welcome to London InstantPrint</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&family=Open+Sans:wght@400;600&display=swap');
  * { box-sizing: border-box; }
  body { margin:0; padding:0; background:#f0f2f5; font-family:'Open Sans',Arial,sans-serif; }
  .outer { padding: 32px 16px; }
  .wrap { max-width:620px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 32px rgba(0,0,0,0.10); }

  /* HERO HEADER */
  .hero {
    background: linear-gradient(135deg, #1e3a6e 0%, #2a5298 60%, #1a3060 100%);
    padding: 48px 40px 40px;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 250px; height: 250px;
    background: rgba(245,200,0,0.08);
    border-radius: 50%;
  }
  .hero::after {
    content: '';
    position: absolute;
    bottom: -80px; left: -40px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
  }
  .hero-logo {
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 900;
    font-size: 28px;
    letter-spacing: -0.5px;
    display: block;
    margin-bottom: 24px;
    position: relative;
    z-index: 2;
  }
  .logo-w { color: #fff; }
  .logo-y { color: #f5c800; }
  .logo-dot {
    display: inline-block;
    width: 7px; height: 7px;
    background: #f5c800;
    border-radius: 50%;
    margin: 0 2px 2px;
    vertical-align: middle;
  }
  .hero-emoji {
    font-size: 52px;
    display: block;
    margin-bottom: 16px;
    position: relative;
    z-index: 2;
  }
  .hero-title {
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 900;
    font-size: 26px;
    color: #fff;
    margin: 0 0 8px;
    position: relative;
    z-index: 2;
  }
  .hero-sub {
    color: rgba(255,255,255,0.65);
    font-size: 14px;
    margin: 0;
    position: relative;
    z-index: 2;
  }
  .accent-bar { height: 5px; background: linear-gradient(90deg, #f5c800 0%, #ffdc50 50%, #f5c800 100%); }

  /* BODY */
  .body { padding: 36px 40px; }
  .greeting { font-size: 16px; color: #1a1a1a; font-weight: 600; margin: 0 0 6px; }
  .intro { font-size: 14px; color: #666; line-height: 1.8; margin: 0 0 32px; }

  /* Features grid */
  .features {
    display: table;
    width: 100%;
    margin: 0 0 32px;
    border-collapse: separate;
    border-spacing: 10px;
  }
  .features-row { display: table-row; }
  .feature {
    display: table-cell;
    width: 33.33%;
    background: #f7f8fc;
    border-radius: 12px;
    padding: 20px 14px;
    text-align: center;
    border: 1.5px solid #eef0f8;
    vertical-align: top;
  }
  .feature-icon { font-size: 30px; display: block; margin-bottom: 8px; }
  .feature-title {
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 700;
    font-size: 12px;
    color: #1e3a6e;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0 0 4px;
  }
  .feature-desc { font-size: 11px; color: #999; margin: 0; line-height: 1.5; }

  /* Benefits list */
  .benefits-title {
    font-family: 'Montserrat', Arial, sans-serif;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: #1e3a6e;
    margin: 0 0 14px;
    padding-bottom: 8px;
    border-bottom: 2px solid #f0f0f0;
  }
  .benefit-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f5f5f5;
    font-size: 13px;
    color: #555;
    line-height: 1.5;
  }
  .benefit-item:last-child { border-bottom: none; }
  .benefit-check {
    width: 22px; height: 22px;
    background: #1e3a6e;
    border-radius: 50%;
    color: #fff;
    font-size: 11px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
  }

  /* CTA */
  .cta-wrap {
    background: linear-gradient(135deg, #f0f4ff, #e8eeff);
    border-radius: 12px;
    padding: 28px;
    text-align: center;
    margin: 32px 0;
    border: 1.5px solid #d8e4ff;
  }
  .cta-wrap h3 {
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 900;
    font-size: 18px;
    color: #1e3a6e;
    margin: 0 0 6px;
  }
  .cta-wrap p { font-size: 13px; color: #777; margin: 0 0 18px; }
  .btn {
    display: inline-block;
    background: linear-gradient(135deg, #f5c800, #e8b800);
    color: #1a1a1a !important;
    text-decoration: none;
    padding: 15px 40px;
    border-radius: 8px;
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 900;
    font-size: 14px;
    letter-spacing: 0.3px;
    box-shadow: 0 4px 16px rgba(245,200,0,0.4);
  }

  /* FOOTER */
  .footer { background: #1e3a6e; padding: 28px 40px; text-align: center; }
  .footer-logo { font-family: 'Montserrat', Arial, sans-serif; font-weight: 900; font-size: 16px; margin-bottom: 10px; }
  .f-yellow { color: #f5c800; }
  .f-white { color: #fff; }
  .footer-links a { color: rgba(255,255,255,0.55); text-decoration: none; font-size: 12px; margin: 0 8px; }
  .footer-copy { color: rgba(255,255,255,0.35); font-size: 11px; margin: 10px 0 0; }
</style>
</head>
<body>
<div class="outer">
<div class="wrap">

  <!-- HERO -->
  <div class="hero">
    <span class="hero-logo">
      <span class="logo-w">London</span><span class="logo-dot"></span><span class="logo-y">Instant</span><span class="logo-w">Print</span>
    </span>
    <span class="hero-emoji">🎉</span>
    <h1 class="hero-title">Welcome Aboard!</h1>
    <p class="hero-sub">Your account has been created successfully</p>
  </div>
  <div class="accent-bar"></div>

  <!-- BODY -->
  <div class="body">

    <p class="greeting">Hi {{ $user->name }},</p>
    <p class="intro">
      Welcome to London InstantPrint — London's trusted printing partner! We're thrilled to have you join our community. Your account is all set up and ready to go.
    </p>

    <!-- Features -->
    <table class="features">
      <tr class="features-row">
        <td class="feature">
          <span class="feature-icon">🚀</span>
          <p class="feature-title">Fast Delivery</p>
          <p class="feature-desc">Next day delivery across the UK</p>
        </td>
        <td class="feature">
          <span class="feature-icon">🎨</span>
          <p class="feature-title">Quality Prints</p>
          <p class="feature-desc">Premium materials & sharp results</p>
        </td>
        <td class="feature">
          <span class="feature-icon">💬</span>
          <p class="feature-title">UK Support</p>
          <p class="feature-desc">Expert team always on hand</p>
        </td>
      </tr>
    </table>

    <!-- Benefits -->
    <p class="benefits-title">What You Get With Your Account</p>
    <div class="benefit-item">
      <div class="benefit-check">✓</div>
      <span><strong>Full order history</strong> — track and reorder past prints with ease</span>
    </div>
    <div class="benefit-item">
      <div class="benefit-check">✓</div>
      <span><strong>Faster checkout</strong> — save your addresses and billing details</span>
    </div>
    <div class="benefit-item">
      <div class="benefit-check">✓</div>
      <span><strong>Exclusive member offers</strong> — get early access to deals and discounts</span>
    </div>
    <div class="benefit-item">
      <div class="benefit-check">✓</div>
      <span><strong>Real-time order tracking</strong> — know exactly where your print is at all times</span>
    </div>

    <!-- CTA -->
    <div class="cta-wrap">
      <h3>Ready to Print?</h3>
      <p>Browse our full range of products and place your first order today</p>
      <a href="{{ url('/all-products') }}" class="btn">Browse All Products →</a>
    </div>

    <p style="font-size:13px;color:#999;text-align:center;margin:0;">
      Need help getting started? Email us at
      <a href="mailto:info@londoninstantprint.co.uk" style="color:#1e3a6e;font-weight:600;">info@londoninstantprint.co.uk</a>
    </p>
  </div>

  <!-- FOOTER -->
  <div class="footer">
    <div class="footer-logo">
      <span class="f-white">London</span><span class="f-yellow">Instant</span><span class="f-white">Print</span>
    </div>
    <div class="footer-links" style="margin:10px 0;">
      <a href="{{ url('/') }}">Website</a>
      <a href="{{ url('/all-products') }}">Products</a>
      <a href="{{ url('/contact-us') }}">Contact</a>
      <a href="{{ url('/privacy-policy') }}">Privacy</a>
    </div>
    <p class="footer-copy">&copy; {{ date('Y') }} London InstantPrint Ltd. All rights reserved.<br>
    London, United Kingdom &nbsp;|&nbsp; info@londoninstantprint.co.uk</p>
  </div>

</div>
</div>
</body>
</html>