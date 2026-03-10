@extends('layouts.admin')
@section('title', 'Variation Manager – ' . $product->name)
@section('page_title', 'Variation Manager')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
*{box-sizing:border-box}
.vm-wrap{max-width:1200px;margin:0 auto}
.vm-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px}
.vm-header h2{font-size:20px;font-weight:800;color:#1e3a6e;margin:0}
.vm-header .badge{background:#1e3a6e;color:#fff;padding:4px 12px;border-radius:20px;font-size:12px}
.vm-tabs{display:flex;gap:0;margin-bottom:20px;background:#fff;border-radius:8px;overflow:hidden;border:1px solid #e0e0e0}
.vm-tab{flex:1;padding:12px 16px;text-align:center;cursor:pointer;font-size:13px;font-weight:600;color:#666;background:#fff;border:none;transition:all .2s}
.vm-tab.active{background:#1e3a6e;color:#fff}
.vm-tab:hover:not(.active){background:#f0f2f5}
.card{background:#fff;border:1px solid #e0e0e0;border-radius:8px;margin-bottom:16px}
.card-head{padding:14px 20px;border-bottom:1px solid #eee;font-weight:700;font-size:14px;color:#222;display:flex;justify-content:space-between;align-items:center}
.card-body{padding:20px}
.attr-block{border:1px solid #e5e7eb;border-radius:8px;padding:14px;margin-bottom:12px;background:#fafafa}
.attr-name{font-weight:700;font-size:14px;color:#222}
.tag{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:#eef2ff;border:1px solid #c7d2fe;border-radius:4px;font-size:12px;color:#3730a3;margin:2px}
.tag .remove{cursor:pointer;color:#dc2626;font-weight:bold;font-size:14px}
.cb-row{display:flex;gap:16px;font-size:12px;color:#555;margin:8px 0}
.cb-row input[type=checkbox]{accent-color:#1e3a6e;margin-right:4px}
.inp{padding:7px 12px;border:1.5px solid #ddd;border-radius:6px;font-size:13px;outline:none;font-family:inherit;width:100%}
.inp:focus{border-color:#1e3a6e}
.inp-sm{padding:5px 8px;font-size:12px}
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
    <div class="vm-header">
        <div>
            <h2><i class="bi bi-grid-3x3-gap me-2"></i>{{ $product->name }} — Variation Manager</h2>
            <small style="color:#888">Manage attributes, auto-generate variations, set pricing per variation</small>
        </div>
        <div style="display:flex;gap:8px">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-s"><i class="bi bi-arrow-left"></i> Back to Product</a>
            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-s" target="_blank"><i class="bi bi-eye"></i> View Frontend</a>
        </div>
    </div>

    <!-- TABS -->
    <div class="vm-tabs">
        <button class="vm-tab active" onclick="switchTab('attributes',this)">1. Attributes</button>
        <button class="vm-tab" onclick="switchTab('turnarounds',this)">2. Turnarounds</button>
        <button class="vm-tab" onclick="switchTab('quantities',this)">3. Quantities</button>
        <button class="vm-tab" onclick="switchTab('variations',this)">4. Variations & Pricing</button>
    </div>

    <div id="loadingState" class="loading"><i class="bi bi-arrow-repeat"></i> Loading data...</div>

    <!-- TAB 1: ATTRIBUTES -->
    <div id="tab-attributes" class="tab-panel" style="display:none">
        <div class="card">
            <div class="card-head">
                Attributes
                <span id="attrCount" class="badge">0</span>
            </div>
            <div class="card-body">
                <div id="attrList"></div>
                <div class="dashed" style="margin-top:12px">
                    <input class="inp" id="newAttrName" placeholder="New attribute name (e.g. Embellishment)" onkeydown="if(event.key==='Enter')addAttribute()">
                    <button class="btn btn-p" onclick="addAttribute()"><i class="bi bi-plus"></i> Add Attribute</button>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: TURNAROUNDS -->
    <div id="tab-turnarounds" class="tab-panel" style="display:none">
        <div class="card">
            <div class="card-head">
                Turnarounds (Delivery Speeds)
                <button class="btn btn-p" onclick="addTurnaround()"><i class="bi bi-plus"></i> Add</button>
            </div>
            <div class="card-body">
                <div id="turnList"></div>
            </div>
        </div>
    </div>

    <!-- TAB 3: QUANTITIES -->
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

    <!-- TAB 4: VARIATIONS -->
    <div id="tab-variations" class="tab-panel" style="display:none">
        <div class="card">
            <div class="card-head">
                Variations & Pricing
                <span id="varCount" class="badge">0</span>
            </div>
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

    <!-- SAVE BAR -->
    <div class="save-bar">
        <div id="saveMsg" class="save-msg"></div>
        <button class="btn btn-p" onclick="saveAll()" style="padding:10px 28px;font-size:14px">
            <i class="bi bi-check-circle"></i> Save All Changes
        </button>
    </div>
</div>

<script>
var PRODUCT_ID = {{ $product->id }};
var CSRF = '{{ csrf_token() }}';
var DATA = { attributes: [], turnarounds: [], quantities: [], variations: [] };
var expandedVar = null;
var nextId = 90000;
function nid(){ return 'new_' + (++nextId); }

// ===== TAB SWITCH =====
function switchTab(name, btn){
    document.querySelectorAll('.tab-panel').forEach(p => p.style.display='none');
    document.querySelectorAll('.vm-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-'+name).style.display='block';
    btn.classList.add('active');
}

// ===== LOAD DATA =====
function loadData(){
    fetch('/admin/products/'+PRODUCT_ID+'/variations',{headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}})
    .then(r=>r.json())
    .then(d=>{
        DATA = d;
        // Ensure IDs are present
        DATA.attributes.forEach(a=>{ a.values = a.values||[]; });
        DATA.turnarounds = DATA.turnarounds||[];
        DATA.quantities = DATA.quantities||[];
        DATA.variations = DATA.variations||[];
        renderAll();
        document.getElementById('loadingState').style.display='none';
        document.getElementById('tab-attributes').style.display='block';
    })
    .catch(e=>{
        document.getElementById('loadingState').innerHTML='<span style="color:red">Error loading: '+e.message+'</span>';
    });
}

function renderAll(){
    renderAttributes();
    renderTurnarounds();
    renderQuantities();
    renderVariations();
}

// ===== ATTRIBUTES =====
function renderAttributes(){
    var html = '';
    DATA.attributes.forEach((a,i)=>{
        html += '<div class="attr-block" data-idx="'+i+'">';
        html += '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">';
        html += '<span class="attr-name">'+esc(a.name)+'</span>';
        html += '<button class="btn btn-d" onclick="removeAttribute('+i+')"><i class="bi bi-x"></i> Remove</button>';
        html += '</div>';
        html += '<div class="cb-row">';
        html += '<label><input type="checkbox" '+(a.visible?'checked':'')+' onchange="DATA.attributes['+i+'].visible=this.checked"> Visible on product page</label>';
        html += '<label><input type="checkbox" '+(a.used_for_variations?'checked':'')+' onchange="DATA.attributes['+i+'].used_for_variations=this.checked"> Used for variations</label>';
        html += '</div>';
        html += '<div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:8px">';
        a.values.forEach((v,vi)=>{
            html += '<span class="tag">'+esc(v.value)+' <span class="remove" onclick="removeAttrValue('+i+','+vi+')">&times;</span></span>';
        });
        if(!a.values.length) html += '<span style="font-size:12px;color:#999">No values yet</span>';
        html += '</div>';
        html += '<div style="display:flex;gap:8px">';
        html += '<input class="inp inp-sm" id="newVal_'+i+'" placeholder="Add value..." onkeydown="if(event.key===\'Enter\')addAttrValue('+i+')">';
        html += '<button class="btn btn-s" onclick="addAttrValue('+i+')">Add</button>';
        html += '</div></div>';
    });
    document.getElementById('attrList').innerHTML = html;
    document.getElementById('attrCount').textContent = DATA.attributes.length;
}

function addAttribute(){
    var n = document.getElementById('newAttrName').value.trim();
    if(!n) return;
    DATA.attributes.push({id:nid(),name:n,visible:true,used_for_variations:true,values:[],sort_order:DATA.attributes.length});
    document.getElementById('newAttrName').value='';
    renderAttributes();
}
function removeAttribute(i){ DATA.attributes.splice(i,1); renderAttributes(); }
function addAttrValue(i){
    var el = document.getElementById('newVal_'+i);
    var v = el.value.trim();
    if(!v) return;
    DATA.attributes[i].values.push({id:nid(),value:v,sort_order:DATA.attributes[i].values.length});
    el.value='';
    renderAttributes();
}
function removeAttrValue(ai,vi){ DATA.attributes[ai].values.splice(vi,1); renderAttributes(); }

// ===== TURNAROUNDS =====
function renderTurnarounds(){
    var html = '';
    DATA.turnarounds.forEach((t,i)=>{
        html += '<div class="turn-row">';
        html += '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">';
        html += '<strong style="font-size:13px">Turnaround #'+(i+1)+'</strong>';
        html += '<button class="btn btn-d" onclick="DATA.turnarounds.splice('+i+',1);renderTurnarounds()"><i class="bi bi-x"></i></button>';
        html += '</div>';
        html += '<div style="display:grid;grid-template-columns:1fr 80px 80px 1fr;gap:8px">';
        html += '<div><label style="font-size:10px;font-weight:700;color:#888">LABEL</label><input class="inp inp-sm" value="'+esc(t.label)+'" onchange="DATA.turnarounds['+i+'].label=this.value"></div>';
        html += '<div><label style="font-size:10px;font-weight:700;color:#888">DAYS MIN</label><input class="inp inp-sm" type="number" value="'+(t.working_days_min||1)+'" onchange="DATA.turnarounds['+i+'].working_days_min=+this.value"></div>';
        html += '<div><label style="font-size:10px;font-weight:700;color:#888">DAYS MAX</label><input class="inp inp-sm" type="number" value="'+(t.working_days_max||1)+'" onchange="DATA.turnarounds['+i+'].working_days_max=+this.value"></div>';
        html += '<div><label style="font-size:10px;font-weight:700;color:#888">DEADLINE</label><input class="inp inp-sm" value="'+esc(t.artwork_deadline||'')+'" onchange="DATA.turnarounds['+i+'].artwork_deadline=this.value"></div>';
        html += '</div></div>';
    });
    if(!DATA.turnarounds.length) html = '<div class="empty-state">No turnarounds. Add Express, 1 Day, 3-4 Days etc.</div>';
    document.getElementById('turnList').innerHTML = html;
}
function addTurnaround(){
    DATA.turnarounds.push({id:nid(),label:'',working_days_min:1,working_days_max:1,artwork_deadline:'6:30pm',sort_order:DATA.turnarounds.length});
    renderTurnarounds();
}

// ===== QUANTITIES =====
function renderQuantities(){
    var html = '';
    DATA.quantities.forEach((q,i)=>{
        html += '<span class="qty-tag">'+q.quantity+' <span class="remove" onclick="DATA.quantities.splice('+i+',1);renderQuantities()">&times;</span></span>';
    });
    if(!DATA.quantities.length) html = '<span style="font-size:12px;color:#999">No quantities added</span>';
    document.getElementById('qtyTags').innerHTML = html;
}
function addQuantity(){
    var el = document.getElementById('newQtyInput');
    var q = parseInt(el.value);
    if(!q || DATA.quantities.find(x=>x.quantity===q)) return;
    DATA.quantities.push({id:nid(),quantity:q,sort_order:DATA.quantities.length});
    DATA.quantities.sort((a,b)=>a.quantity-b.quantity);
    el.value='';
    renderQuantities();
}

// ===== VARIATIONS =====
function getVarAttrs(){
    return DATA.attributes.filter(a=>a.used_for_variations && a.values.length>0);
}

function cartesian(arrays){
    if(!arrays.length) return [];
    return arrays.reduce((acc,arr)=>acc.flatMap(combo=>arr.map(val=>[...combo,val])),[[]])
}

function autoGenerate(){
    var va = getVarAttrs();
    if(!va.length){ alert('Pehle attributes add karein jinke "Used for variations" ON ho'); return; }
    var combos = cartesian(va.map(a=>a.values));
    var added = 0;
    combos.forEach(combo=>{
        var sels = combo.map((v,i)=>({attribute_id:va[i].id, attribute_value_id:v.id}));
        // Check if exists
        var exists = DATA.variations.find(vr=>{
            return va.every((a,i)=>{
                var s = (vr.selections||[]).find(s=>String(s.attribute_id)===String(a.id));
                return s && String(s.attribute_value_id)===String(combo[i].id);
            });
        });
        if(!exists){
            DATA.variations.push({id:nid(),sku:'',enabled:true,selections:sels,pricing:[],disabled_quantities:[],sort_order:DATA.variations.length});
            added++;
        }
    });
    alert(added+' new variations added! (Duplicates skipped)');
    renderVariations();
}

function addSingleVariation(){
    var va = getVarAttrs();
    if(!va.length){ alert('Pehle attributes add karein'); return; }
    var sels = va.map(a=>({attribute_id:a.id, attribute_value_id:a.values[0].id}));
    DATA.variations.push({id:nid(),sku:'',enabled:true,selections:sels,pricing:[],disabled_quantities:[],sort_order:DATA.variations.length});
    renderVariations();
}

function getValLabel(attrId, valId){
    var a = DATA.attributes.find(x=>String(x.id)===String(attrId));
    if(!a) return '?';
    var v = a.values.find(x=>String(x.id)===String(valId));
    return v ? v.value : '?';
}

function renderVariations(){
    var va = getVarAttrs();
    var total = va.length ? va.reduce((a,b)=>a*b.values.length,1) : 0;
    document.getElementById('varInfo').textContent = 'Possible: '+total+' | Active: '+DATA.variations.length;
    document.getElementById('varCount').textContent = DATA.variations.length;

    if(!DATA.variations.length){
        document.getElementById('varList').innerHTML = '<div class="empty-state">No variations yet. Auto Generate ya Add Single se shuru karein.</div>';
        return;
    }

    var html = '';
    DATA.variations.forEach((v,idx)=>{
        var isExp = expandedVar == v.id;
        html += '<div class="var-row '+(isExp?'expanded':'')+'">';
        html += '<div class="var-header">';
        html += '<div class="var-selects">';
        html += '<span style="font-size:11px;color:#999;font-weight:700;margin-right:4px">#'+(idx+1)+'</span>';
        va.forEach(a=>{
            var sel = (v.selections||[]).find(s=>String(s.attribute_id)===String(a.id));
            var selVal = sel ? sel.attribute_value_id : (a.values[0]?a.values[0].id:'');
            html += '<div><label>'+esc(a.name)+'</label><select onchange="updateVarSel('+idx+',\''+a.id+'\',this.value)">';
            a.values.forEach(av=>{
                html += '<option value="'+av.id+'" '+(String(selVal)===String(av.id)?'selected':'')+'>'+esc(av.value)+'</option>';
            });
            html += '</select></div>';
        });
        html += '</div>';
        html += '<div class="var-actions">';
        html += '<button class="btn btn-s" style="padding:4px 10px;font-size:10px" onclick="duplicateVar('+idx+')"><i class="bi bi-copy"></i> Copy</button>';
        html += '<button class="btn btn-d" style="padding:4px 10px;font-size:10px" onclick="DATA.variations.splice('+idx+',1);expandedVar=null;renderVariations()"><i class="bi bi-x"></i></button>';
        html += '<button class="btn '+(isExp?'btn-p':'btn-s')+'" style="padding:4px 14px" onclick="toggleExpand(\''+v.id+'\')">'+(isExp?'▲ Close':'▼ Prices')+'</button>';
        html += '</div></div>';

        if(isExp){
            html += renderPriceTable(v, idx);
        }
        html += '</div>';
    });
    document.getElementById('varList').innerHTML = html;
}

function updateVarSel(varIdx, attrId, newValId){
    var v = DATA.variations[varIdx];
    var sel = (v.selections||[]).find(s=>String(s.attribute_id)===String(attrId));
    if(sel){ sel.attribute_value_id = newValId; }
    else{ v.selections = v.selections||[]; v.selections.push({attribute_id:attrId, attribute_value_id:newValId}); }
}

function duplicateVar(idx){
    var copy = JSON.parse(JSON.stringify(DATA.variations[idx]));
    copy.id = nid();
    DATA.variations.splice(idx+1,0,copy);
    renderVariations();
}

function toggleExpand(id){
    expandedVar = expandedVar==id ? null : id;
    renderVariations();
}

function renderPriceTable(v, varIdx){
    var turns = DATA.turnarounds;
    var qtys = DATA.quantities;
    if(!turns.length || !qtys.length){
        return '<div style="padding:14px;border-top:1px solid #eee;font-size:12px;color:#999">Pehle Turnarounds aur Quantities add karein (Tab 2 & 3)</div>';
    }
    var html = '<div style="padding:14px;border-top:1px solid #eee;background:#fafafa">';
    html += '<div style="font-size:11px;color:#888;margin-bottom:8px"><span style="display:inline-block;width:10px;height:10px;background:#fecaca;border:1px solid #fca5a5;border-radius:2px;margin-right:4px"></span> Red = Disabled</div>';
    html += '<div style="overflow-x:auto"><table class="price-table"><thead><tr>';
    html += '<th>✓</th><th style="text-align:left">Qty</th>';
    turns.forEach(t=>{ html += '<th>'+esc(t.label)+'</th>'; });
    html += '</tr></thead><tbody>';

    qtys.forEach((q,qi)=>{
        var qtyDisabled = (v.disabled_quantities||[]).includes(q.quantity);
        html += '<tr class="'+(qtyDisabled?'disabled-row':'')+'">';
        html += '<td><input type="checkbox" '+(qtyDisabled?'':'checked')+' onchange="toggleQtyDisabled('+varIdx+','+q.quantity+',this.checked)" style="accent-color:#059669;width:15px;height:15px;cursor:pointer"></td>';
        html += '<td style="text-align:left;font-weight:700;'+(qtyDisabled?'text-decoration:line-through;color:#dc2626':'')+'">'+q.quantity+'</td>';
        turns.forEach(t=>{
            var p = (v.pricing||[]).find(x=>String(x.turnaround_id)===String(t.id) && x.quantity===q.quantity);
            var cellDisabled = qtyDisabled || (p && p.disabled);
            var price = p ? p.price : '';

            html += '<td>';
            if(!qtyDisabled){
                html += '<div style="display:flex;align-items:center;justify-content:center;gap:3px">';
                html += '<input type="checkbox" '+(cellDisabled && !qtyDisabled?'':'checked')+' onchange="toggleCellDisabled('+varIdx+','+q.quantity+',\''+t.id+'\',this.checked)" style="accent-color:#059669;width:12px;height:12px;cursor:pointer">';
                if(cellDisabled && !qtyDisabled){
                    html += '<span class="cell-disabled">N/A</span>';
                } else {
                    html += '<input class="price-inp" type="number" step="0.01" value="'+(price||'')+'" placeholder="0.00" '+(qtyDisabled?'disabled':'')+' onchange="setVarPrice('+varIdx+','+q.quantity+',\''+t.id+'\',this.value)">';
                }
                html += '</div>';
            } else {
                html += '<span class="cell-disabled">—</span>';
            }
            html += '</td>';
        });
        html += '</tr>';
    });
    html += '</tbody></table></div></div>';
    return html;
}

function toggleQtyDisabled(varIdx, qty, checked){
    var v = DATA.variations[varIdx];
    v.disabled_quantities = v.disabled_quantities||[];
    if(checked){
        v.disabled_quantities = v.disabled_quantities.filter(q=>q!==qty);
    } else {
        if(!v.disabled_quantities.includes(qty)) v.disabled_quantities.push(qty);
    }
    renderVariations();
}

function toggleCellDisabled(varIdx, qty, turnId, checked){
    var v = DATA.variations[varIdx];
    v.pricing = v.pricing||[];
    var p = v.pricing.find(x=>String(x.turnaround_id)===String(turnId) && x.quantity===qty);
    if(p){
        p.disabled = !checked;
    } else {
        v.pricing.push({turnaround_id:turnId, quantity:qty, price:0, disabled:!checked});
    }
    renderVariations();
}

function setVarPrice(varIdx, qty, turnId, val){
    var v = DATA.variations[varIdx];
    v.pricing = v.pricing||[];
    var p = v.pricing.find(x=>String(x.turnaround_id)===String(turnId) && x.quantity===qty);
    if(p){
        p.price = parseFloat(val)||0;
    } else {
        v.pricing.push({turnaround_id:turnId, quantity:qty, price:parseFloat(val)||0, disabled:false});
    }
}

// ===== SAVE =====
function saveAll(){
    var msg = document.getElementById('saveMsg');
    msg.textContent = 'Saving...';
    msg.className = 'save-msg';

    fetch('/admin/products/'+PRODUCT_ID+'/variations', {
        method:'POST',
        headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},
        body:JSON.stringify(DATA)
    })
    .then(r=>r.json())
    .then(d=>{
        if(d.success){
            msg.textContent = '✓ Saved successfully!';
            msg.className = 'save-msg ok';
            // Reload to get fresh IDs
            loadData();
        } else {
            msg.textContent = '✗ Error: '+(d.error||'Unknown');
            msg.className = 'save-msg err';
        }
        setTimeout(()=>{ msg.textContent=''; }, 4000);
    })
    .catch(e=>{
        msg.textContent = '✗ Network error: '+e.message;
        msg.className = 'save-msg err';
    });
}

function esc(s){ var d=document.createElement('div'); d.textContent=s||''; return d.innerHTML; }

document.addEventListener('DOMContentLoaded', loadData);
</script>
@endsection
