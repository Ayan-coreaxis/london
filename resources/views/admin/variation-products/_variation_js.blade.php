<script>
var PRODUCT_ID = {{ $product->id }};
var CSRF = '{{ csrf_token() }}';
var DATA = { attributes: [], turnarounds: [], quantities: [], variations: [] };
var expandedVar = null;
var nextId = 90000;
function nid(){ return 'new_' + (++nextId); }

function switchTab(name, btn){
    document.querySelectorAll('.tab-panel').forEach(p => p.style.display='none');
    document.querySelectorAll('.vm-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-'+name).style.display='block';
    btn.classList.add('active');
}

function loadData(){
    fetch('/admin/variation-products/'+PRODUCT_ID+'/data',{headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}})
    .then(r=>r.json())
    .then(d=>{
        DATA = d;
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

function renderAll(){ renderAttributes(); renderTurnarounds(); renderQuantities(); renderVariations(); }

// ===== ATTRIBUTES =====
function renderAttributes(){
    var html = '';
    DATA.attributes.forEach((a,i)=>{
        html += '<div style="border:1px solid #e5e7eb;border-radius:8px;padding:14px;margin-bottom:12px;background:#fafafa">';
        html += '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">';
        html += '<span style="font-weight:700;font-size:14px;color:#222">'+esc(a.name)+'</span>';
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
    var v = el.value.trim(); if(!v) return;
    DATA.attributes[i].values.push({id:nid(),value:v,sort_order:DATA.attributes[i].values.length});
    el.value=''; renderAttributes();
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
    el.value=''; renderQuantities();
}

// ===== VARIATIONS =====
function getVarAttrs(){ return DATA.attributes.filter(a=>a.used_for_variations && a.values.length>0); }

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
        var exists = DATA.variations.find(vr=>{
            return va.every((a,i)=>{
                var s = (vr.selections||[]).find(s=>String(s.attribute_id)===String(a.id));
                return s && String(s.attribute_value_id)===String(combo[i].id);
            });
        });
        if(!exists){ DATA.variations.push({id:nid(),sku:'',enabled:true,selections:sels,pricing:[],disabled_quantities:[],sort_order:DATA.variations.length}); added++; }
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
        html += '<div class="var-header"><div class="var-selects">';
        html += '<span style="font-size:11px;color:#999;font-weight:700;margin-right:4px">#'+(idx+1)+'</span>';
        va.forEach(a=>{
            var sel = (v.selections||[]).find(s=>String(s.attribute_id)===String(a.id));
            var selVal = sel ? sel.attribute_value_id : (a.values[0]?a.values[0].id:'');
            html += '<div><label>'+esc(a.name)+'</label><select onchange="updateVarSel('+idx+',\''+a.id+'\',this.value)">';
            a.values.forEach(av=>{ html += '<option value="'+av.id+'" '+(String(selVal)===String(av.id)?'selected':'')+'>'+esc(av.value)+'</option>'; });
            html += '</select></div>';
        });
        html += '</div><div class="var-actions">';
        html += '<button class="btn btn-s" style="padding:4px 10px;font-size:10px" onclick="duplicateVar('+idx+')"><i class="bi bi-copy"></i> Copy</button>';
        html += '<button class="btn btn-d" style="padding:4px 10px;font-size:10px" onclick="DATA.variations.splice('+idx+',1);expandedVar=null;renderVariations()"><i class="bi bi-x"></i></button>';
        html += '<button class="btn '+(isExp?'btn-p':'btn-s')+'" style="padding:4px 14px" onclick="toggleExpand(\''+v.id+'\')">'+(isExp?'▲ Close':'▼ Prices')+'</button>';
        html += '</div></div>';
        if(isExp) html += renderPriceTable(v, idx);
        html += '</div>';
    });
    document.getElementById('varList').innerHTML = html;
}

function updateVarSel(varIdx, attrId, newValId){
    var v = DATA.variations[varIdx];
    var sel = (v.selections||[]).find(s=>String(s.attribute_id)===String(attrId));
    if(sel) sel.attribute_value_id = newValId;
    else { v.selections = v.selections||[]; v.selections.push({attribute_id:attrId, attribute_value_id:newValId}); }
}

function duplicateVar(idx){
    var copy = JSON.parse(JSON.stringify(DATA.variations[idx]));
    copy.id = nid();
    DATA.variations.splice(idx+1,0,copy);
    renderVariations();
}

function toggleExpand(id){ expandedVar = expandedVar==id ? null : id; renderVariations(); }

function renderPriceTable(v, varIdx){
    var turns = DATA.turnarounds, qtys = DATA.quantities;
    if(!turns.length || !qtys.length) return '<div style="padding:14px;border-top:1px solid #eee;font-size:12px;color:#999">Pehle Turnarounds aur Quantities add karein (Tab 2 & 3)</div>';
    var html = '<div style="padding:14px;border-top:1px solid #eee;background:#fafafa">';
    html += '<div style="font-size:11px;color:#888;margin-bottom:8px"><span style="display:inline-block;width:10px;height:10px;background:#fecaca;border:1px solid #fca5a5;border-radius:2px;margin-right:4px"></span> Red = Disabled</div>';
    html += '<div style="overflow-x:auto"><table class="price-table"><thead><tr><th>✓</th><th style="text-align:left">Qty</th>';
    turns.forEach(t=>{ html += '<th>'+esc(t.label)+'</th>'; });
    html += '</tr></thead><tbody>';
    qtys.forEach((q,qi)=>{
        var qd = (v.disabled_quantities||[]).includes(q.quantity);
        html += '<tr class="'+(qd?'disabled-row':'')+'">';
        html += '<td><input type="checkbox" '+(qd?'':'checked')+' onchange="toggleQtyDisabled('+varIdx+','+q.quantity+',this.checked)" style="accent-color:#059669;width:15px;height:15px;cursor:pointer"></td>';
        html += '<td style="text-align:left;font-weight:700;'+(qd?'text-decoration:line-through;color:#dc2626':'')+'">'+q.quantity+'</td>';
        turns.forEach(t=>{
            var p = (v.pricing||[]).find(x=>String(x.turnaround_id)===String(t.id) && x.quantity===q.quantity);
            var cd = qd || (p && p.disabled);
            var price = p ? p.price : '';
            html += '<td>';
            if(!qd){
                html += '<div style="display:flex;align-items:center;justify-content:center;gap:3px">';
                html += '<input type="checkbox" '+(cd && !qd?'':'checked')+' onchange="toggleCellDisabled('+varIdx+','+q.quantity+',\''+t.id+'\',this.checked)" style="accent-color:#059669;width:12px;height:12px;cursor:pointer">';
                if(cd && !qd) html += '<span class="cell-disabled">N/A</span>';
                else html += '<input class="price-inp" type="number" step="0.01" value="'+(price||'')+'" placeholder="0.00" onchange="setVarPrice('+varIdx+','+q.quantity+',\''+t.id+'\',this.value)">';
                html += '</div>';
            } else html += '<span class="cell-disabled">—</span>';
            html += '</td>';
        });
        html += '</tr>';
    });
    html += '</tbody></table></div></div>';
    return html;
}

function toggleQtyDisabled(vi, qty, checked){
    var v = DATA.variations[vi]; v.disabled_quantities = v.disabled_quantities||[];
    if(checked) v.disabled_quantities = v.disabled_quantities.filter(q=>q!==qty);
    else { if(!v.disabled_quantities.includes(qty)) v.disabled_quantities.push(qty); }
    renderVariations();
}

function toggleCellDisabled(vi, qty, tid, checked){
    var v = DATA.variations[vi]; v.pricing = v.pricing||[];
    var p = v.pricing.find(x=>String(x.turnaround_id)===String(tid) && x.quantity===qty);
    if(p) p.disabled = !checked;
    else v.pricing.push({turnaround_id:tid, quantity:qty, price:0, disabled:!checked});
    renderVariations();
}

function setVarPrice(vi, qty, tid, val){
    var v = DATA.variations[vi]; v.pricing = v.pricing||[];
    var p = v.pricing.find(x=>String(x.turnaround_id)===String(tid) && x.quantity===qty);
    if(p) p.price = parseFloat(val)||0;
    else v.pricing.push({turnaround_id:tid, quantity:qty, price:parseFloat(val)||0, disabled:false});
}

// ===== SAVE =====
function saveAll(){
    var msg = document.getElementById('saveMsg');
    msg.textContent = 'Saving...'; msg.className = 'save-msg';
    fetch('/admin/variation-products/'+PRODUCT_ID+'/data', {
        method:'POST',
        headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},
        body:JSON.stringify(DATA)
    })
    .then(r=>r.json())
    .then(d=>{
        if(d.success){ msg.textContent='✓ Saved successfully!'; msg.className='save-msg ok'; loadData(); }
        else { msg.textContent='✗ Error: '+(d.error||'Unknown'); msg.className='save-msg err'; }
        setTimeout(()=>{ msg.textContent=''; }, 4000);
    })
    .catch(e=>{ msg.textContent='✗ Network error: '+e.message; msg.className='save-msg err'; });
}

function esc(s){ var d=document.createElement('div'); d.textContent=s||''; return d.innerHTML; }

document.addEventListener('DOMContentLoaded', loadData);
</script>
