<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welcome to London InstantPrint</title>
<style>
  body { margin:0; padding:0; background:#f4f4f4; font-family:'Open Sans',Arial,sans-serif; }
  .wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.08); }
  .header { background:#1e3a6e; padding:36px 40px; text-align:center; }
  .header h1 { color:#fff; font-size:26px; margin:0 0 8px; font-family:Montserrat,Arial,sans-serif; font-weight:900; }
  .header p { color:#a8c0e8; margin:0; font-size:14px; }
  .body { padding:36px 40px; }
  .features { display:flex; gap:12px; margin:24px 0; }
  .feature { flex:1; text-align:center; padding:16px 10px; background:#f7f7f7; border-radius:8px; }
  .feature .icon { font-size:28px; display:block; margin-bottom:8px; }
  .feature p { margin:0; font-size:12px; color:#555; font-weight:600; font-family:Montserrat,Arial,sans-serif; }
  .cta { text-align:center; margin:30px 0; }
  .btn { display:inline-block; background:#e8352a; color:#fff; text-decoration:none; padding:14px 32px; border-radius:7px; font-family:Montserrat,Arial,sans-serif; font-weight:700; font-size:14px; }
  .footer { background:#f7f7f7; padding:20px 40px; text-align:center; font-size:12px; color:#999; }
  .footer a { color:#1e3a6e; text-decoration:none; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>Welcome to London InstantPrint! 🎉</h1>
    <p>Your account has been created successfully</p>
  </div>
  <div class="body">
    <p style="color:#333;font-size:15px;">Hi <strong>{{ $user->name }}</strong>,</p>
    <p style="color:#555;font-size:14px;line-height:1.7;">Thank you for joining London InstantPrint — London's trusted printing partner. We're delighted to have you on board!</p>

    <div class="features">
      <div class="feature"><span class="icon">📦</span><p>Track Orders</p></div>
      <div class="feature"><span class="icon">⚡</span><p>Fast Delivery</p></div>
      <div class="feature"><span class="icon">🎨</span><p>Quality Prints</p></div>
    </div>

    <p style="color:#555;font-size:14px;line-height:1.7;">Your account gives you access to:</p>
    <ul style="color:#555;font-size:14px;line-height:2;">
      <li>Full order history and tracking</li>
      <li>Faster checkout with saved details</li>
      <li>Exclusive member offers</li>
    </ul>

    <div class="cta">
      <a href="{{ url('/all-products') }}" class="btn">Start Shopping</a>
    </div>

    <p style="font-size:13px;color:#777;">Need help? Email us at <a href="mailto:info@londoninstantprint.co.uk" style="color:#1e3a6e;">info@londoninstantprint.co.uk</a></p>
  </div>
  <div class="footer">
    &copy; {{ date('Y') }} London InstantPrint. All rights reserved.<br>
    <a href="{{ url('/') }}">londoninstantprint.co.uk</a>
  </div>
</div>
</body>
</html>
