@extends('layouts.admin')
@section('title','Variation Products')
@section('page_title','Variation Products')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
  <div>
    <p style="color:#888;font-size:13px;margin:0">Manage products with attributes, variations & advanced pricing</p>
  </div>
  <a href="{{ route('admin.vpm.create') }}" class="btn-yellow"><i class="fas fa-plus"></i> Add Variation Product</a>
</div>

<div class="data-card">
  <div class="data-card-hdr">
    <h3>All Products ({{ count($products) }})</h3>
  </div>
  <table class="data-table">
    <thead>
      <tr><th>#</th><th>Product</th><th>Category</th><th>Attributes</th><th>Variations</th><th>Status</th><th>Actions</th></tr>
    </thead>
    <tbody>
    @forelse($products as $p)
    <tr>
      <td style="color:#aaa;font-size:12px">{{ $p->id }}</td>
      <td>
        <div style="font-weight:700;color:#1e3a6e">{{ $p->name }}</div>
        <div style="font-size:11px;color:#aaa">/product/{{ $p->slug }}</div>
      </td>
      <td><span class="badge" style="background:#f0f0f0;color:#555">{{ $p->category ?: '—' }}</span></td>
      <td>
        @if($p->attr_count > 0)
          <span class="badge" style="background:#eef2ff;color:#3730a3;border:1px solid #c7d2fe">{{ $p->attr_count }} attrs</span>
        @else
          <span style="color:#ccc;font-size:12px">—</span>
        @endif
      </td>
      <td>
        @if($p->var_count > 0)
          <span class="badge" style="background:#ecfdf5;color:#059669;border:1px solid #a7f3d0">{{ $p->var_count }} vars</span>
        @else
          <span style="color:#ccc;font-size:12px">—</span>
        @endif
      </td>
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
          <a href="{{ route('admin.vpm.edit',$p->id) }}" class="btn-primary btn-sm" style="background:#059669" title="Edit & Manage Variations"><i class="fas fa-th"></i></a>
          <form method="POST" action="{{ route('admin.vpm.destroy',$p->id) }}" onsubmit="return confirm('Delete {{ $p->name }}?')" style="display:inline">
            @csrf @method('DELETE')
            <button class="btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
          </form>
        </div>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;padding:40px;color:#aaa">No variation products yet. <a href="{{ route('admin.vpm.create') }}" style="color:#1e3a6e">Create your first</a>.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
@endsection
