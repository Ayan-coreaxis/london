<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Confirmed – London InstantPrint</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&family=Open+Sans:wght@400;600&display=swap');
  * { box-sizing: border-box; }
  body { margin:0; padding:0; background:#f0f2f5; font-family:'Open Sans',Arial,sans-serif; }

  .outer { padding: 32px 16px; background:#f0f2f5; }
  .wrap { max-width:620px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 32px rgba(0,0,0,0.10); }

  /* ── HEADER ── */
  .header {
    background: linear-gradient(135deg, #1e3a6e 0%, #2a5298 100%);
    padding: 0;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .header-top {
    padding: 32px 40px 24px;
    position: relative;
    z-index: 2;
  }
  .header::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
  }
  .header::after {
    content: '';
    position: absolute;
    bottom: -60px; left: -30px;
    width: 180px; height: 180px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
  }

  /* Logo */
  .logo-area { margin-bottom: 20px; }
  .logo-text {
    display: inline-block;
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 900;
    font-size: 26px;
    letter-spacing: -0.5px;
  }
  .logo-london { color: #ffffff; }
  .logo-instant { color: #f5c800; }
  .logo-print { color: #ffffff; }
  .logo-dot {
    display: inline-block;
    width: 8px; height: 8px;
    background: #f5c800;
    border-radius: 50%;
    margin: 0 2px 2px;
    vertical-align: middle;
  }

  .header-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 50px;
    padding: 10px 24px;
    margin-bottom: 8px;
  }
  .check-icon {
    width: 28px; height: 28px;
    background: #2ecc71;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
  }
  .header-badge-text {
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 700;
    font-size: 16px;
    color: #ffffff;
    letter-spacing: 0.3px;
  }
  .header-sub {
    color: rgba(255,255,255,0.70);
    font-size: 13px;
    margin: 8px 0 0;
  }

  /* Yellow accent bar */
  .accent-bar {
    height: 5px;
    background: linear-gradient(90deg, #f5c800 0%, #ffdc50 50%, #f5c800 100%);
  }

  /* ── BODY ── */
  .body { padding: 36px 40px; }

  .greeting { font-size: 16px; color: #1a1a1a; margin: 0 0 6px; font-weight: 600; }
  .intro { font-size: 14px; color: #666; line-height: 1.7; margin: 0 0 28px; }

  /* Ref box */
  .ref-box {
    background: linear-gradient(135deg, #f0f4ff 0%, #e8eeff 100%);
    border-left: 4px solid #1e3a6e;
    border-radius: 10px;
    padding: 18px 22px;
    margin: 0 0 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .ref-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #888; font-weight: 600; margin: 0 0 4px; }
  .ref-number { font-family: 'Montserrat', Arial, sans-serif; font-size: 22px; font-weight: 900; color: #1e3a6e; margin: 0; }
  .ref-icon { font-size: 32px; opacity: 0.3; }

  /* Section title */
  .section-title {
    font-family: 'Montserrat', Arial, sans-serif;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: #1e3a6e;
    margin: 0 0 12px;
    padding-bottom: 8px;
    border-bottom: 2px solid #f0f0f0;
  }

  /* Items table */
  .items-table { width: 100%; border-collapse: collapse; margin-bottom: 28px; }
  .items-table th {
    background: #f7f8fc;
    padding: 10px 12px;
    text-align: left;
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #888;
    border-bottom: 2px solid #eee;
  }
  .items-table td {
    padding: 12px 12px;
    border-bottom: 1px solid #f3f3f3;
    font-size: 13px;
    color: #333;
    vertical-align: top;
  }
  .items-table tr:last-child td { border-bottom: none; }
  .product-name { font-weight: 600; color: #1a1a1a; margin: 0 0 3px; }
  .product-opts { font-size: 12px; color: #999; margin: 0; line-height: 1.5; }
  .price-cell { text-align: right; font-weight: 700; color: #1e3a6e; white-space: nowrap; }

  /* Totals */
  .totals-wrap {
    background: #f7f8fc;
    border-radius: 10px;
    padding: 18px 20px;
    margin-bottom: 28px;
  }
  .totals-row {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    color: #666;
    padding: 5px 0;
  }
  .totals-row.divider { border-top: 1.5px solid #e0e0e0; margin-top: 8px; padding-top: 12px; }
  .totals-row.total-final { font-family: 'Montserrat', Arial, sans-serif; font-weight: 900; font-size: 17px; color: #1e3a6e; }
  .totals-row.discount { color: #2ecc71; }

  /* Address box */
  .address-box {
    background: #fff;
    border: 1.5px solid #e8e8e8;
    border-radius: 10px;
    padding: 18px 20px;
    margin-bottom: 28px;
    font-size: 13px;
    color: #555;
    line-height: 1.8;
  }

  /* CTA */
  .cta { text-align: center; margin: 28px 0; }
  .btn {
    display: inline-block;
    background: linear-gradient(135deg, #e8352a, #c0221a);
    color: #fff !important;
    text-decoration: none;
    padding: 15px 40px;
    border-radius: 8px;
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 700;
    font-size: 14px;
    letter-spacing: 0.3px;
    box-shadow: 0 4px 16px rgba(232,53,42,0.3);
  }

  /* Trust strip */
  .trust-strip {
    display: flex;
    border-top: 1.5px solid #f0f0f0;
    padding-top: 20px;
    margin-top: 20px;
    gap: 0;
  }
  .trust-item {
    flex: 1;
    text-align: center;
    padding: 0 10px;
    border-right: 1px solid #f0f0f0;
  }
  .trust-item:last-child { border-right: none; }
  .trust-icon { font-size: 22px; display: block; margin-bottom: 4px; }
  .trust-label { font-size: 11px; color: #999; font-weight: 600; font-family: 'Montserrat', Arial, sans-serif; }

  /* ── FOOTER ── */
  .footer {
    background: #1e3a6e;
    padding: 28px 40px;
    text-align: center;
  }
  .footer-logo {
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 900;
    font-size: 16px;
    margin-bottom: 10px;
  }
  .footer-logo .f-yellow { color: #f5c800; }
  .footer-logo .f-white { color: #fff; }
  .footer-links { margin: 10px 0; }
  .footer-links a {
    color: rgba(255,255,255,0.55);
    text-decoration: none;
    font-size: 12px;
    margin: 0 8px;
  }
  .footer-copy { color: rgba(255,255,255,0.35); font-size: 11px; margin: 10px 0 0; }
</style>
</head>
<body>
<div class="outer">
<div class="wrap">

  <!-- HEADER -->
  <div class="header">
    <div class="header-top">
      <div class="logo-area">
        <span class="logo-text">
          <span class="logo-london">London</span><span class="logo-dot"></span><span class="logo-instant">Instant</span><span class="logo-print">Print</span>
        </span>
      </div>
      <div class="header-badge">
        <span class="check-icon">✓</span>
        <span class="header-badge-text">Order Confirmed!</span>
      </div>
      <p class="header-sub">Your order has been received and is being processed</p>
    </div>
  </div>
  <div class="accent-bar"></div>

  <!-- BODY -->
  <div class="body">

    <p class="greeting">Hi {{ $order->first_name }},</p>
    <p class="intro">Thank you for choosing London InstantPrint! We've received your order and our team will begin processing it shortly. You'll receive another email once your order is in production.</p>

    <!-- Order Ref -->
    <div class="ref-box">
      <div>
        <p class="ref-label">Your Order Reference</p>
        <p class="ref-number">{{ $order->order_ref }}</p>
      </div>
      <div class="ref-icon">🧾</div>
    </div>

    <!-- Items -->
    <p class="section-title">Order Summary</p>
    <table class="items-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Options</th>
          <th style="text-align:center">Qty</th>
          <th style="text-align:right">Price</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $item)
        <tr>
          <td>
            <p class="product-name">{{ $item->product_name }}</p>
          </td>
          <td>
            @if($item->options)
              @php $opts = is_string($item->options) ? json_decode($item->options, true) : (array)$item->options; @endphp
              <p class="product-opts">
                @foreach($opts as $k => $v){{ ucfirst($k) }}: {{ $v }}<br>@endforeach
              </p>
            @else
              <span style="color:#ccc;">—</span>
            @endif
          </td>
          <td style="text-align:center;color:#666;">{{ $item->quantity }}</td>
          <td class="price-cell">£{{ number_format($item->line_total, 2) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Totals -->
    <div class="totals-wrap">
      <div class="totals-row">
        <span>Subtotal</span>
        <span>£{{ number_format($order->subtotal, 2) }}</span>
      </div>
      <div class="totals-row">
        <span>VAT (20%)</span>
        <span>£{{ number_format($order->vat, 2) }}</span>
      </div>
      <div class="totals-row">
        <span>Delivery</span>
        <span>{{ $order->delivery_cost > 0 ? '£'.number_format($order->delivery_cost, 2) : 'FREE' }}</span>
      </div>
      @if(!empty($order->discount) && $order->discount > 0)
      <div class="totals-row discount">
        <span>Discount</span>
        <span>-£{{ number_format($order->discount, 2) }}</span>
      </div>
      @endif
      <div class="totals-row divider total-final">
        <span>Total Paid</span>
        <span>£{{ number_format($order->total, 2) }}</span>
      </div>
    </div>

    <!-- Address -->
    <p class="section-title">📦 Delivery Address</p>
    <div class="address-box">
      <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
      @if($order->company)<span>{{ $order->company }}</span><br>@endif
      {{ $order->address_line1 }}<br>
      @if($order->address_line2){{ $order->address_line2 }}<br>@endif
      {{ $order->city }}, {{ $order->postcode }}<br>
      United Kingdom
    </div>

    <!-- CTA -->
    <div class="cta">
      <a href="{{ url('/account/order/' . $order->id) }}" class="btn">Track Your Order →</a>
    </div>

    <p style="font-size:13px;color:#999;text-align:center;margin:0;">
      Questions? Contact us at
      <a href="mailto:info@londoninstantprint.co.uk" style="color:#1e3a6e;font-weight:600;">info@londoninstantprint.co.uk</a>
    </p>

    <!-- Trust -->
    <div class="trust-strip">
      <div class="trust-item">
        <span class="trust-icon">🚀</span>
        <span class="trust-label">Fast Delivery</span>
      </div>
      <div class="trust-item">
        <span class="trust-icon">🎨</span>
        <span class="trust-label">Quality Prints</span>
      </div>
      <div class="trust-item">
        <span class="trust-icon">🔒</span>
        <span class="trust-label">Secure Payment</span>
      </div>
      <div class="trust-item">
        <span class="trust-icon">💬</span>
        <span class="trust-label">UK Support</span>
      </div>
    </div>

  </div>

  <!-- FOOTER -->
  <div class="footer">
    <div class="footer-logo">
      <span class="f-white">London</span><span class="f-yellow">Instant</span><span class="f-white">Print</span>
    </div>
    <div class="footer-links">
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