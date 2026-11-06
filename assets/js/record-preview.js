/**
 * At-a-glance previews of a linked record (discussion #91).
 *
 * dschipfel asked to see what is on the other end of a link without leaving the
 * page he is on, and asked specifically for "a small information badge/icon"
 * next to the link rather than a right-click menu, because right-click is not
 * discoverable and does not exist on a tablet.
 *
 * So the badge is a button rather than a hover target: hovering is the same
 * problem as right-clicking wearing a different hat — invisible until you
 * happen to do it, and unavailable on touch. It opens on click, on Enter and on
 * Space.
 *
 * ⚠️ It is a SPAN with role="button", not a <button>. Half the places a linked
 * record appears are pills that are themselves anchors, and a <button> inside an
 * <a> is invalid — browsers recover from it differently, and the badge would be
 * the one element whose behaviour depended on which screen you found it on. The
 * cost is handling Enter and Space by hand, below.
 *
 * ⚠️ ONE popover for the whole page. Two open previews would be two answers to
 * "what is this", and the second would be read as belonging to the first badge.
 *
 * 🔴 Every failure looks the same on screen. The server deliberately gives one
 * answer for "does not exist" and "you may not see it" — see
 * includes/record_preview.php — and a UI that distinguished them (an empty card
 * here, an error there) would leak exactly what the server withheld. A network
 * failure lands in the same place for the same reason.
 *
 * Usage from a module:
 *     html += FreeITSMPreview.badge('ticket', 42);
 * and, after changing a record whose preview may be cached:
 *     FreeITSMPreview.forget('ticket', 42);
 *
 * @wiki Record-Previews-Developer-Guide
 */
(function () {
    'use strict';

    // ⚠️ Derived from this file's own URL, not from the page's depth. Modules
    // live at different depths and pretty URLs make a page's apparent depth a
    // lie, so a hardcoded '../api/' would be right in some places and quietly
    // wrong in others.
    var ROOT = (function () {
        var s = document.currentScript && document.currentScript.src;
        if (!s) {
            var all = document.getElementsByTagName('script');
            for (var i = all.length - 1; i >= 0; i--) {
                if (/record-preview\.js/.test(all[i].src)) { s = all[i].src; break; }
            }
        }
        return s ? s.replace(/assets\/js\/record-preview\.js.*$/, '') : '../';
    })();
    var API = ROOT + 'api/system/record_preview.php';

    var cache = {};       // 'type:id' -> preview object, or the string 'gone'
    var pop = null;       // the one popover element
    var openFor = null;   // the badge it belongs to

    /** HTML-escapes a value; null and undefined become an empty string. */
    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    /** A `common.preview.*` string, with an English fallback if i18n is absent. */
    function rt(key, fallback) {
        if (typeof window.t !== 'function') return fallback;
        var out = window.t('common.preview.' + key);
        // i18n.js hands back the key itself when it has nothing; never show that.
        return (!out || out.indexOf('common.preview.') === 0) ? fallback : out;
    }

    var INFO_SVG = '<svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" ' +
        'stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
        '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/>' +
        '<line x1="12" y1="8" x2="12.01" y2="8"/></svg>';

    /**
     * The badge, as an HTML string — modules build their rows with template
     * literals, so handing back a node would mean every caller changed shape.
     */
    function badge(type, id) {
        if (!type || !id) return '';
        var label = rt('aria', 'Preview this record');
        return '<span class="rp-badge" role="button" tabindex="0" data-rp-type="' + esc(type) + '"' +
               ' data-rp-id="' + esc(id) + '" aria-label="' + esc(label) + '"' +
               ' aria-expanded="false" title="' + esc(label) + '">' + INFO_SVG + '</span>';
    }

    /** Returns the page's one popover element, creating it hidden on first use. */
    function ensurePopover() {
        if (pop) return pop;
        pop = document.createElement('div');
        pop.className = 'rp-popover';
        pop.setAttribute('role', 'dialog');
        pop.hidden = true;
        document.body.appendChild(pop);
        return pop;
    }

    /** Hides the popover, drops the phone sheet mode and resets the badge it belonged to. */
    function close() {
        if (!pop) return;
        pop.hidden = true;
        pop.classList.remove('rp-sheet');
        if (openFor) {
            openFor.setAttribute('aria-expanded', 'false');
            openFor.classList.remove('rp-badge--open');
        }
        openFor = null;
        document.body.classList.remove('rp-sheet-open');
    }

    /**
     * Anchor the card to its badge, flipping when there is no room.
     *
     * ⚠️ position:fixed and viewport coordinates, so a card opened from inside a
     * scrolling panel is not clipped by it. The trade is that it does not travel
     * with the scroll — which is why scrolling closes it.
     */
    function place(anchor) {
        // A phone has nowhere to put a 300px card beside a badge, so it becomes
        // a sheet at the bottom of the screen rather than a popover that has
        // been squeezed until it is unreadable.
        if (window.innerWidth < 560) {
            pop.classList.add('rp-sheet');
            pop.style.left = '';
            pop.style.top = '';
            document.body.classList.add('rp-sheet-open');
            return;
        }
        pop.classList.remove('rp-sheet');
        document.body.classList.remove('rp-sheet-open');

        var r = anchor.getBoundingClientRect();
        var w = pop.offsetWidth, h = pop.offsetHeight, gap = 8, pad = 10;

        var left = r.left;
        if (left + w > window.innerWidth - pad) left = window.innerWidth - w - pad;
        if (left < pad) left = pad;

        var top = r.bottom + gap;
        if (top + h > window.innerHeight - pad) {
            var above = r.top - h - gap;
            // Above only when it actually fits there. Otherwise pin it in view
            // rather than flipping it off the top of the screen.
            top = above >= pad ? above : Math.max(pad, window.innerHeight - h - pad);
        }
        pop.style.left = Math.round(left) + 'px';
        pop.style.top  = Math.round(top) + 'px';
    }

    /** Shows the loading message in the popover next to the badge. */
    function renderLoading(anchor) {
        pop.innerHTML = '<div class="rp-loading">' + esc(rt('loading', 'Loading…')) + '</div>';
        pop.hidden = false;
        place(anchor);
    }

    /**
     * Shows the single "cannot be shown" card - used alike for not found, not allowed and network
     * failure.
     */
    function renderUnavailable(anchor, message) {
        pop.innerHTML = '<div class="rp-unavailable">' +
            esc(message || rt('unavailable', 'That record cannot be shown here.')) + '</div>';
        pop.hidden = false;
        place(anchor);
    }

    /**
     * Draws a record preview into the popover - heading, label / value rows (with a colour dot where
     * given), lead text and an Open link.
     */
    function render(anchor, p) {
        var rows = (p.fields || []).map(function (f) {
            // A status or priority colour comes from the record's own settings,
            // so it is a value, not a class — there is no stylesheet rule for a
            // colour somebody typed in yesterday.
            var dot = f.colour
                ? '<span class="rp-dot" style="background:' + esc(f.colour) + '"></span>'
                : '';
            return '<div class="rp-row"><span class="rp-label">' + esc(f.label) + '</span>' +
                   '<span class="rp-value">' + dot + esc(f.value) + '</span></div>';
        }).join('');

        var lead = p.lead ? '<div class="rp-lead">' + esc(p.lead) + '</div>' : '';

        var open = p.url
            ? '<a class="rp-open" href="' + esc(p.url) + '" target="_blank" rel="noopener">' +
              esc(rt('open', 'Open')) + '</a>'
            : '';

        pop.innerHTML =
            '<div class="rp-head">' + esc(p.heading || '') + '</div>' +
            (rows ? '<div class="rp-fields">' + rows + '</div>' : '') +
            lead +
            (open ? '<div class="rp-foot">' + open + '</div>' : '');
        pop.hidden = false;
        place(anchor);
    }

    /**
     * Opens the popover for a badge, from the cache when possible, otherwise fetching
     * record_preview.php and caching the answer (or "gone").
     */
    async function show(anchor) {
        var type = anchor.getAttribute('data-rp-type');
        var id   = anchor.getAttribute('data-rp-id');
        var key  = type + ':' + id;

        ensurePopover();
        openFor = anchor;
        anchor.setAttribute('aria-expanded', 'true');
        anchor.classList.add('rp-badge--open');

        if (cache[key] === 'gone') { renderUnavailable(anchor); return; }
        if (cache[key]) { render(anchor, cache[key]); return; }

        renderLoading(anchor);
        try {
            var res  = await fetch(API + '?type=' + encodeURIComponent(type) + '&id=' + encodeURIComponent(id));
            var data = await res.json();
            // ⚠️ The badge may already be closed, or a different one opened, by
            // the time this lands. Drawing into the popover regardless would put
            // one record's details under another record's badge.
            if (openFor !== anchor) return;
            if (!data.success || !data.preview) {
                cache[key] = 'gone';
                renderUnavailable(anchor, data.error);
                return;
            }
            cache[key] = data.preview;
            render(anchor, data.preview);
        } catch (e) {
            // 🔴 The same card as a refusal, on purpose. See the header.
            if (openFor === anchor) renderUnavailable(anchor);
        }
    }

    // --- Wiring -------------------------------------------------------------
    // Delegated, so a badge drawn into a table an hour from now works without
    // anybody remembering to bind it.
    document.addEventListener('click', function (e) {
        var b = e.target.closest ? e.target.closest('.rp-badge') : null;
        if (b) {
            e.preventDefault();
            e.stopPropagation();
            // A second click on the open badge closes it — the badge is the
            // toggle, so it has to work in both directions.
            if (openFor === b && pop && !pop.hidden) { close(); return; }
            show(b);
            return;
        }
        // A click inside the card is not a dismissal; the Open link lives there.
        if (pop && !pop.hidden && !(e.target.closest && e.target.closest('.rp-popover'))) close();
    }, true);

    // Enter and Space, which a real <button> would have given us for free. See
    // the header for why the badge cannot be one.
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter' && e.key !== ' ' && e.key !== 'Spacebar') return;
        var b = e.target && e.target.closest ? e.target.closest('.rp-badge') : null;
        if (!b) return;
        e.preventDefault();          // Space would otherwise scroll the page.
        e.stopPropagation();
        if (openFor === b && pop && !pop.hidden) { close(); return; }
        show(b);
    }, true);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && pop && !pop.hidden) {
            var back = openFor;
            close();
            if (back) back.focus();   // Escape should not lose your place.
        }
    });

    // The card is positioned in viewport coordinates and does not follow the
    // page, so anything that moves the badge closes it rather than leaving it
    // pointing at empty space.
    window.addEventListener('scroll', function () { if (pop && !pop.hidden) close(); }, true);
    window.addEventListener('resize', function () { if (pop && !pop.hidden) close(); });

    window.FreeITSMPreview = {
        badge: badge,
        close: close,
        /** Drop a cached preview after the record behind it has been edited. */
        forget: function (type, id) {
            if (type === undefined) { cache = {}; return; }
            delete cache[type + ':' + id];
        }
    };
})();
