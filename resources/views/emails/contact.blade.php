<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact Form Message</title>
<style>
  body { margin:0; padding:0; background:#f4f4f4; font-family:Arial,sans-serif; }
  .wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:10px; overflow:hidden; }
  .header { background:#1e3a6e; padding:24px 36px; }
  .header h1 { color:#fff; font-size:18px; margin:0; }
  .body { padding:30px 36px; font-size:14px; color:#333; line-height:1.7; }
  .field { margin-bottom:14px; }
  .field strong { display:block; color:#1e3a6e; font-size:12px; text-transform:uppercase; margin-bottom:3px; }
  .field span { color:#333; }
  .message-box { background:#f7f7f7; border-radius:7px; padding:16px; margin-top:6px; white-space:pre-wrap; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header"><h1>📬 New Contact Form Message</h1></div>
  <div class="body">
    <div class="field"><strong>Name</strong><span>{{ $contactName }}</span></div>
    <div class="field"><strong>Email</strong><span><a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></span></div>
    @if($contactPhone)<div class="field"><strong>Phone</strong><span>{{ $contactPhone }}</span></div>@endif
    <div class="field"><strong>Subject</strong><span>{{ $contactSubject }}</span></div>
    <div class="field"><strong>Message</strong><div class="message-box">{{ $contactMessage }}</div></div>
  </div>
</div>
</body>
</html>
