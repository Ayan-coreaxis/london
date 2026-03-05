<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Status Update</title>
<style>
  body { margin:0; padding:0; background:#f4f4f4; font-family:'Open Sans',Arial,sans-serif; }
  .wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.08); }
  .header { background:#1e3a6e; padding:30px 40px; text-align:center; }
  .header h1 { color:#fff; font-size:22px; margin:0; font-family:Montserrat,Arial,sans-serif; font-weight:900; }
  .body { padding:36px 40px; }
  .status-badge { display:inline-block; padding:10px 24px; border-radius:30px; font-family:Montserrat,Arial,sans-serif; font-weight:700; font-size:15px; margin:16px 0; }
  .status-pending       { background:#fff8e6; color:#c87600; border:1px solid #ffd888; }
  .status-confirmed     { background:#e8f4ff; color:#0055aa; border:1px solid #99ccff; }
  .status-in_production { background:#f0e8ff; color:#6600cc; border:1px solid #cc99ff; }
  .status-dispatched    { background:#fff0e8; color:#cc4400; border:1px solid #ffbb88; }
  .status-completed     { background:#f0faf0; color:#2a7a2a; border:1px solid #99dd99; }
  .status-cancelled     { background:#fff3f2; color:#c0392b; border:1px solid #fbc9c6; }
  .ref-box { background:#f0f4ff; border-left:4px solid #1e3a6e; border-radius:6px; padding:14px 18px; margin:20px 0; }
  .ref-box p { margin:0; font-size:13px; color:#555; }
  .ref-box strong { font-size:18px; color:#1e3a6e; font-family:Montserrat,Arial,sans-serif; }
  .cta { text-align:center; margin:30px 0; }
  .btn { display:inline-block; background:#e8352a; color:#fff; text-decoration:none; padding:14px 32px; border-radius:7px; font-family:Montserrat,Arial,sans-serif; font-weight:700; font-size:14px; }
  .footer { background:#f7f7f7; padding:20px 40px; text-align:center; font-size:12px; color:#999; }
  .footer a { color:#1e3a6e; text-decoration:none; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>📦 Order Status Update</h1>
  </div>
  <div class="body">
    <p style="color:#555;font-size:14px;">Hi <strong>{{ $order->first_name }}</strong>, your order status has been updated.</p>

    <div class="ref-box">
      <p>Order Reference</p>
      <strong>{{ $order->order_ref }}</strong>
    </div>

    <p style="font-size:14px;color:#555;">Your order is now:</p>
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
    @endphp
    <div class="status-badge status-{{ $newStatus }}">{{ $label }}</div>

    @if($newStatus === 'dispatched')
      <p style="font-size:14px;color:#555;">Your order has been dispatched and is on its way to you! You should receive it within 1-2 working days.</p>
    @elseif($newStatus === 'in_production')
      <p style="font-size:14px;color:#555;">Our team has started printing your order. We'll notify you once it's dispatched.</p>
    @elseif($newStatus === 'confirmed')
      <p style="font-size:14px;color:#555;">Great news! Your order has been confirmed and will enter production shortly.</p>
    @elseif($newStatus === 'completed')
      <p style="font-size:14px;color:#555;">Your order is complete. We hope you love your prints! 🎉</p>
    @endif

    <div class="cta">
      <a href="{{ url('/account/order/' . $order->id) }}" class="btn">View Order Details</a>
    </div>

    <p style="font-size:13px;color:#777;">Questions? Email us at <a href="mailto:info@londoninstantprint.co.uk" style="color:#1e3a6e;">info@londoninstantprint.co.uk</a></p>
  </div>
  <div class="footer">
    &copy; {{ date('Y') }} London InstantPrint. All rights reserved.<br>
    <a href="{{ url('/') }}">londoninstantprint.co.uk</a>
  </div>
</div>
</body>
</html>
