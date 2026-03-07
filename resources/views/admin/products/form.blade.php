@extends('layouts.admin')
@section('title', isset($editing) && $editing ? 'Edit Product' : 'Add Product')
@section('page_title', isset($editing) && $editing ? 'Edit Product' : 'Add Product')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
.sec-card{background:#fff;border:1px solid #e8e8e8;border-radius:8px;margin-bottom:20px;}
.sec-card .sec-head{padding:14px 20px;font-weight:700;font-size:14px;border-bottom:1px solid #e8e8e8;display:flex;justify-content:space-between;align-items:center;color:#222;}
.sec-card .sec-body{padding:20px;}
.option-row{background:#fafafa;border:1px solid #e0e0e0;border-radius:8px;padding:14px;margin-bottom:10px;position:relative;padding-top:40px;}
.option-row .rm{position:absolute;top:10px;right:10px;}
.row{display:flex;flex-wrap:wrap;margin:0 -8px;}
.col-lg-8{flex:0 0 66.666%;max-width:66.666%;padding:0 8px;}
.col-lg-4{flex:0 0 33.333%;max-width:33.333%;padding:0 8px;}
.col-md-3{flex:0 0 25%;max-width:25%;padding:0 8px;}
.col-md-4{flex:0 0 33.333%;max-width:33.333%;padding:0 8px;}
.col-md-5{flex:0 0 41.666%;max-width:41.666%;padding:0 8px;}
.col-6{flex:0 0 50%;max-width:50%;padding:0 8px;}
.g-2{gap:8px;}.g-3{gap:12px;}.g-4{gap:16px;}
.mb-0{margin-bottom:0}.mb-2{margin-bottom:8px}.mb-3{margin-bottom:12px}.mt-3{margin-top:12px}.mt-1{margin-top:4px}
.form-label{display:block;font-size:12px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;}
.form-control,.form-select{width:100%;height:42px;border:1.5px solid #ddd;border-radius:7px;padding:0 13px;font-size:14px;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s;background:#fff;}
.form-control:focus,.form-select:focus{border-color:#1e3a6e;box-shadow:0 0 0 3px rgba(30,58,110,.08);}
textarea.form-control{height:80px;padding:12px 13px;resize:vertical;}
.form-control-sm{height:34px;font-size:12px;padding:0 10px;}
.btn{padding:8px 16px;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;border:none;font-family:'Inter',sans-serif;transition:all .2s;text-decoration:none;display:inline-block;}
.btn-primary{background:#1e3a6e;color:#fff;}.btn-primary:hover{background:#162d56;}
.btn-sm{padding:5px 12px;font-size:12px;}
.btn-outline-primary{background:none;border:1.5px solid #1e3a6e;color:#1e3a6e;}.btn-outline-primary:hover{background:#1e3a6e;color:#fff;}
.btn-outline-secondary{background:none;border:1.5px solid #888;color:#666;}.btn-outline-secondary:hover{background:#888;color:#fff;}
.btn-outline-danger{background:none;border:1.5px solid #dc3545;color:#dc3545;}.btn-outline-danger:hover{background:#dc3545;color:#fff;}
.btn-warning{background:#f5c800;color:#111;}.btn-warning:hover{background:#e0b400;}
.d-flex{display:flex;}.gap-2{gap:8px;}.gap-1{gap:4px;}
.justify-content-between{justify-content:space-between;}
.align-items-center{align-items:center;}
.text-danger{color:#dc3545;}.text-muted{color:#888;}.fw-normal{font-weight:400;}.fw-bold{font-weight:700;}
.text-center{text-align:center;}
.alert{padding:12px 16px;border-radius:7px;margin-bottom:16px;font-size:13px;font-weight:600;}
.alert-success{background:#f0faf0;border:1px solid #c6e8c6;color:#2a7a2a;}
.alert-danger{background:#fff0f0;border:1px solid #ffcccc;color:#cc0000;}
.alert-dismissible{position:relative;padding-right:40px;}
.btn-close{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;font-size:18px;cursor:pointer;color:#666;}
.table{width:100%;border-collapse:collapse;}
.table th{padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#888;text-transform:uppercase;border-bottom:1px solid #eee;background:#fafafa;}
.table td{padding:12px 14px;font-size:13px;border-bottom:1px solid #f5f5f5;}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;}
.bg-success{background:#e6ffed;color:#156f15;}.bg-secondary{background:#f0f0f0;color:#666;}
.bg-light{background:#f0f0f0;}.text-dark{color:#333;}
</style>
@endsection

@section('content')

    @php $editing = isset($isEdit) && $isEdit; @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $editing ? 'Edit Product' : 'Add New Product' }}</h4>
        <div class="d-flex gap-2">
            @if($editing)
                <a href="{{ route('product.show', $product->slug) }}" class="btn btn-sm btn-outline-primary" target="_blank"><i class="bi bi-eye me-1"></i>View</a>
            @endif
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    @endif

    {{-- ✅ FIX: @method('PUT') hata diya --}}
    <form method="POST"
        action="{{ $editing ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
        enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
        <div class="col-lg-8">

            {{-- Basic Info --}}
            <div class="sec-card">
                <div class="sec-head">Basic Information</div>
                <div class="sec-body">
                    <div class="mb-3">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $editing ? $product->name : '') }}" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                @foreach(['Business Cards','Flyers & Leaflets','Posters','Banners','Stationery','Other'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $editing ? $product->category : '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Base Price (£)</label>
                            <input type="number" name="base_price" class="form-control" step="0.01" min="0"
                                value="{{ old('base_price', $editing ? $product->base_price : '0.00') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control"
                                value="{{ old('sku', $editing ? $product->sku : '') }}" placeholder="BC-001">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $editing ? $product->description : '') }}</textarea>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status', $editing ? $product->status : 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="draft"  {{ old('status', $editing ? $product->status : '')       === 'draft'  ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" min="0"
                                value="{{ old('sort_order', $editing ? ($product->sort_order ?? 0) : 0) }}">
                        </div>
                    </div>

                    {{-- 4 Product Images --}}
                    <div class="mt-3">
                        <label class="form-label fw-bold">Product Images <small class="text-muted fw-normal">(Max 4 — Gallery pe show hongi)</small></label>
                        <div class="row g-2">
                            @for($img = 1; $img <= 4; $img++)
                            <div class="col-6 col-md-3">
                                <div class="border rounded p-2 text-center" style="background:#fafafa;">
                                    <div style="height:80px;display:flex;align-items:center;justify-content:center;margin-bottom:8px;overflow:hidden;border-radius:4px;background:#eee;">
                                        {{-- ✅ FIX: asset('storage/...') hataya, seedha asset(...) --}}
                                        @if($editing && !empty($product->{'image'.$img}))
                                            <img id="imgPreview{{ $img }}" src="{{ asset($product->{'image'.$img}) }}"
                                                 style="max-height:80px;max-width:100%;object-fit:cover;">
                                        @else
                                            <img id="imgPreview{{ $img }}" src="" style="max-height:80px;max-width:100%;object-fit:cover;display:none;">
                                            <span id="imgPlaceholder{{ $img }}" style="font-size:11px;color:#aaa;">Image {{ $img }}</span>
                                        @endif
                                    </div>
                                    <label style="font-size:11px;font-weight:600;color:#555;display:block;margin-bottom:4px;">
                                        {{ $img === 1 ? 'Main Image' : 'Image '.$img }}
                                    </label>
                                    <input type="file" name="image{{ $img }}" class="form-control form-control-sm"
                                           accept="image/*" style="font-size:11px;"
                                           onchange="previewImg(this, {{ $img }})">
                                </div>
                            </div>
                            @endfor
                        </div>
                        <small class="text-muted">Image 1 = Main display image. Baaki 3 gallery thumbnails mein aayengi.</small>
                    </div>
                </div>
            </div>

            {{-- Variants --}}
            <div class="sec-card">
                <div class="sec-head">
                    Variants <small class="text-muted fw-normal">(tabs on product page)</small>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addVariant()"><i class="bi bi-plus me-1"></i>Add</button>
                </div>
                <div class="sec-body">
                    <div id="variantsWrap">
                        @if($editing)
                            @foreach($variants as $v)
                            <div class="d-flex gap-2 mb-2 vr-row">
                                <input type="text" name="variant_name[]" class="form-control form-control-sm" value="{{ $v->name }}" placeholder="Variant Name">
                                <input type="text" name="variant_slug[]" class="form-control form-control-sm" value="{{ $v->link_slug }}" placeholder="link-slug">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.vr-row').remove()"><i class="bi bi-x"></i></button>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <small class="text-muted">Slug = URL of linked product (e.g. folded-business-cards)</small>
                </div>
            </div>

            {{-- Presets --}}
            <div class="sec-card">
                <div class="sec-head">
                    Presets <small class="text-muted fw-normal">("Most Popular" jaise badges)</small>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addPreset()"><i class="bi bi-plus me-1"></i>Add</button>
                </div>
                <div class="sec-body">
                    <div id="presetsWrap">
                        @if($editing)
                            @foreach($presets as $p)
                            <div class="d-flex gap-2 mb-2 pr-row">
                                <input type="text" name="preset_label[]" class="form-control form-control-sm" value="{{ $p->label }}" placeholder="Most Popular">
                                <input type="text" name="preset_description[]" class="form-control form-control-sm" value="{{ $p->description }}" placeholder="Short description...">
                                <input type="color" name="preset_color[]" class="form-control form-control-color form-control-sm" value="{{ $p->badge_color }}" style="width:46px">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.pr-row').remove()"><i class="bi bi-x"></i></button>
                            </div>
                            @endforeach
                        @else
                            <div class="d-flex gap-2 mb-2 pr-row">
                                <input type="text" name="preset_label[]" class="form-control form-control-sm" value="Most Popular" placeholder="Most Popular">
                                <input type="text" name="preset_description[]" class="form-control form-control-sm" value="85mm x 55mm, 400gsm Silk, matt lamination." placeholder="Short description...">
                                <input type="color" name="preset_color[]" class="form-control form-control-color form-control-sm" value="#d93025" style="width:46px">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.pr-row').remove()"><i class="bi bi-x"></i></button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Options --}}
            <div class="sec-card">
                <div class="sec-head">
                    Product Options <small class="text-muted fw-normal">(dropdowns & toggles)</small>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addOption()"><i class="bi bi-plus me-1"></i>Add Option</button>
                </div>
                <div class="sec-body">
                    <p class="text-muted small mb-3">Values aur prices same line number pe likhein. 0 = no charge.</p>
                    <div id="optionsWrap">
                        @if($editing)
                            @foreach($optionsRaw as $o)
                            <div class="option-row">
                                <button type="button" class="btn btn-sm btn-outline-danger rm" onclick="this.closest('.option-row').remove()"><i class="bi bi-x"></i></button>
                                <div class="row g-2 mb-2">
                                    <div class="col-md-7">
                                        <label class="form-label small mb-1">Option Name</label>
                                        <input type="text" name="option_name[]" class="form-control form-control-sm" value="{{ $o->option_name }}">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small mb-1">Display As</label>
                                        <select name="display_type[]" class="form-select form-select-sm">
                                            <option value="dropdown" {{ $o->display_type === 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                                            <option value="buttons"  {{ $o->display_type === 'buttons'  ? 'selected' : '' }}>Button Toggle</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-7">
                                        <label class="form-label small mb-1">Values (one per line)</label>
                                        <textarea name="option_values[]" class="form-control form-control-sm" rows="4">{{ $o->values_str }}</textarea>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small mb-1">Extra Price £ (one per line)</label>
                                        <textarea name="option_prices[]" class="form-control form-control-sm" rows="4">{{ $o->prices_str }}</textarea>
                                        <div class="price-hint">0 = no charge</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="option-row">
                                <button type="button" class="btn btn-sm btn-outline-danger rm" onclick="this.closest('.option-row').remove()"><i class="bi bi-x"></i></button>
                                <div class="row g-2 mb-2">
                                    <div class="col-md-7"><label class="form-label small mb-1">Option Name</label><input type="text" name="option_name[]" class="form-control form-control-sm" value="Finished Size"></div>
                                    <div class="col-md-5"><label class="form-label small mb-1">Display As</label><select name="display_type[]" class="form-select form-select-sm"><option value="dropdown" selected>Dropdown</option><option value="buttons">Button Toggle</option></select></div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-7"><label class="form-label small mb-1">Values (one per line)</label><textarea name="option_values[]" class="form-control form-control-sm" rows="5">85mm x 55mm (Square Corners)
85mm x 55mm (Round Corners)
A6
A5
A4</textarea></div>
                                    <div class="col-md-5"><label class="form-label small mb-1">Extra Price £</label><textarea name="option_prices[]" class="form-control form-control-sm" rows="5">0
0
1.00
2.00
3.50</textarea><div class="price-hint">0 = no charge</div></div>
                                </div>
                            </div>
                            <div class="option-row">
                                <button type="button" class="btn btn-sm btn-outline-danger rm" onclick="this.closest('.option-row').remove()"><i class="bi bi-x"></i></button>
                                <div class="row g-2 mb-2">
                                    <div class="col-md-7"><label class="form-label small mb-1">Option Name</label><input type="text" name="option_name[]" class="form-control form-control-sm" value="Printed Sides"></div>
                                    <div class="col-md-5"><label class="form-label small mb-1">Display As</label><select name="display_type[]" class="form-select form-select-sm"><option value="dropdown">Dropdown</option><option value="buttons" selected>Button Toggle</option></select></div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-7"><label class="form-label small mb-1">Values (one per line)</label><textarea name="option_values[]" class="form-control form-control-sm" rows="3">Single Sided
Double Sided</textarea></div>
                                    <div class="col-md-5"><label class="form-label small mb-1">Extra Price £</label><textarea name="option_prices[]" class="form-control form-control-sm" rows="3">0
3.00</textarea><div class="price-hint">0 = no charge</div></div>
                                </div>
                            </div>
                            <div class="option-row">
                                <button type="button" class="btn btn-sm btn-outline-danger rm" onclick="this.closest('.option-row').remove()"><i class="bi bi-x"></i></button>
                                <div class="row g-2 mb-2">
                                    <div class="col-md-7"><label class="form-label small mb-1">Option Name</label><input type="text" name="option_name[]" class="form-control form-control-sm" value="Stock"></div>
                                    <div class="col-md-5"><label class="form-label small mb-1">Display As</label><select name="display_type[]" class="form-select form-select-sm"><option value="dropdown" selected>Dropdown</option><option value="buttons">Button Toggle</option></select></div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-7"><label class="form-label small mb-1">Values (one per line)</label><textarea name="option_values[]" class="form-control form-control-sm" rows="5">115gsm Silk
350gsm Silk
450gsm Silk
400gsm Uncoated</textarea></div>
                                    <div class="col-md-5"><label class="form-label small mb-1">Extra Price £</label><textarea name="option_prices[]" class="form-control form-control-sm" rows="5">0
2.00
4.00
1.50</textarea><div class="price-hint">0 = no charge</div></div>
                                </div>
                            </div>
                            <div class="option-row">
                                <button type="button" class="btn btn-sm btn-outline-danger rm" onclick="this.closest('.option-row').remove()"><i class="bi bi-x"></i></button>
                                <div class="row g-2 mb-2">
                                    <div class="col-md-7"><label class="form-label small mb-1">Option Name</label><input type="text" name="option_name[]" class="form-control form-control-sm" value="Lamination"></div>
                                    <div class="col-md-5"><label class="form-label small mb-1">Display As</label><select name="display_type[]" class="form-select form-select-sm"><option value="dropdown" selected>Dropdown</option><option value="buttons">Button Toggle</option></select></div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-7"><label class="form-label small mb-1">Values (one per line)</label><textarea name="option_values[]" class="form-control form-control-sm" rows="5">No Lamination
Gloss Lamination
Matt Lamination
Soft Touch Lamination</textarea></div>
                                    <div class="col-md-5"><label class="form-label small mb-1">Extra Price £</label><textarea name="option_prices[]" class="form-control form-control-sm" rows="5">0
2.50
2.50
4.00</textarea><div class="price-hint">0 = no charge</div></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Product Info Accordion Texts (editable per product) --}}
            <div class="sec-card">
                <div class="sec-head">Product Info Accordion</div>
                <div class="sec-body">
                    <p style="font-size:12px;color:#888;margin-bottom:12px">These texts appear in the accordion on the product page left side. Leave blank to use defaults.</p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Artwork Setup</label>
                        <textarea name="artwork_setup_text" class="form-control" rows="2" placeholder="Default: Upload your print-ready artwork in PDF, AI, EPS...">{{ $isEdit ? ($product->artwork_setup_text ?? '') : '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Artwork Templates</label>
                        <textarea name="artwork_templates_text" class="form-control" rows="2" placeholder="Default: Download our free artwork templates...">{{ $isEdit ? ($product->artwork_templates_text ?? '') : '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Technical Specification</label>
                        <textarea name="technical_spec_text" class="form-control" rows="2" placeholder="Default: Standard size: 85mm × 55mm...">{{ $isEdit ? ($product->technical_spec_text ?? '') : '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Key Information</label>
                        <textarea name="key_info_text" class="form-control" rows="2" placeholder="Default: All orders are checked by our 30-point artwork review team...">{{ $isEdit ? ($product->key_info_text ?? '') : '' }}</textarea>
                    </div>
                </div>
            </div>

            {{-- FAQs --}}
            <div class="sec-card">
                <div class="sec-head">
                    FAQs
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFaq()"><i class="bi bi-plus me-1"></i>Add</button>
                </div>
                <div class="sec-body">
                    <div id="faqsWrap">
                        @if($editing)
                            @foreach($faqs as $f)
                            <div class="mb-3 fq-row">
                                <div class="d-flex gap-2 mb-1">
                                    <input type="text" name="faq_question[]" class="form-control form-control-sm" value="{{ $f->question }}" placeholder="Question">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.fq-row').remove()"><i class="bi bi-x"></i></button>
                                </div>
                                <textarea name="faq_answer[]" class="form-control form-control-sm" rows="2">{{ $f->answer }}</textarea>
                            </div>
                            @endforeach
                        @else
                            <div class="mb-3 fq-row">
                                <div class="d-flex gap-2 mb-1">
                                    <input type="text" name="faq_question[]" class="form-control form-control-sm" value="How long will my print take to arrive?" placeholder="Question">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.fq-row').remove()"><i class="bi bi-x"></i></button>
                                </div>
                                <textarea name="faq_answer[]" class="form-control form-control-sm" rows="2">Find out how quickly you can get your print by filling in your selected options in the product builder.</textarea>
                            </div>
                            <div class="mb-3 fq-row">
                                <div class="d-flex gap-2 mb-1">
                                    <input type="text" name="faq_question[]" class="form-control form-control-sm" value="How do I set up my artwork for print?" placeholder="Question">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.fq-row').remove()"><i class="bi bi-x"></i></button>
                                </div>
                                <textarea name="faq_answer[]" class="form-control form-control-sm" rows="2">Ensure your file is in CMYK colour mode, at 300 DPI resolution, and includes a 3mm bleed on all sides.</textarea>
                            </div>
                            <div class="mb-3 fq-row">
                                <div class="d-flex gap-2 mb-1">
                                    <input type="text" name="faq_question[]" class="form-control form-control-sm" value="What is the standard Business Card size?" placeholder="Question">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.fq-row').remove()"><i class="bi bi-x"></i></button>
                                </div>
                                <textarea name="faq_answer[]" class="form-control form-control-sm" rows="2">The standard UK Business Card size is 85mm x 55mm.</textarea>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

            {{-- Pricing Table --}}
            @if($editing)
            <div class="sec-card" id="pricingCard">
                <div class="sec-head">
                    Turnaround & Pricing
                    <button type="button" class="btn btn-sm btn-primary" onclick="addTurnaround()"><i class="bi bi-plus me-1"></i>Add Turnaround</button>
                </div>
                <div class="sec-body">
                    <p class="text-muted small mb-3">
                        Har turnaround ke liye quantities aur prices likho. Quantities aur Prices <strong>comma-separated</strong> likhein.<br>
                        Example: <code>50, 100, 250, 500</code> &nbsp;|&nbsp; <code>12.99, 19.99, 34.99, 59.99</code>
                    </p>
                    <div id="turnaroundsWrap">
                        <div class="text-center py-3 text-muted small" id="pricingLoading">
                            <div class="spinner-border spinner-border-sm me-2"></div> Loading...
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <button type="button" class="btn btn-success" onclick="savePricing()">
                            <i class="bi bi-check-circle me-1"></i>Save Pricing Table
                        </button>
                        <div id="pricingSaveMsg" class="align-self-center small"></div>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-info small">
                <i class="bi bi-info-circle me-2"></i>Product save karne ke baad Turnaround &amp; Pricing section available ho ga.
            </div>
            @endif

        {{-- RIGHT SIDEBAR --}}
        <div class="col-lg-4">
            <div class="sec-card sticky-top" style="top:20px">
                <div class="sec-head">{{ $editing ? 'Save Changes' : 'Publish' }}</div>
                <div class="sec-body d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>{{ $editing ? 'Update Product' : 'Save Product' }}
                    </button>
                    @if($editing)
                        <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="bi bi-eye me-1"></i>Preview
                        </a>
                        <button type="button" class="btn btn-outline-danger w-100"
                            onclick="if(confirm('Delete this product?')) document.getElementById('deleteForm').submit()">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    @endif
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>

            <div class="sec-card mt-3">
                <div class="sec-head">Pricing Help</div>
                <div class="sec-body small text-muted">
                    <p><strong>Options Extra Prices:</strong><br>User jab option select kare ga, extra price base price mein add ho gi.</p>
                    <p><strong>Turnaround Pricing:</strong><br>Agar quantity match kare turnaround table se toh woh price use ho gi — warna base price + extras.</p>
                    <p class="mb-0"><strong>Priority:</strong> Turnaround table &gt; Base price</p>
                </div>
            </div>
        </div>
        </div>
    </form>
</div>
</div>

<script>
function addOption() {
    var html = '<div class="option-row">'
        + '<button type="button" class="btn btn-sm btn-outline-danger rm" onclick="this.closest(\'.option-row\').remove()"><i class="bi bi-x"></i></button>'
        + '<div class="row g-2 mb-2">'
        + '<div class="col-md-7"><label class="form-label small mb-1">Option Name</label><input type="text" name="option_name[]" class="form-control form-control-sm" placeholder="e.g. Paper Weight"></div>'
        + '<div class="col-md-5"><label class="form-label small mb-1">Display As</label><select name="display_type[]" class="form-select form-select-sm"><option value="dropdown">Dropdown</option><option value="buttons">Button Toggle</option></select></div>'
        + '</div>'
        + '<div class="row g-2">'
        + '<div class="col-md-7"><label class="form-label small mb-1">Values (one per line)</label><textarea name="option_values[]" class="form-control form-control-sm" rows="4" placeholder="Option 1&#10;Option 2"></textarea></div>'
        + '<div class="col-md-5"><label class="form-label small mb-1">Extra Price £</label><textarea name="option_prices[]" class="form-control form-control-sm" rows="4" placeholder="0&#10;2.50"></textarea><div class="price-hint">0 = no charge</div></div>'
        + '</div></div>';
    document.getElementById('optionsWrap').insertAdjacentHTML('beforeend', html);
}

function addVariant() {
    var html = '<div class="d-flex gap-2 mb-2 vr-row">'
        + '<input type="text" name="variant_name[]" class="form-control form-control-sm" placeholder="Variant Name">'
        + '<input type="text" name="variant_slug[]" class="form-control form-control-sm" placeholder="link-slug">'
        + '<button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest(\'.vr-row\').remove()"><i class="bi bi-x"></i></button>'
        + '</div>';
    document.getElementById('variantsWrap').insertAdjacentHTML('beforeend', html);
}

function addPreset() {
    var html = '<div class="d-flex gap-2 mb-2 pr-row">'
        + '<input type="text" name="preset_label[]" class="form-control form-control-sm" placeholder="Label">'
        + '<input type="text" name="preset_description[]" class="form-control form-control-sm" placeholder="Description">'
        + '<input type="color" name="preset_color[]" class="form-control form-control-color form-control-sm" value="#d93025" style="width:46px">'
        + '<button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest(\'.pr-row\').remove()"><i class="bi bi-x"></i></button>'
        + '</div>';
    document.getElementById('presetsWrap').insertAdjacentHTML('beforeend', html);
}

function previewImg(input, num) {
    var preview = document.getElementById('imgPreview' + num);
    var placeholder = document.getElementById('imgPlaceholder' + num);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

var productId = {{ $editing ? $product->id : 'null' }};

function removeTaRow(btn) {
    btn.closest('.ta-row').remove();
}

function turnaroundRowHtml(t) {
    var qties  = (t && t.quantities)  ? String(t.quantities).trim()  : '';
    var prices = (t && t.prices)      ? String(t.prices).trim()      : '';
    var label  = t ? (t.label || '') : '';
    var wdMin  = t ? (t.working_days_min || 1) : 1;
    var wdMax  = t ? (t.working_days_max || 1) : 1;
    var dead   = t ? (t.artwork_deadline || '6:30pm') : '6:30pm';

    var d = document.createElement('div');
    d.className = 'ta-row border rounded p-3 mb-3 position-relative';
    d.innerHTML =
        '<button type="button" class="btn btn-sm btn-outline-danger position-absolute" style="top:10px;right:10px" onclick="removeTaRow(this)"><i class="bi bi-x"></i></button>'
        + '<div class="row g-2 mb-2">'
        + '<div class="col-md-4"><label class="form-label small mb-1 fw-bold">Turnaround Label</label><input type="text" name="turnaround_label[]" class="form-control form-control-sm" placeholder="e.g. Express"></div>'
        + '<div class="col-md-2"><label class="form-label small mb-1 fw-bold">Days Min</label><input type="number" name="working_days_min[]" class="form-control form-control-sm" min="1" max="30"></div>'
        + '<div class="col-md-2"><label class="form-label small mb-1 fw-bold">Days Max</label><input type="number" name="working_days_max[]" class="form-control form-control-sm" min="1" max="30"></div>'
        + '<div class="col-md-4"><label class="form-label small mb-1 fw-bold">Artwork Deadline</label><input type="text" name="artwork_deadline[]" class="form-control form-control-sm" placeholder="6:30pm"></div>'
        + '</div>'
        + '<div class="row g-2">'
        + '<div class="col-md-6"><label class="form-label small mb-1">Quantities <small class="text-muted">(comma separated)</small></label><input type="text" name="quantities[]" class="form-control form-control-sm" placeholder="50, 100, 250, 500, 1000"></div>'
        + '<div class="col-md-6"><label class="form-label small mb-1">Prices £ <small class="text-muted">(comma separated)</small></label><input type="text" name="prices[]" class="form-control form-control-sm" placeholder="9.99, 14.99, 24.99, 39.99, 59.99"></div>'
        + '</div>';

    d.querySelector('[name="turnaround_label[]"]').value = label;
    d.querySelector('[name="working_days_min[]"]').value = wdMin;
    d.querySelector('[name="working_days_max[]"]').value = wdMax;
    d.querySelector('[name="artwork_deadline[]"]').value  = dead;
    d.querySelector('[name="quantities[]"]').value        = qties;
    d.querySelector('[name="prices[]"]').value            = prices;

    return d;
}

function addTurnaround() {
    document.getElementById('turnaroundsWrap').appendChild(turnaroundRowHtml(null));
}

function loadPricing() {
    if (!productId) return;
    fetch('/admin/products/' + productId + '/pricing', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(function(r) {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(function(data) {
        var wrap = document.getElementById('turnaroundsWrap');
        wrap.innerHTML = '';
        if (!data || !data.length) {
            wrap.innerHTML = '<p class="text-muted small mb-0">Koi turnaround nahi — "Add Turnaround" se add karo.</p>';
            return;
        }
        data.forEach(function(t) { wrap.appendChild(turnaroundRowHtml(t)); });
    })
    .catch(function(err) {
        document.getElementById('turnaroundsWrap').innerHTML = '<p class="text-danger small mb-0">Load failed: ' + err.message + '</p>';
    });
}

function savePricing() {
    if (!productId) return;
    var rows = document.querySelectorAll('.ta-row');
    var body = new URLSearchParams();
    body.append('_token', '{{ csrf_token() }}');
    rows.forEach(function(row) {
        body.append('turnaround_label[]', row.querySelector('[name="turnaround_label[]"]').value);
        body.append('working_days_min[]', row.querySelector('[name="working_days_min[]"]').value);
        body.append('working_days_max[]', row.querySelector('[name="working_days_max[]"]').value);
        body.append('artwork_deadline[]', row.querySelector('[name="artwork_deadline[]"]').value);
        body.append('quantities[]',       row.querySelector('[name="quantities[]"]').value);
        body.append('prices[]',           row.querySelector('[name="prices[]"]').value);
    });
    var msg = document.getElementById('pricingSaveMsg');
    msg.innerHTML = '<span class="text-muted">Saving...</span>';
    fetch('/admin/products/' + productId + '/pricing', {
        method: 'POST',
        body: body,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function() {
        msg.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Saved!</span>';
        setTimeout(function(){ msg.innerHTML = ''; }, 3000);
    })
    .catch(function() { msg.innerHTML = '<span class="text-danger">Error saving!</span>'; });
}

document.addEventListener('DOMContentLoaded', function() { loadPricing(); });

function addFaq() {
    var html = '<div class="mb-3 fq-row">'
        + '<div class="d-flex gap-2 mb-1">'
        + '<input type="text" name="faq_question[]" class="form-control form-control-sm" placeholder="Question">'
        + '<button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest(\'.fq-row\').remove()"><i class="bi bi-x"></i></button>'
        + '</div>'
        + '<textarea name="faq_answer[]" class="form-control form-control-sm" rows="2" placeholder="Answer"></textarea>'
        + '</div>';
    document.getElementById('faqsWrap').insertAdjacentHTML('beforeend', html);
}
</script>

@if($editing)
<form id="deleteForm" method="POST" action="{{ route('admin.products.destroy', $product->id) }}">
    @csrf @method('DELETE')
</form>
@endif

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection
