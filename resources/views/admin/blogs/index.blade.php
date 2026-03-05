@extends('layouts.admin')
@section('title','Blog Posts')
@section('page_title','Blog Management')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
  <div></div>
  <a href="{{ route('admin.blogs.create') }}" class="btn-yellow"><i class="fas fa-plus"></i> New Blog Post</a>
</div>

<div class="data-card">
  <div class="data-card-hdr">
    <h3><i class="fas fa-blog" style="color:#1e3a6e;margin-right:6px"></i>All Posts ({{ $blogs->count() }})</h3>
  </div>
  <table class="data-table">
    <thead>
      <tr><th>Title</th><th>Category</th><th>Author</th><th>Status</th><th>Order</th><th>Date</th><th></th></tr>
    </thead>
    <tbody>
    @forelse($blogs as $blog)
    <tr>
      <td>
        <div style="display:flex;align-items:center;gap:12px">
          @if($blog->image)
            <img src="{{ asset($blog->image) }}" alt="" style="width:52px;height:40px;border-radius:5px;object-fit:cover;flex-shrink:0" onerror="this.style.display='none'">
          @else
            <div style="width:52px;height:40px;border-radius:5px;background:#eef3ff;display:flex;align-items:center;justify-content:center;flex-shrink:0"><i class="fas fa-image" style="color:#1e3a6e;opacity:.4"></i></div>
          @endif
          <div>
            <div style="font-weight:600;font-size:13px">{{ Str::limit($blog->title,60) }}</div>
            @if($blog->excerpt)<div style="font-size:11px;color:#aaa">{{ Str::limit($blog->excerpt,80) }}</div>@endif
          </div>
        </div>
      </td>
      <td><span style="background:#eef3ff;color:#1e3a6e;padding:2px 9px;border-radius:10px;font-size:11px;font-weight:700">{{ $blog->category ?: '—' }}</span></td>
      <td style="font-size:12px;color:#888">{{ $blog->author }}</td>
      <td>
        @if($blog->status === 'published')
          <span class="badge" style="background:#e6ffed;color:#1a7a1a;border:1px solid #99dd99">Published</span>
        @else
          <span class="badge" style="background:#f5f5f5;color:#888;border:1px solid #ddd">Draft</span>
        @endif
      </td>
      <td style="color:#aaa;font-size:12px">{{ $blog->sort_order }}</td>
      <td style="font-size:12px;color:#aaa">{{ $blog->created_at->format('d M Y') }}</td>
      <td style="display:flex;gap:6px">
        <a href="{{ route('admin.blogs.edit',$blog->id) }}" class="btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
        <form method="POST" action="{{ route('admin.blogs.destroy',$blog->id) }}" onsubmit="return confirm('Delete this post?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn-danger"><i class="fas fa-trash"></i></button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;padding:40px;color:#aaa">No blog posts yet</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
@endsection
