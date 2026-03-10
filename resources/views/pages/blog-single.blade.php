@extends('layouts.app')
@section('title', $blog->title.' – London InstantPrint')
@section('meta_description', $blog->excerpt ?? '')

@section('styles')
<style>
.blog-single-wrap { max-width: 820px; margin: 0 auto; padding: 48px 24px 80px; }
.blog-breadcrumb { font-size: 13px; color: #888; margin-bottom: 24px; }
.blog-breadcrumb a { color: #888; text-decoration: none; }
.blog-breadcrumb a:hover { color: #1e3a6e; }
.blog-breadcrumb span { margin: 0 6px; }
.blog-single-hero-img { width: 100%; height: 360px; object-fit: cover; border-radius: 12px; margin-bottom: 32px; }
.blog-single-cat { display: inline-block; background: #1e3a6e; color: #fff; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 10px; font-family: 'Montserrat',sans-serif; text-transform: uppercase; margin-bottom: 14px; }
.blog-single-title { font-family: 'Montserrat',sans-serif; font-size: clamp(24px,3.5vw,38px); font-weight: 900; color: #1e3a6e; line-height: 1.25; margin-bottom: 14px; }
.blog-single-meta { font-size: 13px; color: #aaa; margin-bottom: 28px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
.blog-single-body { font-size: 15px; line-height: 1.8; color: #444; }
.blog-single-body h2,h3,h4 { font-family: 'Montserrat',sans-serif; color: #1e3a6e; margin: 24px 0 10px; }
.blog-single-body p  { margin-bottom: 16px; }
.blog-single-body ul,ol { margin: 0 0 16px 24px; }
.blog-single-body li { margin-bottom: 6px; }
.related-section { max-width: 1160px; margin: 0 auto; padding: 0 24px 80px; }
.related-section h2 { font-family: 'Montserrat',sans-serif; font-size: 22px; font-weight: 900; color: #1e3a6e; margin-bottom: 20px; }
.related-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
@media (max-width: 700px) { .related-grid { grid-template-columns: 1fr; } .blog-single-hero-img { height: 220px; } }
</style>
@endsection

@section('content')
<div class="blog-single-wrap">
  <div class="blog-breadcrumb">
    <a href="{{ route('home') }}">Home</a><span>›</span>
    <a href="{{ route('blog') }}">Blog</a><span>›</span>
    <strong>{{ Str::limit($blog->title,50) }}</strong>
  </div>

  @if($blog->image)
    <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="blog-single-hero-img" onerror="this.style.display='none'">
  @endif

  @if($blog->category)
    <span class="blog-single-cat">{{ $blog->category }}</span>
  @endif

  <h1 class="blog-single-title">{{ $blog->title }}</h1>
  <div class="blog-single-meta">
    By <strong>{{ $blog->author }}</strong> · {{ $blog->created_at->format('d M Y') }}
  </div>

  <div class="blog-single-body">
    @if($blog->body)
      {!! $blog->body !!}
    @elseif($blog->excerpt)
      <p>{{ $blog->excerpt }}</p>
    @else
      <p>This article is coming soon. Check back later!</p>
    @endif
  </div>
</div>

@if($related->count())
<div class="related-section">
  <h2>Related Articles</h2>
  <div class="related-grid">
    @foreach($related as $post)
    <a href="{{ route('blog.show',$post->slug) }}" style="border-radius:10px;overflow:hidden;background:#fff;border:1px solid #eee;text-decoration:none;display:block;transition:box-shadow .2s" onmouseover="this.style.boxShadow='0 6px 24px rgba(30,58,110,.1)'" onmouseout="this.style.boxShadow='none'">
      @if($post->image)
        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" style="width:100%;height:140px;object-fit:cover" onerror="this.style.display='none'">
      @endif
      <div style="padding:14px 16px">
        <div style="font-family:'Montserrat',sans-serif;font-size:13px;font-weight:800;color:#1e3a6e;margin-bottom:6px;line-height:1.3">{{ $post->title }}</div>
        <div style="font-size:11px;color:#aaa">{{ $post->created_at->format('d M Y') }}</div>
      </div>
    </a>
    @endforeach
  </div>
</div>
@endif
@endsection
