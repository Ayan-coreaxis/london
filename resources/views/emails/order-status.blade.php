<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Status Update – London InstantPrint</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&family=Open+Sans:wght@400;600&display=swap');
  * { box-sizing: border-box; }
  body { margin:0; padding:0; background:#f0f2f5; font-family:'Open Sans',Arial,sans-serif; }
  .outer { padding: 32px 16px; background:#f0f2f5; }
  .wrap { max-width:620px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 32px rgba(0,0,0,0.10); }

  /* HEADER */
  .header {
    background: linear-gradient(135deg, #1e3a6e 0%, #2a5298 100%);
    padding: 32px 40px 28px;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .header::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
  }
  .logo-text {
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 900;
    font-size: 22px;
    letter-spacing: -0.5px;
    display: block;
    margin-bottom: 18px;
  }
  .logo-white { color: #ffffff; }
  .logo-yellow { color: #f5c800; }
  .logo-dot {
    display: inline-block;
    width: 6px; height: 6px;
    background: #f5c800;
    border-radius: 50%;
    margin: 0 2px 1px;
    vertical-align: middle;
  }
  .header-title {
    color: #fff;
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 900;
    font-size: 20px;
    margin: 0 0 6px;
  }
  .header-sub { color: rgba(255,255,255,0.65); font-size: 13px; margin: 0; }
  .accent-bar { height: 5px; background: linear-gradient(90deg, #f5c800 0%, #ffdc50 50%, #f5c800 100%); }

  /* BODY */
  .body { padding: 36px 40px; }
  .intro { font-size: 14px; color: #555; line-height: 1.7; margin: 0 0 24px; }

  /* Ref box */
  .ref-box {
    background: #f7f8fc;
    border-radius: 10px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1.5px solid #e8eef8;
  }
  .ref-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #aaa; font-weight: 600; margin: 0 0 3px; }
  .ref-number { font-family: 'Montserrat', Arial, sans-serif; font-size: 20px; font-weight: 900; color: #1e3a6e; margin: 0; }

  /* Status display */
  .status-wrap { text-align: center; margin: 28px 0; }
  .status-label-sm { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #aaa; font-weight: 600; margin: 0 0 12px; }

  .status-badge {
    display: inline-block;
    padding: 12px 32px;
    border-radius: 50px;
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 700;
    font-size: 16px;
    letter-spacing: 0.3px;
  }
  .status-pending       { background:#fff8e6; color:#c87600; border:2px solid #ffd888; }
  .status-confirmed     { background:#e8f4ff; color:#0055aa; border:2px solid #99ccff; }
  .status-in_production { background:#f5f0ff; color:#6600cc; border:2px solid #cc99ff; }
  .status-dispatched    { background:#fff3e8; color:#cc4400; border:2px solid #ffbb88; }
  .status-completed     { background:#f0faf0; color:#1a7a1a; border:2px solid #88dd88; }
  .status-cancelled     { background:#fff3f2; color:#c0392b; border:2px solid #fbc9c6; }

  /* Progress timeline */
  .timeline { display: flex; align-items: center; justify-content: center; margin: 28px 0; gap: 0; }
  .tl-step { text-align: center; flex: 1; position: relative; }
  .tl-dot {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: #e8e8e8;
    color: #fff;
    font-size: 13px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 6px;
    position: relative;
    z-index: 2;
    font-weight: 700;
  }
  .tl-dot.done { background: #1e3a6e; }
  .tl-dot.active { background: #f5c800; color: #1a1a1a; box-shadow: 0 0 0 4px rgba(245,200,0,0.2); }
  .tl-dot.cancelled { background: #e8352a; }
  .tl-label { font-size: 10px; color: #aaa; font-weight: 600; font-family: 'Montserrat', Arial, sans-serif; text-transform: uppercase; letter-spacing: 0.5px; }
  .tl-label.done { color: #1e3a6e; }
  .tl-label.active { color: #c87600; }
  .tl-line {
    flex: 1;
    height: 3px;
    background: #e8e8e8;
    margin-bottom: 24px;
  }
  .tl-line.done { background: #1e3a6e; }

  /* Message box */
  .msg-box {
    background: #f7f8fc;
    border-radius: 10px;
    padding: 20px 22px;
    margin: 20px 0 28px;
    font-size: 14px;
    color: #444;
    line-height: 1.7;
    border-left: 4px solid #1e3a6e;
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
    box-shadow: 0 4px 16px rgba(232,53,42,0.3);
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

  <!-- HEADER -->
  <div class="header">
    <span class="logo-text">
      <span class="logo-white">London</span><span class="logo-dot"></span><span class="logo-yellow">Instant</span><span class="logo-white">Print</span>
    </span>
    <h1 class="header-title">Order Status Update</h1>
    <p class="header-sub">Your order has been updated</p>
  </div>
  <div class="accent-bar"></div>

  <!-- BODY -->
  <div class="body">

    <p class="intro">Hi <strong>{{ $order->first_name }}</strong>, we have an update on your order. Please see the details below.</p>

    <!-- Ref -->
    <div class="ref-box">
      <div>
        <p class="ref-label">Order Reference</p>
        <p class="ref-number">{{ $order->order_ref }}</p>
      </div>
      <span style="font-size:28px;opacity:0.3;">📋</span>
    </div>

    <!-- Status badge -->
    <div class="status-wrap">
      <p class="status-label-sm">Current Status</p>
      @php
        $statusLabels = [
          'pending'       => '⏳ Pending',
          'confirmed'     => '✅ Confirmed',
          'in_production' => '🖨️ In Production',
          'dispatched'    => '🚚 Dispatched',
          'completed'     => '🎉 Completed',
          'cancelled'     => '❌ Cancelled',
        ];
        $label = $statusLabels[$newStatus] ?? ucfirst(str_replace('_',' ',$newStatus));
        $steps = ['pending','confirmed','in_production','dispatched','completed'];
        $currentIdx = array_search($newStatus, $steps);
      @endphp
      <div class="status-badge status-{{ $newStatus }}">{{ $label }}</div>
    </div>

    <!-- Timeline (only for non-cancelled) -->
    @if($newStatus !== 'cancelled')
    <div class="timeline">
      @foreach(['pending'=>'Received','confirmed'=>'Confirmed','in_production'=>'Printing','dispatched'=>'Shipped','completed'=>'Done'] as $step => $stepLabel)
        @php
          $stepIdx = array_search($step, $steps);
          $isDone = $currentIdx > $stepIdx;
          $isActive = $currentIdx === $stepIdx;
          $dotClass = $isDone ? 'done' : ($isActive ? 'active' : '');
          $labelClass = $isDone ? 'done' : ($isActive ? 'active' : '');
        @endphp
        <div class="tl-step">
          <div class="tl-dot {{ $dotClass }}">{{ $isDone ? '✓' : ($stepIdx + 1) }}</div>
          <div class="tl-label {{ $labelClass }}">{{ $stepLabel }}</div>
        </div>
        @if(!$loop->last)
          <div class="tl-line {{ $isDone ? 'done' : '' }}"></div>
        @endif
      @endforeach
    </div>
    @endif

    <!-- Status message -->
    <div class="msg-box">
      @if($newStatus === 'confirmed')
        🎉 Great news! Your order has been confirmed. Our print team will begin production very soon.
      @elseif($newStatus === 'in_production')
        🖨️ Our team is currently printing your order. We'll notify you as soon as it's ready to dispatch.
      @elseif($newStatus === 'dispatched')
        🚚 Your order is on its way! It should arrive within <strong>1–2 working days</strong>. Keep an eye on your delivery.
      @elseif($newStatus === 'completed')
        🎉 Your order is complete! We hope you love your prints. Don't forget to leave us a review!
      @elseif($newStatus === 'cancelled')
        ❌ Your order has been cancelled. If you believe this is an error or have any questions, please contact us immediately.
      @else
        Your order status has been updated to <strong>{{ $label }}</strong>. We'll keep you informed of any further changes.
      @endif
    </div>

    <!-- CTA -->
    <div class="cta">
      <a href="{{ url('/account/order/' . $order->id) }}" class="btn">View Order Details →</a>
    </div>

    <p style="font-size:13px;color:#999;text-align:center;margin:0;">
      Need help? Email us at
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