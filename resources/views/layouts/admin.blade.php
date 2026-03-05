<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>@yield('title','Admin') – London InstantPrint</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',sans-serif;background:#f0f2f5;color:#222}
.admin-wrap{display:flex;min-height:100vh}
/* SIDEBAR */
.sidebar{width:240px;background:#1e3a6e;color:#fff;display:flex;flex-direction:column;flex-shrink:0;position:fixed;height:100vh;overflow-y:auto;z-index:100}
.sidebar-logo{padding:22px 20px 18px;border-bottom:1px solid rgba(255,255,255,.1);display:flex;align-items:center;gap:10px}
.sidebar-logo img{height:34px;filter:brightness(10)}
.sidebar-logo span{font-size:13px;font-weight:700;color:#fff;letter-spacing:.3px}
.sidebar-nav{padding:12px 0;flex:1}
.nav-section{padding:10px 18px 4px;font-size:10px;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:1px}
.nav-link{display:flex;align-items:center;gap:12px;padding:10px 20px;color:rgba(255,255,255,.75);text-decoration:none;font-size:13px;font-weight:500;transition:all .2s;border-left:3px solid transparent}
.nav-link:hover,.nav-link.active{color:#fff;background:rgba(255,255,255,.1);border-left-color:#f5c800}
.nav-link i{width:16px;text-align:center;font-size:14px}
.sidebar-footer{padding:16px 20px;border-top:1px solid rgba(255,255,255,.1);font-size:12px;color:rgba(255,255,255,.5)}
/* MAIN */
.main-content{margin-left:240px;flex:1;display:flex;flex-direction:column;min-height:100vh}
.topbar{background:#fff;padding:14px 28px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #e8e8e8;position:sticky;top:0;z-index:50}
.topbar-title{font-size:18px;font-weight:700;color:#1e3a6e}
.topbar-right{display:flex;align-items:center;gap:16px}
.admin-badge{font-size:12px;background:#eef3ff;color:#1e3a6e;padding:5px 12px;border-radius:20px;font-weight:600}
.logout-btn{font-size:13px;color:#e8352a;text-decoration:none;font-weight:600}
.page-body{padding:28px;flex:1}
/* CARDS */
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:28px}
.stat-card{background:#fff;border-radius:10px;padding:20px 22px;border:1px solid #e8e8e8}
.stat-card .label{font-size:12px;color:#888;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px}
.stat-card .value{font-size:26px;font-weight:800;color:#1e3a6e}
.stat-card .change{font-size:12px;color:#3c9c3c;margin-top:4px}
.stat-card .icon{float:right;width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px}
/* TABLE */
.data-card{background:#fff;border-radius:10px;border:1px solid #e8e8e8;overflow:hidden;margin-bottom:24px}
.data-card-hdr{padding:16px 22px;border-bottom:1px solid #e8e8e8;display:flex;align-items:center;justify-content:space-between}
.data-card-hdr h3{font-size:14px;font-weight:700;color:#222}
.data-table{width:100%;border-collapse:collapse}
.data-table th{padding:11px 16px;text-align:left;font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.5px;background:#f9f9f9;border-bottom:1px solid #eee}
.data-table td{padding:12px 16px;font-size:13px;color:#333;border-bottom:1px solid #f5f5f5}
.data-table tr:last-child td{border-bottom:none}
.data-table tr:hover td{background:#fafafa}
/* BADGES */
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.badge-pending{background:#fff8e6;color:#d08800}
.badge-confirmed{background:#e8f4ff;color:#0066cc}
.badge-in_production{background:#eef8ff;color:#0088cc}
.badge-dispatched{background:#f0f8f0;color:#228822}
.badge-completed{background:#e6ffed;color:#156f15}
.badge-cancelled{background:#fff0f0;color:#cc0000}
/* FORMS */
.form-group{margin-bottom:18px}
.form-group label{display:block;font-size:12px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px}
.form-control{width:100%;height:42px;border:1.5px solid #ddd;border-radius:7px;padding:0 13px;font-size:14px;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s}
.form-control:focus{border-color:#1e3a6e;box-shadow:0 0 0 3px rgba(30,58,110,.08)}
textarea.form-control{height:80px;padding:12px 13px;resize:vertical}
.btn-primary{padding:10px 22px;background:#1e3a6e;color:#fff;border:none;border-radius:7px;font-size:13px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;transition:background .2s;text-decoration:none;display:inline-block}
.btn-primary:hover{background:#162d56}
.btn-yellow{padding:10px 22px;background:#f5c800;color:#1a1a1a;border:none;border-radius:7px;font-size:13px;font-weight:700;cursor:pointer;transition:background .2s;text-decoration:none;display:inline-block}
.btn-yellow:hover{background:#e0b400}
.btn-danger{padding:7px 16px;background:#fff0f0;color:#cc0000;border:1px solid #ffcccc;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;transition:all .2s;text-decoration:none;display:inline-block}
.btn-danger:hover{background:#cc0000;color:#fff}
.btn-sm{padding:6px 14px;font-size:12px}
/* ALERTS */
.alert-success{background:#f0faf0;border:1px solid #c6e8c6;border-radius:8px;padding:12px 16px;color:#2a7a2a;margin-bottom:20px;font-size:13px;font-weight:600}
.alert-error{background:#fff0f0;border:1px solid #ffcccc;border-radius:8px;padding:12px 16px;color:#cc0000;margin-bottom:20px;font-size:13px;font-weight:600}
@media(max-width:900px){.stat-grid{grid-template-columns:1fr 1fr}.sidebar{display:none}.main-content{margin-left:0}}
</style>
@yield('styles')
</head>
<body>
<div class="admin-wrap">
  <aside class="sidebar">
    <div class="sidebar-logo">
      <img src="{{ asset('images/logo.png') }}" alt="Logo">
      <span>Admin Panel</span>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-section">Main</div>
      <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
      </a>
      <div class="nav-section">Commerce</div>
      <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
        <i class="fas fa-shopping-bag"></i> Orders
      </a>
      <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
        <i class="fas fa-box"></i> Products
      </a>
      <div class="nav-section">People</div>
      <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
        <i class="fas fa-users"></i> Customers
      </a>
      <div class="nav-section">Analytics</div>
      <a href="{{ route('admin.analytics') }}" class="nav-link {{ request()->routeIs('admin.analytics*') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i> Analytics
      </a>
      <div class="nav-section">Content</div>
      <a href="{{ route('admin.blogs.index') }}" class="nav-link {{ request()->routeIs('admin.blogs*') ? 'active' : '' }}">
        <i class="fas fa-blog"></i> Blog Posts
      </a>
      <div class="nav-section">Config</div>
      <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
        <i class="fas fa-cog"></i> Site Settings
      </a>
    </nav>
    <div class="sidebar-footer">
      <form method="POST" action="{{ route('admin.logout') }}">@csrf
        <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.5);cursor:pointer;font-size:12px;font-family:'Inter',sans-serif;">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </form>
    </div>
  </aside>
  <div class="main-content">
    <div class="topbar">
      <div class="topbar-title">@yield('page_title','Dashboard')</div>
      <div class="topbar-right">
        <span class="admin-badge"><i class="fas fa-shield-alt"></i> {{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
        <a href="{{ route('home') }}" target="_blank" class="logout-btn" style="color:#1e3a6e;"><i class="fas fa-external-link-alt"></i> View Site</a>
      </div>
    </div>
    <div class="page-body">
      @if(session('success'))<div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
      @if(session('error'))<div class="alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif
      @yield('content')
    </div>
  </div>
</div>
@yield('scripts')
</body>
</html>