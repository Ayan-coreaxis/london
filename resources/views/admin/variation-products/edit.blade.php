@extends('layouts.admin')
@section('title', 'Edit – ' . $product->name)
@section('page_title', 'Variation Product Manager')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
*{box-sizing:border-box}
/* Product Info Card */
.pi-card{background:#fff;border:1px solid #e0e0e0;border-radius:8px;margin-bottom:20px}
.pi-card .pi-head{padding:14px 20px;border-bottom:1px solid #eee;font-weight:700;font-size:14px;color:#222;display:flex;justify-content:space-between;align-items:center;cursor:pointer}
.pi-card .pi-body{padding:20px}
.pi-row{display:flex;gap:16px;margin-bottom:14px;flex-wrap:wrap}
.pi-row .pi-field{flex:1;min-width:200px}
.pi-row .pi-field label{display:block;font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.4px;margin-bottom:5px}
.pi-row .pi-field input,.pi-row .pi-field select,.pi-row .pi-field textarea{width:100%;padding:8px 12px;border:1.5px solid #ddd;border-radius:6px;font-size:13px;font-family:inherit;outline:none}
.pi-row .pi-field textarea{height:60px;resize:vertical}
.pi-row .pi-field input:focus,.pi-row .pi-field select:focus,.pi-row .pi-field textarea:focus{border-color:#1e3a6e}
/* Variation Manager Styles */
.vm-wrap{max-width:1200px;margin:0 auto}
.vm-tabs{display:flex;gap:0;margin-bottom:20px;background:#fff;border-radius:8px;overflow:hidden;border:1px solid #e0e0e0}
.vm-tab{flex:1;padding:12px 16px;text-align:center;cursor:pointer;font-size:13px;font-weight:600;color:#666;background:#fff;border:none;transition:all .2s}
.vm-tab.active{background:#1e3a6e;color:#fff}
.vm-tab:hover:not(.active){background:#f0f2f5}
.card{background:#fff;border:1px solid #e0e0e0;border-radius:8px;margin-bottom:16px}
.card-head{padding:14px 20px;border-bottom:1px solid #eee;font-weight:700;font-size:14px;color:#222;display:flex;justify-content:space-between;align-items:center}
.card-body{padding:20px}
.tag{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:#eef2ff;border:1px solid #c7d2fe;border-radius:4px;font-size:12px;color:#3730a3;margin:2px}
.tag .remove{cursor:pointer;color:#dc2626;font-weight:bold;font-size:14px}
.cb-row{display:flex;gap:16px;font-size:12px;color:#555;margin:8px 0}
.cb-row input[type=checkbox]{accent-color:#1e3a6e;margin-right:4px}
.inp{padding:7px 12px;border:1.5px solid #ddd;border-radius:6px;font-size:13px;outline:none;font-family:inherit;width:100%}
.inp:focus{border-color:#1e3a6e}
.btn{padding:7px 16px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;border:none;font-family:inherit;transition:all .2s;display:inline-flex;align-items:center;gap:5px}
.btn-p{background:#1e3a6e;color:#fff}.btn-p:hover{background:#162d56}
.btn-s{background:#f0f2f5;color:#333;border:1px solid #ddd}.btn-s:hover{background:#e0e2e5}
.btn-d{background:#fee2e2;color:#dc2626;border:1px solid #fca5a5}.btn-d:hover{background:#dc2626;color:#fff}
.btn-g{background:#059669;color:#fff}.btn-g:hover{background:#047857}
.btn-w{background:#f59e0b;color:#fff}.btn-w:hover{background:#d97706}
.dashed{border:2px dashed #d1d5db;border-radius:8px;padding:12px;background:#f9fafb;display:flex;gap:8px}
.var-row{border:1px solid #e5e7eb;border-radius:8px;margin-bottom:8px;background:#fff;overflow:hidden;transition:all .15s}
.var-row.expanded{border-color:#1e3a6e;border-width:2px}
.var-header{padding:10px 14px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px}
.var-selects{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
.var-selects label{font-size:9px;font-weight:700;color:#888;text-transform:uppercase;display:block;margin-bottom:2px}
.var-selects select{padding:5px 8px;font-size:11px;border:1px solid #ddd;border-radius:4px;background:#fff;max-width:160px}
.var-actions{display:flex;gap:6px;align-items:center}
.price-table{width:100%;border-collapse:collapse;font-size:12px}
.price-table th{padding:8px 10px;background:#1e3a6e;color:#fff;text-align:center;font-size:11px}
.price-table th:first-child{text-align:center;width:45px}
.price-table th:nth-child(2){text-align:left}
.price-table td{padding:5px 8px;text-align:center;border-bottom:1px solid #f0f0f0}
.price-table tr.disabled-row{background:#fef2f2;opacity:0.5}
.price-table tr.disabled-row td{text-decoration:line-through}
.price-inp{padding:5px 6px;border:1px solid #ddd;border-radius:4px;font-size:12px;width:75px;text-align:center;outline:none}
.price-inp:focus{border-color:#1e3a6e}
.price-inp:disabled{background:#f5f5f5;color:#999}
.cell-disabled{color:#dc2626;font-weight:600;font-size:11px}
.qty-tag{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:#e0f2fe;border:1px solid #7dd3fc;border-radius:4px;font-size:12px;color:#0369a1;margin:2px}
.turn-row{border:1px solid #e5e7eb;border-radius:6px;padding:12px;margin-bottom:8px;background:#fafafa}
.var-count{font-size:11px;color:#888;margin-bottom:12px}
.save-bar{position:sticky;bottom:0;background:#fff;border-top:2px solid #1e3a6e;padding:14px 20px;display:flex;justify-content:space-between;align-items:center;z-index:50;margin:0 -28px -28px}
.save-msg{font-size:13px;font-weight:600}
.save-msg.ok{color:#059669}.save-msg.err{color:#dc2626}
.empty-state{text-align:center;padding:30px;color:#999;font-size:13px;background:#fafafa;border-radius:8px}
.loading{text-align:center;padding:40px;color:#888}
</style>
@endsection

@section('content')
<div class="vm-wrap">
    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px">
        <div>
            <h2 style="font-size:20px;font-weight:800;color:#1e3a6e;margin:0"><i class="bi bi-grid-3x3-gap" style="margin-right:8px"></i>{{ $product->name }}</h2>
            <small style="color:#888">Product info + attributes, turnarounds, quantities, variations & pricing</small>
        </div>
        <div style="display:flex;gap:8px">
            <a href="{{ route('admin.vpm.index') }}" class="btn btn-s"><i class="bi bi-arrow-left"></i> Back to List</a>
            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-s" target="_blank"><i class="bi bi-eye"></i> View Frontend</a>
        </div>
    </div>

    {{-- ═══ PRODUCT INFO (collapsible) ═══ --}}
    <div class="pi-card">
        <div class="pi-head" onclick="document.getElementById('piBody').style.display = document.getElementById('piBody').style.display === 'none' ? 'block' : 'none'">
            <span><i class="bi bi-info-circle" style="margin-right:6px"></i>Product Information</span>
            <span style="font-size:11px;color:#888;font-weight:400">Click to expand/collapse</span>
        </div>
        <div class="pi-body" id="piBody">
            <form action="{{ route('admin.vpm.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="pi-row">
                    <div class="pi-field" style="flex:2"><label>Product Name</label><input type="text" name="name" value="{{ $product->name }}" required></div>
                    <div class="pi-field"><label>Category</label><input type="text" name="category" value="{{ $product->category }}"></div>
                    <div class="pi-field"><label>SKU</label><input type="text" name="sku" value="{{ $product->sku }}"></div>
                </div>
                <div class="pi-row">
                    <div class="pi-field" style="flex:3"><label>Description</label><textarea name="description">{{ $product->description }}</textarea></div>
                </div>
                <div class="pi-row">
                    <div class="pi-field"><label>Base Price (£)</label><input type="number" name="base_price" step="0.01" value="{{ $product->base_price }}"></div>
                    <div class="pi-field"><label>Status</label><select name="status"><option value="active" {{ $product->status=='active'?'selected':'' }}>Active</option><option value="draft" {{ $product->status=='draft'?'selected':'' }}>Draft</option></select></div>
                    <div class="pi-field"><label>Sort Order</label><input type="number" name="sort_order" value="{{ $product->sort_order ?? 0 }}"></div>
                </div>
                <div class="pi-row">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="pi-field">
                        <label>Image {{ $i }} @if($product->{"image$i"}) <span style="color:#059669">✓</span> @endif</label>
                        <input type="file" name="image{{ $i }}" accept="image/*" style="padding:6px">
                    </div>
                    @endfor
                </div>
                <button type="submit" class="btn btn-p" style="padding:9px 22px"><i class="bi bi-check-circle"></i> Save Product Info</button>
            </form>
        </div>
    </div>

    {{-- ═══ VARIATION MANAGER TABS ═══ --}}
    <div class="vm-tabs">
        <button class="vm-tab active" onclick="switchTab('attributes',this)">1. Attributes</button>
        <button class="vm-tab" onclick="switchTab('turnarounds',this)">2. Turnarounds</button>
        <button class="vm-tab" onclick="switchTab('quantities',this)">3. Quantities</button>
        <button class="vm-tab" onclick="switchTab('variations',this)">4. Variations & Pricing</button>
    </div>

    <div id="loadingState" class="loading"><i class="bi bi-arrow-repeat"></i> Loading data...</div>

    {{-- TAB 1: ATTRIBUTES --}}
    <div id="tab-attributes" class="tab-panel" style="display:none">
        <div class="card">
            <div class="card-head">Attributes <span id="attrCount" class="badge" style="background:#1e3a6e;color:#fff;padding:4px 12px;border-radius:20px;font-size:12px">0</span></div>
            <div class="card-body">
                <div id="attrList"></div>
                <div class="dashed" style="margin-top:12px">
                    <input class="inp" id="newAttrName" placeholder="New attribute name (e.g. Embellishment)" onkeydown="if(event.key==='Enter')addAttribute()">
                    <button class="btn btn-p" onclick="addAttribute()"><i class="bi bi-plus"></i> Add Attribute</button>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 2: TURNAROUNDS --}}
    <div id="tab-turnarounds" class="tab-panel" style="display:none">
        <div class="card">
            <div class="card-head">Turnarounds (Delivery Speeds) <button class="btn btn-p" onclick="addTurnaround()"><i class="bi bi-plus"></i> Add</button></div>
            <div class="card-body"><div id="turnList"></div></div>
        </div>
    </div>

    {{-- TAB 3: QUANTITIES --}}
    <div id="tab-quantities" class="tab-panel" style="display:none">
        <div class="card">
            <div class="card-head">Quantity Options</div>
            <div class="card-body">
                <div id="qtyTags" style="margin-bottom:12px"></div>
                <div class="dashed">
                    <input class="inp" id="newQtyInput" type="number" placeholder="e.g. 2000" style="width:140px" onkeydown="if(event.key==='Enter')addQuantity()">
                    <button class="btn btn-s" onclick="addQuantity()"><i class="bi bi-plus"></i> Add Qty</button>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 4: VARIATIONS --}}
    <div id="tab-variations" class="tab-panel" style="display:none">
        <div class="card">
            <div class="card-head">Variations & Pricing <span id="varCount" class="badge" style="background:#1e3a6e;color:#fff;padding:4px 12px;border-radius:20px;font-size:12px">0</span></div>
            <div class="card-body">
                <div style="display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap">
                    <button class="btn btn-g" onclick="autoGenerate()"><i class="bi bi-lightning"></i> Auto Generate All</button>
                    <button class="btn btn-s" onclick="addSingleVariation()"><i class="bi bi-plus"></i> Add Single</button>
                    <button class="btn btn-d" onclick="if(confirm('Remove all variations?')){DATA.variations=[];renderVariations();}"><i class="bi bi-trash"></i> Remove All</button>
                </div>
                <div class="var-count" id="varInfo"></div>
                <div id="varList"></div>
            </div>
        </div>
    </div>

    {{-- SAVE BAR --}}
    <div class="save-bar">
        <div id="saveMsg" class="save-msg"></div>
        <button class="btn btn-p" onclick="saveAll()" style="padding:10px 28px;font-size:14px">
            <i class="bi bi-check-circle"></i> Save All Changes
        </button>
    </div>
</div>

@endsection

@section('scripts')
@include('admin.variation-products._variation_js', ['product' => $product])
@endsection
