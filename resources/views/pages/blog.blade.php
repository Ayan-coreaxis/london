@extends('layouts.app')
@section('title', 'Blog – London InstantPrint')
@section('meta_description', 'Tips, guides and inspiration for print design and marketing.')

@section('styles')
<style>
.blog-page-wrap { max-width: 1160px; margin: 0 auto; padding: 48px 24px 80px; }
.blog-page-hero { text-align: center; margin-bottom: 40px; }
.blog-page-hero h1 { font-family: 'Montserrat',sans-serif; font-size: clamp(28px,4vw,46px); font-weight: 900; color: #1e3a6e; margin-bottom: 10px; }
.blog-page-hero p  { font-size: 15px; color: #888; }
.blog-cats { display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; margin-bottom: 36px; }
.blog-cat-btn { padding: 7px 18px; border-radius: 20px; font-size: 12px; font-weight: 700; text-decoration: none; border: 1.5px solid #e0e0e0; background: #fff; color: #666; transition: all .2s; font-family: 'Montserrat',sans-serif; }
.blog-cat-btn:hover, .blog-cat-btn.active { border-color: #1e3a6e; background: #1e3a6e; color: #fff; }
.blog-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 24px; margin-bottom: 40px; }
.blog-card-full { border-radius: 12px; overflow: hidden; background: #fff; border: 1px solid #eee; text-decoration: none; color: inherit; display: block; transition: box-shadow .2s, transform .2s; }
.blog-card-full:hover { box-shadow: 0 8px 32px rgba(30,58,110,.12); transform: translateY(-3px); }
.bcf-thumb { height: 180px; overflow: hidden; background: #eef3ff; position: relative; }
.bcf-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
.blog-card-full:hover .bcf-thumb img { transform: scale(1.04); }
.bcf-cat { position: absolute; top: 12px; left: 12px; background: #1e3a6e; color: #fff; font-size: 10px; font-weight: 700; padding: 3px 10px; border-radius: 10px; font-family: 'Montserrat',sans-serif; text-transform: uppercase; }
.bcf-body { padding: 18px 20px 20px; }
.bcf-body h3 { font-family: 'Montserrat',sans-serif; font-size: 15px; font-weight: 800; color: #1e3a6e; margin-bottom: 8px; line-height: 1.35; }
.bcf-excerpt { font-size: 13px; color: #777; line-height: 1.6; margin-bottom: 14px; }
.bcf-meta { font-size: 11px; color: #bbb; display: flex; justify-content: space-between; align-items: center; }
.bcf-read-more { font-size: 12px; font-weight: 700; color: #e8352a; text-decoration: none; font-family: 'Montserrat',sans-serif; }
.blog-empty { text-align: center; padding: 60px; color: #aaa; grid-column: 1/-1; }
@media (max-width: 900px) { .blog-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 580px) { .blog-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="blog-page-wrap">

  <div class="blog-page-hero">
    <h1>Blog &amp; Inspiration</h1>
    <p>Tips, guides and ideas for your print projects.</p>
  </div>

  {{-- Category filters --}}
  @if($categories->count())
  <div class="blog-cats">
    <a href="{{ route('blog') }}" class="blog-cat-btn {{ !request('category') ? 'active' : '' }}">All</a>
    @foreach($categories as $cat)
    <a href="{{ route('blog') }}?category={{ urlencode($cat) }}" class="blog-cat-btn {{ request('category')===$cat ? 'active' : '' }}">{{ $cat }}</a>
    @endforeach
  </div>
  @endif

  <div class="blog-grid">
    @forelse($blogs as $blog)
    <a href="{{ route('blog.show',$blog->slug) }}" class="blog-card-full">
      <div class="bcf-thumb">
        @if($blog->image)
          <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" onerror="this.style.display='none'">
        @else
          <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:40px">📰</div>
        @endif
        @if($blog->category)
          <span class="bcf-cat">{{ $blog->category }}</span>
        @endif
      </div>
      <div class="bcf-body">
        <h3>{{ $blog->title }}</h3>
        @if($blog->excerpt)
          <p class="bcf-excerpt">{{ Str::limit($blog->excerpt,100) }}</p>
        @endif
        <div class="bcf-meta">
          <span>{{ $blog->created_at->format('d M Y') }}</span>
          <span class="bcf-read-more">Read more →</span>
        </div>
      </div>
    </a>
    @empty
    <div class="blog-empty">
      <p>No blog posts yet. Check back soon!</p>
    </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  @if(method_exists($blogs,'links'))
    {{ $blogs->links() }}
  @endif

</div>
@endsection
