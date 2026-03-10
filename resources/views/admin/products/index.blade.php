@extends('layouts.admin')
@section('title','Products')
@section('page_title','Product Management')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
  <div style="display:flex;gap:8px">
    <a href="{{ route('admin.products.create') }}" class="btn-yellow"><i class="fas fa-plus"></i> Add Product</a>
  </div>
</div>

@if(session('success'))
  <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

<div class="data-card">
  <div class="data-card-hdr">
    <h3>All Products ({{ count($products) }})</h3>
  </div>
  <table class="data-table">
    <thead>
      <tr><th>#</th><th>Product</th><th>Category</th><th>Base Price</th><th>SKU</th><th>Status</th><th>Actions</th></tr>
    </thead>
    <tbody>
    @forelse($products as $p)
    <tr>
      <td style="color:#aaa;font-size:12px">{{ $p->id }}</td>
      <td>
        <div style="font-weight:700;color:#1e3a6e">{{ $p->name }}</div>
        <div style="font-size:11px;color:#aaa">/product/{{ $p->slug }}</div>
      </td>
      <td><span class="badge" style="background:#f0f0f0;color:#555">{{ $p->category ?? '—' }}</span></td>
      <td><strong>£{{ number_format($p->base_price,2) }}</strong></td>
      <td style="font-size:12px;color:#888;font-family:monospace">{{ $p->sku ?? '—' }}</td>
      <td>
        @if($p->status === 'active')
          <span class="badge" style="background:#e6ffed;color:#156f15;border:1px solid #b2dfb2">Active</span>
        @else
          <span class="badge" style="background:#f0f0f0;color:#888;border:1px solid #ddd">Draft</span>
        @endif
      </td>
      <td>
        <div style="display:flex;gap:6px">
          <a href="{{ route('product.show',$p->slug) }}" target="_blank" class="btn-primary btn-sm" style="background:#888" title="Preview"><i class="fas fa-eye"></i></a>
          <a href="{{ route('admin.products.edit',$p->id) }}" class="btn-primary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>
          <a href="{{ route('admin.products.pricing.get',$p->id) }}" class="btn-primary btn-sm" style="background:#d08800" title="Pricing"><i class="fas fa-tags"></i></a>
          <form method="POST" action="{{ route('admin.products.destroy',$p->id) }}" onsubmit="return confirm('Delete {{ $p->name }}?')" style="display:inline">
            @csrf @method('DELETE')
            <button class="btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
          </form>
        </div>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;padding:40px;color:#aaa">No products yet. <a href="{{ route('admin.products.create') }}" style="color:#1e3a6e">Add your first product</a>.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
@endsection
