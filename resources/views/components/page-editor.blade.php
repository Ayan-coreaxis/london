@auth('admin')
<div id="pe-root">

{{-- ── FLOATING TOGGLE BUTTON ── --}}
<button id="pe-toggle" title="Edit Page" onclick="peToggleMode()">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
    </svg>
    <span id="pe-toggle-label">Edit Page</span>
</button>

{{-- ── TOOLBAR ── --}}
<div id="pe-toolbar" style="display:none">
    <div class="pe-tb-left">
        <span id="pe-sel-label">Click any text to edit</span>
    </div>
    <div class="pe-tb-controls">

        {{-- Font Family --}}
        <select id="pe-font" class="pe-select" title="Font" onchange="peApply('fontFamily',this.value)">
            <option value="">— Font —</option>
            <optgroup label="⭐ Local Fonts">
                <option value="'ABCGravity',sans-serif">ABC Gravity</option>
                <option value="'ABCGravity-Condensed',sans-serif">ABC Gravity Condensed</option>
                <option value="'ABCGravity-Compressed',sans-serif">ABC Gravity Compressed</option>
                <option value="'ABCGravity-Expanded',sans-serif">ABC Gravity Expanded</option>
                <option value="'ABCGravity-Wide',sans-serif">ABC Gravity Wide</option>
                <option value="'BrittiSans',sans-serif">Britti Sans</option>
                <option value="'BrittiSans-Bold',sans-serif">Britti Sans Bold</option>
                <option value="'BrittiSans-Light',sans-serif">Britti Sans Light</option>
            </optgroup>
            <optgroup label="System Fonts">
                <option value="'Montserrat',sans-serif">Montserrat</option>
                <option value="'Open Sans',sans-serif">Open Sans</option>
                <option value="'Inter',sans-serif">Inter</option>
                <option value="'Georgia',serif">Georgia</option>
                <option value="'Arial',sans-serif">Arial</option>
            </optgroup>
        </select>

        {{-- Font Size --}}
        <select id="pe-size" class="pe-select" title="Font Size" onchange="peApply('fontSize',this.value)">
            <option value="">Size</option>
            @foreach([10,11,12,13,14,15,16,18,20,22,24,26,28,32,36,40,48,56,64,72,80,96] as $sz)
            <option value="{{ $sz }}px">{{ $sz }}</option>
            @endforeach
        </select>

        {{-- Heading --}}
        <select id="pe-heading" class="pe-select" title="Heading" onchange="peChangeTag(this.value)">
            <option value="">Tag</option>
            <option value="p">P</option>
            <option value="h1">H1</option>
            <option value="h2">H2</option>
            <option value="h3">H3</option>
            <option value="h4">H4</option>
            <option value="h5">H5</option>
            <option value="span">Span</option>
        </select>

        {{-- Separator --}}
        <div class="pe-sep"></div>

        {{-- Bold --}}
        <button class="pe-btn" id="pe-bold" title="Bold" onclick="peToggleStyle('bold')"><b>B</b></button>
        {{-- Italic --}}
        <button class="pe-btn" id="pe-italic" title="Italic" onclick="peToggleStyle('italic')"><i>I</i></button>
        {{-- Underline --}}
        <button class="pe-btn" id="pe-underline" title="Underline" onclick="peToggleStyle('underline')"><u>U</u></button>
        {{-- Strikethrough --}}
        <button class="pe-btn" id="pe-strike" title="Strikethrough" onclick="peToggleStyle('strikethrough')"><s>S</s></button>

        {{-- Separator --}}
        <div class="pe-sep"></div>

        {{-- Text Align --}}
        <button class="pe-btn" title="Align Left"   onclick="peApply('textAlign','left')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="15" y2="12"/><line x1="3" y1="18" x2="18" y2="18"/></svg>
        </button>
        <button class="pe-btn" title="Center"       onclick="peApply('textAlign','center')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="6" y1="12" x2="18" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
        </button>
        <button class="pe-btn" title="Align Right"  onclick="peApply('textAlign','right')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="9" y1="12" x2="21" y2="12"/><line x1="6" y1="18" x2="21" y2="18"/></svg>
        </button>

        {{-- Separator --}}
        <div class="pe-sep"></div>

        {{-- Text Color --}}
        <label class="pe-btn pe-color-wrap" title="Text Color">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg>
            <input type="color" id="pe-color" value="#000000" oninput="peApply('color',this.value)" title="Text Color">
        </label>

        {{-- BG Color --}}
        <label class="pe-btn pe-color-wrap" title="Background Color">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
            <input type="color" id="pe-bg" value="#ffffff" oninput="peApply('backgroundColor',this.value)" title="Background Color">
        </label>

        {{-- Line Height --}}
        <select id="pe-lh" class="pe-select" title="Line Height" onchange="peApply('lineHeight',this.value)">
            <option value="">Line H</option>
            <option value="1">1.0</option>
            <option value="1.2">1.2</option>
            <option value="1.4">1.4</option>
            <option value="1.5">1.5</option>
            <option value="1.6">1.6</option>
            <option value="1.8">1.8</option>
            <option value="2">2.0</option>
        </select>

        {{-- Letter Spacing --}}
        <select id="pe-ls" class="pe-select" title="Letter Spacing" onchange="peApply('letterSpacing',this.value)">
            <option value="">Spacing</option>
            <option value="-1px">-1</option>
            <option value="0px">0</option>
            <option value="0.5px">0.5</option>
            <option value="1px">1</option>
            <option value="2px">2</option>
            <option value="3px">3</option>
            <option value="4px">4</option>
        </select>

        {{-- Separator --}}
        <div class="pe-sep"></div>

        {{-- Save --}}
        <button class="pe-btn pe-save" onclick="peSave()" title="Save changes to this page">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Save
        </button>

        {{-- Reset --}}
        <button class="pe-btn pe-reset" onclick="peReset()" title="Reset all edits on this page">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
        </button>

        {{-- Close --}}
        <button class="pe-btn pe-close" onclick="peToggleMode()" title="Exit editor">✕</button>
    </div>
</div>

{{-- ── STATUS TOAST ── --}}
<div id="pe-toast"></div>

</div>{{-- #pe-root --}}

<style>
#pe-root { position:fixed; z-index:99999; }

/* Toggle button */
#pe-toggle {
    position:fixed; bottom:24px; right:24px;
    background:#1e3a6e; color:#fff;
    border:none; border-radius:50px;
    padding:10px 18px; font-size:13px; font-weight:700;
    cursor:pointer; display:flex; align-items:center; gap:8px;
    box-shadow:0 4px 20px rgba(30,58,110,.4);
    transition:all .2s; font-family:'Inter',sans-serif;
    z-index:99999;
}
#pe-toggle:hover { background:#e8352a; transform:translateY(-2px); }
#pe-toggle.active { background:#e8352a; }

/* Toolbar */
#pe-toolbar {
    position:fixed; top:0; left:0; right:0;
    background:#1a1a2e; color:#fff;
    display:flex; align-items:center; gap:0;
    padding:0 12px; height:52px;
    box-shadow:0 2px 16px rgba(0,0,0,.4);
    z-index:99998; font-family:'Inter',sans-serif;
    overflow-x:auto;
}
.pe-tb-left { flex-shrink:0; margin-right:12px; }
#pe-sel-label {
    font-size:11px; color:#aaa; max-width:180px;
    overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
    background:rgba(255,255,255,.07); padding:4px 10px; border-radius:4px;
}
.pe-tb-controls { display:flex; align-items:center; gap:4px; flex:1; flex-wrap:nowrap; overflow-x:auto; }
.pe-select {
    height:32px; border:1px solid rgba(255,255,255,.15);
    border-radius:5px; background:#2d2d44; color:#fff;
    font-size:12px; padding:0 6px; cursor:pointer; outline:none;
}
.pe-select:hover { border-color:rgba(255,255,255,.35); }
.pe-btn {
    height:32px; min-width:32px; padding:0 8px;
    border:1px solid rgba(255,255,255,.15);
    border-radius:5px; background:#2d2d44; color:#fff;
    font-size:12px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px;
    transition:all .15s; white-space:nowrap;
}
.pe-btn:hover { background:rgba(255,255,255,.15); border-color:rgba(255,255,255,.4); }
.pe-btn.active { background:#4a6fa5; border-color:#6b9bd2; }
.pe-sep { width:1px; height:24px; background:rgba(255,255,255,.15); margin:0 4px; flex-shrink:0; }
.pe-color-wrap { position:relative; cursor:pointer; }
.pe-color-wrap input[type=color] { position:absolute; opacity:0; width:0; height:0; }
.pe-save { background:#22a85a !important; border-color:#22a85a !important; font-weight:700; padding:0 14px; }
.pe-save:hover { background:#1a8f4a !important; }
.pe-reset { background:#c0392b !important; border-color:#c0392b !important; }
.pe-close { background:#555 !important; font-weight:700; }

/* Editable element highlight */
.pe-editable {
    outline:2px dashed transparent !important;
    transition:outline .15s;
    cursor:pointer !important;
}
.pe-editable:hover {
    outline:2px dashed rgba(74,111,165,.7) !important;
    outline-offset:2px;
}
.pe-editable.pe-selected {
    outline:2px solid #4a6fa5 !important;
    outline-offset:2px;
}

/* Toast */
#pe-toast {
    position:fixed; bottom:80px; right:24px;
    background:#22a85a; color:#fff;
    padding:10px 20px; border-radius:8px;
    font-size:13px; font-weight:700; font-family:'Inter',sans-serif;
    opacity:0; transform:translateY(10px);
    transition:all .3s; pointer-events:none;
    box-shadow:0 4px 16px rgba(0,0,0,.2);
}
#pe-toast.show { opacity:1; transform:translateY(0); }
#pe-toast.error { background:#e8352a; }

/* Body padding when toolbar visible */
body.pe-active { padding-top:52px !important; }
</style>

<script>
(function(){
    const EDITABLES = 'h1,h2,h3,h4,h5,h6,p,span,a,button,label,li,div[class],section > .btn-shop-now,.promo-main-text,.hero-left h1,.hero-left p,.featured-section h2,.trending-section h2,.product-info h3,.blog-body h3,.delivery-title h2,.trust-label,.dc-title,.dc-link,.footer-col h4,.footer-col address,.footer-copy';

    let editMode   = false;
    let selected   = null;      // currently selected DOM element
    let editMap    = {};        // { selector: {styles:{}, content:''} }
    let selectorCount = {};     // for unique selector generation

    // ─── Load saved edits ───────────────────────────────────────────
    async function loadEdits() {
        try {
            const r = await fetch('/admin/page-editor/edits?path='+encodeURIComponent(location.pathname));
            const data = await r.json();
            data.forEach(edit => {
                const styles = JSON.parse(edit.styles || '{}');
                editMap[edit.selector] = { styles, content: edit.content };

                // Apply saved styles to elements
                document.querySelectorAll(edit.selector).forEach(el => {
                    applyStylesToEl(el, styles);
                    if (edit.content && edit.content !== el.innerHTML) {
                        el.innerHTML = edit.content;
                    }
                });
            });
        } catch(e) {}
    }

    function applyStylesToEl(el, styles) {
        Object.entries(styles).forEach(([prop, val]) => {
            el.style[prop] = val;
        });
    }

    // ─── Toggle edit mode ────────────────────────────────────────────
    window.peToggleMode = function() {
        editMode = !editMode;
        const toolbar  = document.getElementById('pe-toolbar');
        const toggleBtn = document.getElementById('pe-toggle');
        const label    = document.getElementById('pe-toggle-label');

        if (editMode) {
            toolbar.style.display = 'flex';
            document.body.classList.add('pe-active');
            toggleBtn.classList.add('active');
            label.textContent = 'Exit Editor';
            makeEditable();
        } else {
            toolbar.style.display = 'none';
            document.body.classList.remove('pe-active');
            toggleBtn.classList.remove('active');
            label.textContent = 'Edit Page';
            removeEditable();
            selected = null;
        }
    };

    // ─── Make elements clickable ─────────────────────────────────────
    function makeEditable() {
        document.querySelectorAll(EDITABLES).forEach((el, i) => {
            if (el.closest('#pe-root')) return;
            el.classList.add('pe-editable');
            el.addEventListener('click', onElClick, true);
        });
    }

    function removeEditable() {
        document.querySelectorAll('.pe-editable').forEach(el => {
            el.classList.remove('pe-editable','pe-selected');
            el.removeEventListener('click', onElClick, true);
        });
    }

    // ─── Element click ────────────────────────────────────────────────
    function onElClick(e) {
        if (!editMode) return;
        e.preventDefault(); e.stopPropagation();

        document.querySelectorAll('.pe-selected').forEach(el => el.classList.remove('pe-selected'));
        selected = e.currentTarget;
        selected.classList.add('pe-selected');

        // Show selector in toolbar
        const sel = getSelector(selected);
        document.getElementById('pe-sel-label').textContent = sel;

        // Sync toolbar controls to element's current styles
        const cs = window.getComputedStyle(selected);
        syncToolbar(cs, selected);
    }

    // ─── Sync toolbar to selected element ────────────────────────────
    function syncToolbar(cs, el) {
        // Font
        const fontSel = document.getElementById('pe-font');
        const inlineFf = el.style.fontFamily;
        fontSel.value = inlineFf || '';

        // Size
        const sizeSel = document.getElementById('pe-size');
        const inlineFs = el.style.fontSize;
        sizeSel.value = inlineFs || '';

        // Bold
        const fw = el.style.fontWeight || cs.fontWeight;
        document.getElementById('pe-bold').classList.toggle('active', parseInt(fw) >= 700 || fw === 'bold');

        // Italic
        const fi = el.style.fontStyle || cs.fontStyle;
        document.getElementById('pe-italic').classList.toggle('active', fi === 'italic');

        // Underline
        const td = el.style.textDecoration || cs.textDecoration;
        document.getElementById('pe-underline').classList.toggle('active', td.includes('underline'));
        document.getElementById('pe-strike').classList.toggle('active', td.includes('line-through'));

        // Color
        try {
            const col = el.style.color || cs.color;
            if (col) document.getElementById('pe-color').value = rgbToHex(col);
        } catch(e) {}

        // Line height
        const lh = el.style.lineHeight || '';
        document.getElementById('pe-lh').value = lh || '';

        // Letter spacing
        const ls = el.style.letterSpacing || '';
        document.getElementById('pe-ls').value = ls || '';
    }

    // ─── Apply style property ─────────────────────────────────────────
    window.peApply = function(prop, val) {
        if (!selected) return peToast('Click an element first!', true);
        selected.style[prop] = val;
        recordEdit(selected, prop, val);
    };

    // ─── Toggle bold/italic/underline ────────────────────────────────
    window.peToggleStyle = function(type) {
        if (!selected) return peToast('Click an element first!', true);
        const cs = window.getComputedStyle(selected);

        if (type === 'bold') {
            const isBold = parseInt(selected.style.fontWeight || cs.fontWeight) >= 700;
            selected.style.fontWeight = isBold ? '400' : '700';
            recordEdit(selected, 'fontWeight', selected.style.fontWeight);
            document.getElementById('pe-bold').classList.toggle('active', !isBold);
        } else if (type === 'italic') {
            const isItalic = (selected.style.fontStyle || cs.fontStyle) === 'italic';
            selected.style.fontStyle = isItalic ? 'normal' : 'italic';
            recordEdit(selected, 'fontStyle', selected.style.fontStyle);
            document.getElementById('pe-italic').classList.toggle('active', !isItalic);
        } else if (type === 'underline') {
            const isUnder = (selected.style.textDecoration || cs.textDecoration).includes('underline');
            selected.style.textDecoration = isUnder ? 'none' : 'underline';
            recordEdit(selected, 'textDecoration', selected.style.textDecoration);
            document.getElementById('pe-underline').classList.toggle('active', !isUnder);
        } else if (type === 'strikethrough') {
            const isStrike = (selected.style.textDecoration || cs.textDecoration).includes('line-through');
            selected.style.textDecoration = isStrike ? 'none' : 'line-through';
            recordEdit(selected, 'textDecoration', selected.style.textDecoration);
            document.getElementById('pe-strike').classList.toggle('active', !isStrike);
        }
    };

    // ─── Change HTML tag (h1→h2 etc) ─────────────────────────────────
    window.peChangeTag = function(tag) {
        if (!selected || !tag) return;
        const newEl = document.createElement(tag);
        newEl.innerHTML  = selected.innerHTML;
        newEl.className  = selected.className;
        newEl.style.cssText = selected.style.cssText;
        selected.parentNode.replaceChild(newEl, selected);
        newEl.classList.add('pe-editable','pe-selected');
        newEl.addEventListener('click', onElClick, true);
        selected = newEl;
        recordEdit(selected, '_tag', tag);
    };

    // ─── Record edit in map ───────────────────────────────────────────
    function recordEdit(el, prop, val) {
        const sel = getSelector(el);
        if (!editMap[sel]) editMap[sel] = { styles: {}, content: null };
        if (prop === '_tag') {
            editMap[sel].tag = val;
        } else {
            editMap[sel].styles[prop] = val;
        }
        editMap[sel].content = el.innerHTML;
    }

    // ─── Save to DB ───────────────────────────────────────────────────
    window.peSave = async function() {
        const edits = Object.entries(editMap).map(([selector, data]) => ({
            selector,
            styles:  data.styles  || {},
            content: data.content || null,
        }));

        try {
            const r = await fetch('/admin/page-editor/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}',
                },
                body: JSON.stringify({ path: location.pathname, edits }),
            });
            const d = await r.json();
            if (d.ok) peToast('✓ Saved! Changes are live.');
            else peToast('Error saving', true);
        } catch(e) { peToast('Save failed', true); }
    };

    // ─── Reset page edits ─────────────────────────────────────────────
    window.peReset = async function() {
        if (!confirm('Reset all edits on this page?')) return;
        try {
            await fetch('/admin/page-editor/reset', {
                method: 'POST',
                headers: { 'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}' },
                body: JSON.stringify({ path: location.pathname }),
            });
            peToast('Reset done. Reloading...');
            setTimeout(() => location.reload(), 1000);
        } catch(e) { peToast('Reset failed', true); }
    };

    // ─── Unique CSS selector ──────────────────────────────────────────
    function getSelector(el) {
        // Use data-pe-id if already set
        if (el.dataset.peId) return `[data-pe-id="${el.dataset.peId}"]`;

        // Build a selector
        let parts = [];
        let node  = el;
        while (node && node !== document.body) {
            let part = node.tagName.toLowerCase();
            if (node.id) { parts.unshift('#'+node.id); break; }
            if (node.className) {
                const cls = [...node.classList].filter(c => !['pe-editable','pe-selected'].includes(c));
                if (cls.length) part += '.' + cls.join('.');
            }
            const siblings = node.parentElement ? [...node.parentElement.children].filter(s => s.tagName === node.tagName) : [];
            if (siblings.length > 1) part += `:nth-of-type(${siblings.indexOf(node)+1})`;
            parts.unshift(part);
            node = node.parentElement;
        }
        const sel = parts.join(' > ').substring(0,200);

        // Store unique ID on element for future reference
        const uid = 'pe-' + Math.random().toString(36).substr(2,8);
        el.dataset.peId = uid;
        return `[data-pe-id="${uid}"]`;
    }

    // ─── Toast notification ───────────────────────────────────────────
    function peToast(msg, isError) {
        const t = document.getElementById('pe-toast');
        t.textContent = msg;
        t.className = 'show' + (isError ? ' error' : '');
        clearTimeout(window._peToastTimer);
        window._peToastTimer = setTimeout(() => t.className = '', 3000);
    }

    // ─── RGB to Hex helper ────────────────────────────────────────────
    function rgbToHex(rgb) {
        const m = rgb.match(/\d+/g);
        if (!m || m.length < 3) return '#000000';
        return '#' + [m[0],m[1],m[2]].map(x => parseInt(x).toString(16).padStart(2,'0')).join('');
    }

    // ─── Init ─────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', loadEdits);

})();
</script>
@endauth
