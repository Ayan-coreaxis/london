<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login – London InstantPrint</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',sans-serif;background:#1e3a6e;min-height:100vh;display:flex;align-items:center;justify-content:center}
.login-card{background:#fff;border-radius:14px;width:100%;max-width:420px;padding:44px 40px;box-shadow:0 20px 60px rgba(0,0,0,.3)}
.login-logo{text-align:center;margin-bottom:28px}
.login-logo img{height:42px}
h1{font-size:22px;font-weight:800;color:#1e3a6e;text-align:center;margin-bottom:6px}
.sub{font-size:13px;color:#888;text-align:center;margin-bottom:28px}
.field{margin-bottom:16px}
.field label{display:block;font-size:11px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px}
.field input{width:100%;height:46px;border:1.5px solid #ddd;border-radius:8px;padding:0 14px;font-size:14px;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s}
.field input:focus{border-color:#1e3a6e;box-shadow:0 0 0 3px rgba(30,58,110,.1)}
.errors{background:#fff0f0;border:1px solid #ffcccc;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#cc0000}
.btn{width:100%;height:48px;background:#f5c800;color:#1a1a1a;border:none;border-radius:8px;font-size:15px;font-weight:800;cursor:pointer;font-family:'Inter',sans-serif;transition:background .2s;margin-top:6px}
.btn:hover{background:#e0b400}
.hint{margin-top:20px;text-align:center;font-size:12px;color:#aaa;background:#f9f9f9;border-radius:8px;padding:10px}
</style>
</head>
<body>
<div class="login-card">
  <div class="login-logo"><img src="{{ asset('images/logo.png') }}" alt="Logo"></div>
  <h1>Admin Panel</h1>
  <p class="sub">Sign in to manage your print shop</p>
  @if($errors->any())
    <div class="errors">@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>
  @endif
  <form method="POST" action="{{ route('admin.login.post') }}">
    @csrf
    <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus></div>
    <div class="field"><label>Password</label><input type="password" name="password" placeholder="••••••••" required></div>
    <button type="submit" class="btn">Sign In to Admin</button>
  </form>
  <div class="hint">Default: admin@londoninstantprint.co.uk / admin123</div>
</div>
</body></html>