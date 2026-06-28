/**
 * Global Confirmation Modal
 *
 * One shared OK/Cancel dialog for the whole app. Look + behaviour modelled
 * on the calendar settings delete-category modal (#450). Auto-injects its
 * own markup + styles on first use so individual pages don't need any
 * HTML scaffolding — just call showConfirm({...}).
 *
 * Usage:
 *   // Callback-style:
 *   showConfirm({
 *       title: 'Delete category?',
 *       message: 'This cannot be undone.',
 *       okLabel: 'Delete',
 *       okClass: 'danger',
 *       onConfirm: () => doDelete()
 *   });
 *
 *   // Promise-style:
 *   const ok = await showConfirm({
 *       title: 'Delete category?',
 *       message: 'This cannot be undone.',
 *       okLabel: 'Delete',
 *       okClass: 'danger'
 *   });
 *   if (ok) doDelete();
 *
 * Options:
 *   title       - Modal heading text. Default: 'Confirm'.
 *   message     - Body text (plain text, not HTML).
 *   okLabel     - Label for the OK button. Default: 'OK'.
 *   okClass     - 'primary' | 'danger'. Drives the OK button colour. Default: 'primary'.
 *   cancelLabel - Label for the Cancel button. Default: 'Cancel'.
 *   onConfirm   - Callback fired when the user confirms.
 *   onCancel    - Callback fired when the user cancels (or dismisses).
 *
 * Returns: Promise<boolean> — resolves true on confirm, false on cancel.
 */
(function() {
    if (window.showConfirm) return; // already loaded

    // Colours go through theme tokens with light-colour fallbacks: on pages
    // without a palette (no theme.css) the fallbacks render the exact original
    // light dialog (zero change app-wide); where a dark palette is active
    // (data-theme set, e.g. Tickets) the dialog themes to match automatically.
    var style = document.createElement('style');
    style.textContent =
        '.fitsm-confirm-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.5);' +
            'z-index:99998;display:flex;align-items:center;justify-content:center;' +
            'opacity:0;visibility:hidden;transition:opacity 0.2s ease,visibility 0.2s ease;' +
            'font-family:"Segoe UI",Tahoma,Geneva,Verdana,sans-serif}' +
        '.fitsm-confirm-overlay.active{opacity:1;visibility:visible}' +
        '.fitsm-confirm-modal{background:var(--surface,#fff);border-radius:8px;width:450px;max-width:90vw;' +
            'box-shadow:0 10px 40px var(--shadow,rgba(0,0,0,0.2));transform:scale(0.95) translateY(-10px);' +
            'transition:transform 0.2s ease}' +
        '.fitsm-confirm-overlay.active .fitsm-confirm-modal{transform:scale(1) translateY(0)}' +
        '.fitsm-confirm-header{padding:20px;border-bottom:1px solid var(--border,#e0e0e0)}' +
        '.fitsm-confirm-header h3{margin:0;font-size:18px;font-weight:600;color:var(--text,#333)}' +
        '.fitsm-confirm-body{padding:20px;color:var(--text-muted,#555);font-size:14px;line-height:1.5}' +
        '.fitsm-confirm-body p{margin:0;white-space:pre-wrap}' +
        '.fitsm-confirm-footer{padding:15px 20px;border-top:1px solid var(--border,#e0e0e0);display:flex;' +
            'justify-content:flex-end;gap:10px}' +
        '.fitsm-confirm-btn{padding:8px 16px;border-radius:4px;font-size:13px;cursor:pointer;' +
            'border:none;transition:background 0.15s;font-family:inherit;font-weight:500}' +
        '.fitsm-confirm-btn:focus-visible{outline:2px solid var(--accent,#0078d4);outline-offset:2px}' +
        '.fitsm-confirm-btn-secondary{background:var(--surface-hover,#f0f0f0);color:var(--text,#333);border:1px solid var(--border,#ddd)}' +
        '.fitsm-confirm-btn-secondary:hover{background:var(--surface-2,#e0e0e0)}' +
        '.fitsm-confirm-btn-primary{background:var(--accent,#0078d4);color:#fff}' +
        '.fitsm-confirm-btn-primary:hover{background:var(--accent-hover,#005ea5)}' +
        '.fitsm-confirm-btn-danger{background:#c62828;color:#fff}' +
        '.fitsm-confirm-btn-danger:hover{background:#a02020}';
    document.head.appendChild(style);

    var overlay = null;
    var titleEl = null;
    var bodyEl = null;
    var cancelBtn = null;
    var okBtn = null;
    var currentOnConfirm = null;
    var currentOnCancel = null;
    var currentResolve = null;
    var keyHandler = null;

    function build() {
        if (overlay) return;
        overlay = document.createElement('div');
        overlay.className = 'fitsm-confirm-overlay';
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-modal', 'true');

        var modal = document.createElement('div');
        modal.className = 'fitsm-confirm-modal';

        var header = document.createElement('div');
        header.className = 'fitsm-confirm-header';
        titleEl = document.createElement('h3');
        header.appendChild(titleEl);
        modal.appendChild(header);

        var body = document.createElement('div');
        body.className = 'fitsm-confirm-body';
        bodyEl = document.createElement('p');
        body.appendChild(bodyEl);
        modal.appendChild(body);

        var footer = document.createElement('div');
        footer.className = 'fitsm-confirm-footer';
        cancelBtn = document.createElement('button');
        cancelBtn.type = 'button';
        cancelBtn.className = 'fitsm-confirm-btn fitsm-confirm-btn-secondary';
        cancelBtn.addEventListener('click', handleCancel);
        footer.appendChild(cancelBtn);

        okBtn = document.createElement('button');
        okBtn.type = 'button';
        okBtn.addEventListener('click', handleConfirm);
        footer.appendChild(okBtn);

        modal.appendChild(footer);
        overlay.appendChild(modal);

        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) handleCancel();
        });

        document.body.appendChild(overlay);
    }

    function handleConfirm() {
        var cb = currentOnConfirm;
        var resolve = currentResolve;
        close();
        if (typeof cb === 'function') cb();
        if (typeof resolve === 'function') resolve(true);
    }

    function handleCancel() {
        var cb = currentOnCancel;
        var resolve = currentResolve;
        close();
        if (typeof cb === 'function') cb();
        if (typeof resolve === 'function') resolve(false);
    }

    function close() {
        if (!overlay) return;
        overlay.classList.remove('active');
        if (keyHandler) {
            document.removeEventListener('keydown', keyHandler);
            keyHandler = null;
        }
        currentOnConfirm = null;
        currentOnCancel = null;
        currentResolve = null;
    }

    window.showConfirm = function(opts) {
        opts = opts || {};
        build();

        titleEl.textContent = opts.title || 'Confirm';
        bodyEl.textContent = opts.message || '';
        okBtn.textContent = opts.okLabel || 'OK';
        cancelBtn.textContent = opts.cancelLabel || 'Cancel';

        var cls = (opts.okClass === 'danger') ? 'danger' : 'primary';
        okBtn.className = 'fitsm-confirm-btn fitsm-confirm-btn-' + cls;

        currentOnConfirm = opts.onConfirm || null;
        currentOnCancel = opts.onCancel || null;

        return new Promise(function(resolve) {
            currentResolve = resolve;

            keyHandler = function(e) {
                if (e.key === 'Escape') handleCancel();
                else if (e.key === 'Enter' && document.activeElement !== cancelBtn) handleConfirm();
            };
            document.addEventListener('keydown', keyHandler);

            requestAnimationFrame(function() {
                overlay.classList.add('active');
                requestAnimationFrame(function() { okBtn.focus(); });
            });
        });
    };
})();
