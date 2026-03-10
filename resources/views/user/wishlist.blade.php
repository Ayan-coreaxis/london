@extends('layouts.app')
@section('title', 'My Wishlist – London InstantPrint')

@section('styles')
<style>
.account-wrap{max-width:1100px;margin:0 auto;padding:36px 24px 80px;font-family:'Open Sans',sans-serif}
.account-breadcrumb{font-size:13px;color:#888;margin-bottom:28px}.account-breadcrumb a{color:#888;text-decoration:none}.account-breadcrumb a:hover{color:#1e3a6e}.account-breadcrumb span{margin:0 6px}
.account-nav{display:flex;gap:8px;margin-bottom:28px;flex-wrap:wrap}
.account-nav a{padding:9px 18px;border:1.5px solid #e0e0e0;border-radius:7px;font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;color:#555;text-decoration:none;transition:all .2s}
.account-nav a:hover,.account-nav a.active{background:#1e3a6e;color:#fff;border-color:#1e3a6e}
.wishlist-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:20px}
.wish-card{background:#fff;border:1px solid #e8e8e8;border-radius:10px;overflow:hidden;transition:box-shadow .2s}
.wish-card:hover{box-shadow:0 4px 20px rgba(0,0,0,.08)}
.wish-thumb{aspect-ratio:4/3;overflow:hidden;background:#f0f4ff}
.wish-thumb img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .3s}
.wish-card:hover .wish-thumb img{transform:scale(1.04)}
.wish-body{padding:14px 16px}
.wish-name{font-family:'Montserrat',sans-serif;font-size:14px;font-weight:700;color:#1e3a6e;margin-bottom:4px}
.wish-price{font-size:13px;color:#888;margin-bottom:10px}
.wish-actions{display:flex;gap:8px}
.wish-btn{padding:8px 14px;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;text-decoration:none;text-align:center;border:none;transition:all .2s;font-family:'Montserrat',sans-serif}
.wish-btn-primary{background:#1e3a6e;color:#fff;flex:1}.wish-btn-primary:hover{background:#162d56}
.wish-btn-remove{background:none;border:1.5px solid #e53935;color:#e53935;padding:8px 12px}.wish-btn-remove:hover{background:#fff5f5}
.empty-state{text-align:center;padding:60px 20px;color:#888}
.empty-state h3{font-family:'Montserrat',sans-serif;font-size:18px;color:#555;margin:16px 0 8px}
.empty-state a{display:inline-block;margin-top:16px;padding:11px 28px;background:#1e3a6e;color:#fff;border-radius:6px;text-decoration:none;font-weight:700;font-size:14px}
</style>
@endsection

@section('content')
<div class="account-wrap">
    <div class="account-breadcrumb"><a href="{{ route('home') }}">Home</a><span>›</span><a href="{{ route('user.dashboard') }}">My Account</a><span>›</span>Wishlist</div>

    <h1 style="font-family:'Montserrat',sans-serif;font-size:26px;font-weight:900;color:#1e3a6e;margin-bottom:24px">My Wishlist</h1>

    <div class="account-nav">
        <a href="{{ route('user.dashboard') }}">My Orders</a>
        <a href="{{ route('user.profile') }}">My Profile</a>
        <a href="{{ route('user.addresses') }}">Addresses</a>
        <a href="{{ route('user.wishlist') }}" class="active">Wishlist</a>
    </div>

    @if(session('success'))<div style="background:#f0faf0;border:1px solid #c6e8c6;border-radius:8px;padding:12px 18px;color:#2a7a2a;font-size:14px;font-weight:600;margin-bottom:20px">✅ {{ session('success') }}</div>@endif

    @if(empty($items))
        <div class="empty-state">
            <svg width="52" height="52" fill="none" stroke="#ccc" stroke-width="1.3" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            <h3>Your wishlist is empty</h3>
            <p>Save products you love for later.</p>
            <a href="{{ route('products') }}">Browse Products</a>
        </div>
    @else
        <div class="wishlist-grid">
            @foreach($items as $item)
            <div class="wish-card">
                <a href="{{ route('product.show', $item->slug) }}" class="wish-thumb">
                    @if($item->image1)<img src="{{ asset($item->image1) }}" alt="{{ $item->name }}">@endif
                </a>
                <div class="wish-body">
                    <div class="wish-name">{{ $item->name }}</div>
                    <div class="wish-price">From £{{ number_format($item->base_price, 2) }}</div>
                    <div class="wish-actions">
                        <a href="{{ route('product.show', $item->slug) }}" class="wish-btn wish-btn-primary">View Product</a>
                        <form method="POST" action="{{ route('user.wishlist.remove', $item->wishlist_id) }}" onsubmit="return confirm('Remove?')">@csrf @method('DELETE')
                            <button type="submit" class="wish-btn wish-btn-remove">✕</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
