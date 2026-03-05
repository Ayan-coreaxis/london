@extends('layouts.admin')
@section('title', $blog ? 'Edit Blog Post' : 'New Blog Post')
@section('page_title', $blog ? 'Edit: '.Str::limit($blog->title,40) : 'New Blog Post')

@section('content')
<div style="max-width:860px">
  <a href="{{ route('admin.blogs.index') }}" class="btn-primary btn-sm" style="background:#888;margin-bottom:20px;display:inline-block">
    <i class="fas fa-arrow-left"></i> Back
  </a>

  <form method="POST" action="{{ $blog ? route('admin.blogs.update',$blog->id) : route('admin.blogs.store') }}" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 300px;gap:20px">

      {{-- LEFT --}}
      <div>
        <div class="data-card" style="margin-bottom:20px">
          <div class="data-card-hdr"><h3>Post Content</h3></div>
          <div style="padding:20px">
            <div class="form-group">
              <label>Title *</label>
              <input type="text" name="title" class="form-control" value="{{ old('title',$blog->title ?? '') }}" required placeholder="Blog post title…">
            </div>
            <div class="form-group">
              <label>Excerpt (shown on listing page)</label>
              <textarea name="excerpt" class="form-control" style="height:80px" placeholder="Short description…">{{ old('excerpt',$blog->excerpt ?? '') }}</textarea>
            </div>
            <div class="form-group">
              <label>Body (HTML allowed)</label>
              <textarea name="body" class="form-control" style="height:240px" placeholder="Full blog content…">{{ old('body',$blog->body ?? '') }}</textarea>
            </div>
          </div>
        </div>
      </div>

      {{-- RIGHT --}}
      <div>
        <div class="data-card" style="margin-bottom:16px">
          <div class="data-card-hdr"><h3>Settings</h3></div>
          <div style="padding:18px">
            <div class="form-group">
              <label>Status *</label>
              <select name="status" class="form-control">
                <option value="draft"     {{ old('status',$blog->status??'draft')==='draft'     ?'selected':'' }}>Draft</option>
                <option value="published" {{ old('status',$blog->status??'draft')==='published' ?'selected':'' }}>Published</option>
              </select>
            </div>
            <div class="form-group">
              <label>Category</label>
              <input type="text" name="category" class="form-control" value="{{ old('category',$blog->category??'') }}" placeholder="e.g. Tips, Guide…">
            </div>
            <div class="form-group">
              <label>Author</label>
              <input type="text" name="author" class="form-control" value="{{ old('author',$blog->author??'London InstantPrint') }}">
            </div>
            <div class="form-group">
              <label>Sort Order</label>
              <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order',$blog->sort_order??0) }}" min="0">
            </div>
          </div>
        </div>

        <div class="data-card" style="margin-bottom:16px">
          <div class="data-card-hdr"><h3>Cover Image</h3></div>
          <div style="padding:18px">
            @if($blog && $blog->image)
              <img src="{{ asset($blog->image) }}" alt="" style="width:100%;height:120px;object-fit:cover;border-radius:7px;margin-bottom:12px" onerror="this.style.display='none'">
            @endif
            <input type="file" name="image" class="form-control" accept="image/*" style="height:auto;padding:8px">
            <div style="font-size:11px;color:#aaa;margin-top:6px">JPG, PNG, WebP. Leave empty to keep current.</div>
          </div>
        </div>

        <button type="submit" class="btn-yellow" style="width:100%;justify-content:center">
          <i class="fas fa-save"></i> {{ $blog ? 'Update Post' : 'Publish Post' }}
        </button>
      </div>
    </div>
  </form>
</div>
@endsection
