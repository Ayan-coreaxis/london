/**
 * page-editor.js
 * 
 * Admin inline page editor — paragraphs aur text ko bold/italic/highlight karne ke liye.
 * Font family change NAHI hogi — sirf formatting allowed hai.
 * 
 * Place at: resources/js/admin/page-editor.js
 */

const PageEditor = {

    /**
     * Allowed formatting options ONLY — no font family
     */
    allowedStyles: ['fontWeight', 'fontStyle', 'backgroundColor', 'fontSize', 'textDecoration', 'color'],

    /**
     * Toolbar HTML — bold, italic, highlight buttons only
     */
    toolbarHTML: `
        <div id="pe-toolbar" style="
            position: fixed; top: 70px; right: 20px; z-index: 9999;
            background: #1e3a6e; color: white; border-radius: 8px;
            padding: 10px 14px; display: flex; gap: 8px; align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        ">
            <span style="font-size:12px; margin-right:4px;">Edit Mode</span>
            <button onclick="PageEditor.applyBold()" title="Bold"
                style="background:#fff; color:#1e3a6e; border:none; border-radius:4px;
                       padding:4px 10px; font-weight:bold; cursor:pointer;">B</button>
            <button onclick="PageEditor.applyItalic()" title="Italic"
                style="background:#fff; color:#1e3a6e; border:none; border-radius:4px;
                       padding:4px 10px; font-style:italic; cursor:pointer;">I</button>
            <button onclick="PageEditor.applyHighlight()" title="Highlight"
                style="background:#f5c518; color:#000; border:none; border-radius:4px;
                       padding:4px 10px; cursor:pointer;">H</button>
            <button onclick="PageEditor.clearFormat()" title="Clear formatting"
                style="background:#e8352a; color:#fff; border:none; border-radius:4px;
                       padding:4px 10px; cursor:pointer;">✕</button>
        </div>
    `,

    /** Currently selected element */
    selected: null,

    /** Initialize the editor */
    init() {
        // Inject toolbar
        document.body.insertAdjacentHTML('beforeend', this.toolbarHTML);

        // Make paragraphs, headings, spans clickable
        const editableSelectors = 'p, h1, h2, h3, h4, h5, h6, span.editable, li, blockquote';
        document.querySelectorAll(editableSelectors).forEach(el => {
            el.style.cursor = 'pointer';
            el.addEventListener('click', (e) => {
                e.stopPropagation();
                this.selectElement(el);
            });
        });

        console.log('[PageEditor] Initialized — font family changes are DISABLED');
    },

    selectElement(el) {
        // Deselect previous
        if (this.selected) {
            this.selected.style.outline = '';
        }
        this.selected = el;
        el.style.outline = '2px dashed #1e3a6e';
        console.log('[PageEditor] Selected:', el.tagName, el.className);
    },

    applyBold() {
        if (!this.selected) return alert('Please click on a text element first');
        const isBold = this.selected.style.fontWeight === 'bold' || 
                       window.getComputedStyle(this.selected).fontWeight >= 600;
        this.selected.style.fontWeight = isBold ? 'normal' : 'bold';
        this.saveEdit({ fontWeight: this.selected.style.fontWeight });
    },

    applyItalic() {
        if (!this.selected) return alert('Please click on a text element first');
        const isItalic = this.selected.style.fontStyle === 'italic';
        this.selected.style.fontStyle = isItalic ? 'normal' : 'italic';
        this.saveEdit({ fontStyle: this.selected.style.fontStyle });
    },

    applyHighlight() {
        if (!this.selected) return alert('Please click on a text element first');
        const isHighlighted = this.selected.style.backgroundColor === 'rgb(245, 197, 24)' ||
                              this.selected.style.backgroundColor === '#f5c518';
        this.selected.style.backgroundColor = isHighlighted ? '' : '#f5c518';
        this.selected.style.color = isHighlighted ? '' : '#1a1a1a';
        this.saveEdit({ 
            backgroundColor: this.selected.style.backgroundColor,
            color: this.selected.style.color
        });
    },

    clearFormat() {
        if (!this.selected) return;
        this.selected.style.fontWeight = '';
        this.selected.style.fontStyle = '';
        this.selected.style.backgroundColor = '';
        this.selected.style.color = '';
        this.selected.style.textDecoration = '';
        this.saveEdit({});  // Empty styles = clear
    },

    /**
     * Save to backend — fontFamily is NEVER sent
     */
    saveEdit(newStyles) {
        if (!this.selected) return;

        // Build selector for this element
        const selector = this.buildSelector(this.selected);
        const pagePath = window.location.pathname;

        // SECURITY: Remove fontFamily if somehow present
        delete newStyles.fontFamily;
        delete newStyles['font-family'];

        // Only allow whitelisted style keys
        const safeStyles = {};
        this.allowedStyles.forEach(key => {
            if (newStyles[key] !== undefined) {
                safeStyles[key] = newStyles[key];
            }
        });

        fetch('/admin/design/page-edit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                page_path: pagePath,
                selector: selector,
                styles: safeStyles,
                content: this.selected.innerText
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                console.log('[PageEditor] Saved:', selector, safeStyles);
            }
        })
        .catch(err => console.error('[PageEditor] Save failed:', err));
    },

    /**
     * Build a CSS selector for the element
     */
    buildSelector(el) {
        const tag = el.tagName.toLowerCase();
        const classes = Array.from(el.classList).slice(0, 2).join('.');
        return classes ? `${tag}.${classes}` : tag;
    },

    /**
     * Load saved edits from backend and apply to page
     * Call this on every page load in admin mode
     */
    loadEdits(edits) {
        edits.forEach(edit => {
            try {
                const el = document.querySelector(edit.selector);
                if (!el) return;

                const styles = JSON.parse(edit.styles);

                // Apply styles (fontFamily will never be in here from backend)
                Object.entries(styles).forEach(([prop, val]) => {
                    if (this.allowedStyles.includes(prop)) {
                        el.style[prop] = val;
                    }
                });

                if (edit.content) {
                    el.innerText = edit.content;
                }
            } catch (e) {
                console.warn('[PageEditor] Could not apply edit:', edit, e);
            }
        });
    }
};

// Auto-init if admin edit mode is active
if (document.body.dataset.editMode === 'true') {
    document.addEventListener('DOMContentLoaded', () => PageEditor.init());
}

window.PageEditor = PageEditor;
