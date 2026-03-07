@extends('layouts.admin')
@section('title','Create Variation Product')
@section('page_title','Create Variation Product')

@section('content')
<div style="max-width:700px">
    <div class="data-card">
        <div class="data-card-hdr"><h3>New Product</h3></div>
        <div style="padding:20px">
            <form action="{{ route('admin.vpm.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Product Name *</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Business Cards" value="{{ old('name') }}">
                    @error('name') <small style="color:#dc2626">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" class="form-control" placeholder="e.g. Print" value="{{ old('category') }}">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" placeholder="Product description...">{{ old('description') }}</textarea>
                </div>
                <div style="display:flex;gap:16px">
                    <div class="form-group" style="flex:1">
                        <label>Base Price (£)</label>
                        <input type="number" name="base_price" class="form-control" step="0.01" value="{{ old('base_price', '0') }}">
                    </div>
                    <div class="form-group" style="flex:1">
                        <label>SKU</label>
                        <input type="text" name="sku" class="form-control" placeholder="e.g. BC-001" value="{{ old('sku') }}">
                    </div>
                </div>
                <div style="display:flex;gap:16px;margin-bottom:16px">
                    <div class="form-group" style="flex:1">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex:1">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="form-group" style="margin-bottom:0">
                        <label>Image {{ $i }}</label>
                        <input type="file" name="image{{ $i }}" class="form-control" accept="image/*" style="padding:8px">
                    </div>
                    @endfor
                </div>
                <div style="display:flex;gap:10px">
                    <button type="submit" class="btn-primary">Create & Set Up Variations →</button>
                    <a href="{{ route('admin.vpm.index') }}" class="btn-danger" style="background:none;border:1.5px solid #888;color:#666;padding:10px 22px;border-radius:7px;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
