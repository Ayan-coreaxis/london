<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Confirmed</title>
<style>
  body { margin:0; padding:0; background:#f4f4f4; font-family:'Open Sans',Arial,sans-serif; }
  .wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.08); }
  .header { background:#1e3a6e; padding:30px 40px; text-align:center; }
  .header img { height:40px; }
  .header h1 { color:#fff; font-size:22px; margin:12px 0 0; font-family:Montserrat,Arial,sans-serif; font-weight:900; }
  .body { padding:36px 40px; }
  .greeting { font-size:16px; color:#333; margin-bottom:16px; }
  .ref-box { background:#f0f4ff; border-left:4px solid #1e3a6e; border-radius:6px; padding:14px 18px; margin:20px 0; }
  .ref-box p { margin:0; font-size:13px; color:#555; }
  .ref-box strong { font-size:20px; color:#1e3a6e; font-family:Montserrat,Arial,sans-serif; }
  .table-wrap { margin:24px 0; }
  table { width:100%; border-collapse:collapse; font-size:13px; }
  th { background:#f7f7f7; padding:10px 12px; text-align:left; font-family:Montserrat,Arial,sans-serif; font-weight:700; color:#555; border-bottom:2px solid #e8e8e8; }
  td { padding:10px 12px; border-bottom:1px solid #f0f0f0; color:#333; vertical-align:top; }
  .totals { margin-top:20px; }
  .totals table { width:260px; margin-left:auto; }
  .totals td { border:none; padding:5px 10px; }
  .totals .total-row td { font-weight:700; font-size:15px; color:#1e3a6e; border-top:2px solid #e8e8e8; padding-top:10px; }
  .address-box { background:#f9f9f9; border-radius:8px; padding:16px 20px; margin:20px 0; font-size:13px; color:#444; line-height:1.7; }
  .address-box h4 { margin:0 0 8px; font-family:Montserrat,Arial,sans-serif; color:#1e3a6e; font-size:13px; }
  .cta { text-align:center; margin:30px 0; }
  .btn { display:inline-block; background:#e8352a; color:#fff; text-decoration:none; padding:14px 32px; border-radius:7px; font-family:Montserrat,Arial,sans-serif; font-weight:700; font-size:14px; }
  .footer { background:#f7f7f7; padding:20px 40px; text-align:center; font-size:12px; color:#999; }
  .footer a { color:#1e3a6e; text-decoration:none; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>✅ Order Confirmed!</h1>
  </div>
  <div class="body">
    <p class="greeting">Hi <strong>{{ $order->first_name }}</strong>,</p>
    <p style="color:#555;font-size:14px;">Thank you for your order! We've received it and our team will begin processing it shortly.</p>

    <div class="ref-box">
      <p>Your Order Reference</p>
      <strong>{{ $order->order_ref }}</strong>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Options</th>
            <th>Qty</th>
            <th style="text-align:right">Price</th>
          </tr>
        </thead>
        <tbody>
          @foreach($items as $item)
          <tr>
            <td><strong>{{ $item->product_name }}</strong></td>
            <td style="color:#777;">
              @if($item->options)
                @php $opts = is_string($item->options) ? json_decode($item->options, true) : (array)$item->options; @endphp
                @foreach($opts as $k => $v)
                  <span>{{ ucfirst($k) }}: {{ $v }}</span><br>
                @endforeach
              @else —
              @endif
            </td>
            <td>{{ $item->quantity }}</td>
            <td style="text-align:right">£{{ number_format($item->line_total, 2) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="totals">
      <table>
        <tr><td>Subtotal</td><td style="text-align:right">£{{ number_format($order->subtotal, 2) }}</td></tr>
        <tr><td>VAT (20%)</td><td style="text-align:right">£{{ number_format($order->vat, 2) }}</td></tr>
        <tr><td>Delivery</td><td style="text-align:right">£{{ number_format($order->delivery_cost, 2) }}</td></tr>
        <tr class="total-row"><td>Total</td><td style="text-align:right">£{{ number_format($order->total, 2) }}</td></tr>
      </table>
    </div>

    <div class="address-box">
      <h4>📦 Delivery Address</h4>
      {{ $order->first_name }} {{ $order->last_name }}<br>
      @if($order->company){{ $order->company }}<br>@endif
      {{ $order->address_line1 }}<br>
      @if($order->address_line2){{ $order->address_line2 }}<br>@endif
      {{ $order->city }}, {{ $order->postcode }}
    </div>

    <div class="cta">
      <a href="{{ url('/account/order/' . $order->id) }}" class="btn">Track Your Order</a>
    </div>

    <p style="font-size:13px;color:#777;">If you have any questions, reply to this email or contact us at <a href="mailto:info@londoninstantprint.co.uk" style="color:#1e3a6e;">info@londoninstantprint.co.uk</a></p>
  </div>
  <div class="footer">
    &copy; {{ date('Y') }} London InstantPrint. All rights reserved.<br>
    <a href="{{ url('/') }}">londoninstantprint.co.uk</a>
  </div>
</div>
</body>
</html>
