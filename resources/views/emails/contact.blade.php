<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Contact Message – London InstantPrint</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&family=Open+Sans:wght@400;600&display=swap');
  * { box-sizing: border-box; }
  body { margin:0; padding:0; background:#f0f2f5; font-family:'Open Sans',Arial,sans-serif; }
  .outer { padding: 32px 16px; }
  .wrap { max-width:620px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 32px rgba(0,0,0,0.10); }

  /* HEADER */
  .header {
    background: linear-gradient(135deg, #1e3a6e 0%, #2a5298 100%);
    padding: 28px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
  }
  .header::after {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 140px; height: 140px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
  }
  .header-left {}
  .logo-text {
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 900;
    font-size: 18px;
    letter-spacing: -0.3px;
    display: block;
    margin-bottom: 8px;
  }
  .logo-w { color: #fff; }
  .logo-y { color: #f5c800; }
  .logo-dot {
    display: inline-block;
    width: 5px; height: 5px;
    background: #f5c800;
    border-radius: 50%;
    margin: 0 2px 1px;
    vertical-align: middle;
  }
  .header-title {
    color: #fff;
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 700;
    font-size: 15px;
    margin: 0;
  }
  .header-sub { color: rgba(255,255,255,0.6); font-size: 12px; margin: 3px 0 0; }
  .header-icon { font-size: 42px; position: relative; z-index: 2; }
  .accent-bar { height: 4px; background: linear-gradient(90deg, #f5c800 0%, #ffdc50 50%, #f5c800 100%); }

  /* Alert banner */
  .alert-banner {
    background: #fff8e6;
    border-left: 4px solid #f5c800;
    padding: 12px 20px;
    font-size: 13px;
    color: #7a5500;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  /* BODY */
  .body { padding: 32px 40px; }

  /* Field rows */
  .field-row {
    display: flex;
    gap: 0;
    margin-bottom: 16px;
    border-radius: 10px;
    overflow: hidden;
    border: 1.5px solid #eef0f6;
  }
  .field-key {
    background: #f7f8fc;
    padding: 14px 16px;
    font-family: 'Montserrat', Arial, sans-serif;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #1e3a6e;
    width: 130px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    border-right: 1.5px solid #eef0f6;
  }
  .field-val {
    padding: 14px 16px;
    font-size: 14px;
    color: #333;
    flex: 1;
    display: flex;
    align-items: center;
  }
  .field-val a { color: #1e3a6e; text-decoration: none; font-weight: 600; }

  /* Message box */
  .msg-section { margin-top: 24px; }
  .msg-label {
    font-family: 'Montserrat', Arial, sans-serif;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #1e3a6e;
    margin: 0 0 10px;
    padding-bottom: 8px;
    border-bottom: 2px solid #f0f0f0;
  }
  .msg-box {
    background: #f7f8fc;
    border-radius: 10px;
    padding: 20px;
    font-size: 14px;
    color: #444;
    line-height: 1.8;
    white-space: pre-wrap;
    border: 1.5px solid #eef0f6;
  }

  /* Reply CTA */
  .reply-wrap {
    margin-top: 28px;
    padding-top: 24px;
    border-top: 2px solid #f0f0f0;
    text-align: center;
  }
  .reply-wrap p { font-size: 13px; color: #888; margin: 0 0 14px; }
  .btn-reply {
    display: inline-block;
    background: linear-gradient(135deg, #1e3a6e, #2a5298);
    color: #fff !important;
    text-decoration: none;
    padding: 13px 32px;
    border-radius: 8px;
    font-family: 'Montserrat', Arial, sans-serif;
    font-weight: 700;
    font-size: 13px;
  }

  /* Meta info */
  .meta-row {
    display: flex;
    gap: 16px;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1.5px solid #f5f5f5;
  }
  .meta-item { font-size: 11px; color: #bbb; }
  .meta-item strong { color: #999; }

  /* FOOTER */
  .footer { background: #1e3a6e; padding: 22px 40px; text-align: center; }
  .footer-logo { font-family: 'Montserrat', Arial, sans-serif; font-weight: 900; font-size: 14px; margin-bottom: 6px; }
  .f-yellow { color: #f5c800; }
  .f-white { color: #fff; }
  .footer-copy { color: rgba(255,255,255,0.35); font-size: 11px; margin: 6px 0 0; }
</style>
</head>
<body>
<div class="outer">
<div class="wrap">

  <!-- HEADER -->
  <div class="header">
    <div class="header-left">
      <span class="logo-text">
        <span class="logo-w">London</span><span class="logo-dot"></span><span class="logo-y">Instant</span><span class="logo-w">Print</span>
      </span>
      <p class="header-title">New Contact Message</p>
      <p class="header-sub">Received via website contact form</p>
    </div>
    <div class="header-icon">📬</div>
  </div>
  <div class="accent-bar"></div>

  <!-- Alert -->
  <div class="alert-banner">
    ⚡ New message received — please respond within 24 hours
  </div>

  <!-- BODY -->
  <div class="body">

    <!-- Fields -->
    <div class="field-row">
      <div class="field-key">👤 Name</div>
      <div class="field-val"><strong>{{ $contactName }}</strong></div>
    </div>
    <div class="field-row">
      <div class="field-key">✉️ Email</div>
      <div class="field-val"><a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></div>
    </div>
    @if(!empty($contactPhone))
    <div class="field-row">
      <div class="field-key">📞 Phone</div>
      <div class="field-val"><a href="tel:{{ $contactPhone }}">{{ $contactPhone }}</a></div>
    </div>
    @endif
    <div class="field-row">
      <div class="field-key">📌 Subject</div>
      <div class="field-val">{{ $contactSubject }}</div>
    </div>

    <!-- Message -->
    <div class="msg-section">
      <p class="msg-label">Message</p>
      <div class="msg-box">{{ $contactMessage }}</div>
    </div>

    <!-- Reply CTA -->
    <div class="reply-wrap">
      <p>Click below to reply directly to this customer</p>
      <a href="mailto:{{ $contactEmail }}?subject=Re: {{ $contactSubject }}" class="btn-reply">Reply to {{ $contactName }} →</a>
    </div>

    <!-- Meta -->
    <div class="meta-row">
      <div class="meta-item"><strong>Received:</strong> {{ now()->format('d M Y, H:i') }}</div>
      <div class="meta-item"><strong>From:</strong> londoninstantprint.co.uk</div>
    </div>

  </div>

  <!-- FOOTER -->
  <div class="footer">
    <div class="footer-logo">
      <span class="f-white">London</span><span class="f-yellow">Instant</span><span class="f-white">Print</span>
    </div>
    <p class="footer-copy">This is an internal notification email. &copy; {{ date('Y') }} London InstantPrint Ltd.</p>
  </div>

</div>
</div>
</body>
</html>