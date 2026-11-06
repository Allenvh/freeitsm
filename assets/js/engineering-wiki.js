/*
 * Engineering wiki - the small browser round the pages (System -> Engineering wiki).
 *
 * Model:
 *   panes[]   one, or two side by side ("Open beside")
 *     tabs[]  each tab has its own history (a list of "page#anchor") and position
 *   the ACTIVE pane is the one the toolbar (back / forward / address) acts on.
 *
 * Links in a page carry data-ew-page. Click = open here; Ctrl/Cmd+click or middle-
 * click = new tab; right-click = Open / Open in new tab / Open beside / Copy link.
 * The open tabs are remembered in this browser (localStorage) - a convenience only;
 * ?page= in the address always wins.
 */
(function () {
    'use strict';

    var C = window.EW_CONFIG, T = C.t;
    var app = document.getElementById('ewApp');
    var panesEl = document.getElementById('ewPanes');
    var sidebar = document.getElementById('ewSidebar');
    var addr = document.getElementById('ewAddress');
    var search = document.getElementById('ewSearch');
    var btnBack = document.getElementById('ewBack');
    var btnFwd = document.getElementById('ewForward');
    var STORE = 'freeitsm.ew.tabs';
    var cache = {};               // page -> response (cleared by Reload)
    var nextId = 1;
    var panes = [];
    var activePane = 0;
    /** True on a phone-width screen (900px or less), where there is one pane and the sidebar slides in. */
    var narrow = function () { return window.matchMedia('(max-width: 900px)').matches; };

    var ICON = {
        close: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
        plus: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>',
        open: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>',
        tab: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="14" rx="2"></rect><path d="M3 11h18"></path><path d="M8 7V4h8v3"></path></svg>',
        beside: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"></rect><line x1="12" y1="4" x2="12" y2="20"></line></svg>',
        link: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>',
        caret: '<svg class="ew-caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
        folder: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>',
        list: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>'
    };

    /** Escapes a value for use in HTML text or an attribute. */
    function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
    /** Splits a "page#anchor" target into [page, anchor]; the anchor is '' when there is none. */
    function splitTarget(t) { var h = t.indexOf('#'); return h < 0 ? [t, ''] : [t.slice(0, h), t.slice(h + 1)]; }
    /** Returns the full address of a wiki page (with ?page=), for Copy link. */
    function pageUrl(target) { return location.origin + location.pathname + '?page=' + encodeURIComponent(target).replace(/%2F/g, '/').replace(/%23/g, '#'); }

    // ------------------------------------------------------------ data

    /**
     * Fetches one rendered page from the API, from the cache when it has been loaded before.
     *
     * Only successful pages are cached; a network error resolves to {success: false} rather than rejecting.
     */
    function fetchPage(page) {
        if (cache[page]) return Promise.resolve(cache[page]);
        return fetch(C.api + 'page.php?page=' + encodeURIComponent(page), { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (d) { if (d.success) cache[page] = d; return d; })
            .catch(function () { return { success: false }; });
    }

    // ------------------------------------------------------------ tabs and panes

    /** Creates a new tab object with its own history, starting at the given target. */
    function newTabObj(target) { return { id: nextId++, history: [target], index: 0, title: target, scroll: 0 }; }
    /** Returns the target a tab is showing now - its current history entry. */
    function cur(tab) { return tab.history[tab.index]; }

    /**
     * Opens a target in a new tab in a pane, after the active tab; a pane index past the end adds a new pane.
     *
     * A background tab does not take focus and only fetches its title for the tab strip.
     */
    function openIn(paneIdx, target, background) {
        if (paneIdx >= panes.length) { panes.push({ tabs: [], active: 0 }); paneIdx = panes.length - 1; }
        var pane = panes[paneIdx];
        var tab = newTabObj(target);
        pane.tabs.splice(pane.tabs.length ? pane.active + 1 : 0, 0, tab);
        if (!background || pane.tabs.length === 1) pane.active = pane.tabs.indexOf(tab);
        if (!background) activePane = paneIdx;
        // TRAP: renderAll() rebuilds EVERY pane's DOM, so every pane is redrawn (from
        // the cache - instant), not just this one, or the other pane goes blank.
        renderAll();
        panes.forEach(function (_, i) { showTab(i); });
        if (background && pane.tabs.length > 1) loadTitle(tab);
        save();
    }

    /**
     * Goes to a target in a pane's active tab, adding it to the tab's history like a browser does.
     *
     * Remembers the scroll position first; the same target again just redraws the tab.
     */
    function navigate(paneIdx, target) {
        var pane = panes[paneIdx], tab = pane.tabs[pane.active];
        var view = paneView(paneIdx);
        if (view) tab.scroll = view.scrollTop;
        if (cur(tab) === target) { showTab(paneIdx); return; }
        // Same page, new anchor: just move, and remember it like a browser does.
        tab.history = tab.history.slice(0, tab.index + 1);
        tab.history.push(target);
        tab.index++;
        activePane = paneIdx;
        showTab(paneIdx);
        save();
    }

    /** Moves the active tab back (-1) or forward (1) through its history. */
    function go(delta) {
        var pane = panes[activePane], tab = pane.tabs[pane.active];
        var n = tab.index + delta;
        if (n < 0 || n >= tab.history.length) return;
        tab.index = n;
        showTab(activePane);
        save();
    }

    /**
     * Closes a tab and picks the tab to show next.
     *
     * With two panes, closing a pane's last tab removes the pane; with one, the start page opens instead.
     */
    function closeTab(paneIdx, tabIdx) {
        var pane = panes[paneIdx];
        pane.tabs.splice(tabIdx, 1);
        if (!pane.tabs.length) {
            if (panes.length > 1) { panes.splice(paneIdx, 1); activePane = 0; }
            else { pane.tabs.push(newTabObj(C.start)); pane.active = 0; }
        } else if (pane.active >= tabIdx) {
            pane.active = Math.max(0, pane.active - (pane.active > tabIdx || pane.active === pane.tabs.length ? 1 : 0));
        }
        renderAll();
        panes.forEach(function (_, i) { showTab(i); });
        save();
    }

    /** Opens a target in the other pane, making a second pane if needed; on a narrow screen, a new tab. */
    function openBeside(target, fromPane) {
        if (narrow()) { openIn(activePane, target, false); return; }
        var other = panes.length > 1 ? (fromPane === 0 ? 1 : 0) : 1;
        openIn(other, target, false);
    }

    // ------------------------------------------------------------ rendering

    /** Returns the scrolling page area of a pane, or null when the pane is not drawn. */
    function paneView(i) { var p = panesEl.querySelector('.ew-pane[data-pane="' + i + '"]'); return p && p.querySelector('.ew-view'); }

    /**
     * Rebuilds every pane, its tab strip and the divider between two panes, with empty page areas.
     *
     * Callers follow it with showTab() for each pane to fill the pages in.
     */
    function renderAll() {
        panesEl.innerHTML = '';
        panesEl.classList.toggle('ew-split', panes.length > 1);
        panes.forEach(function (pane, pi) {
            if (pi > 0) {
                var div = document.createElement('div');
                div.className = 'ew-divider';
                bindDivider(div);
                panesEl.appendChild(div);
            }
            var el = document.createElement('section');
            el.className = 'ew-pane' + (pi === activePane ? ' ew-pane-active' : '');
            el.dataset.pane = pi;
            if (pane.flex) el.style.flex = pane.flex;
            var tabs = '<div class="ew-tabs" role="tablist">';
            pane.tabs.forEach(function (tab, ti) {
                tabs += '<div class="ew-tab' + (ti === pane.active ? ' ew-tab-active' : '') + '" role="tab" data-tab="' + ti + '" title="' + esc(cur(tab)) + '">' +
                    '<span class="ew-tab-title">' + esc(tab.title) + '</span>' +
                    '<button class="ew-tab-close" data-close="' + ti + '" title="' + esc(T.close_tab) + '">' + ICON.close + '</button></div>';
            });
            tabs += '<button class="ew-btn ew-tab-new" data-newtab="1" title="' + esc(T.new_tab) + '">' + ICON.plus + '</button></div>';
            el.innerHTML = tabs + '<div class="ew-view"></div>';
            panesEl.appendChild(el);
        });
    }

    /** Fetches a background tab's page so its tab can show the real title. */
    function loadTitle(tab) {
        var t = splitTarget(cur(tab));
        fetchPage(t[0]).then(function (d) { if (d.success) { tab.title = d.title; renderTabStrips(); } });
    }

    /** Updates the titles in every tab strip without rebuilding the panes. */
    function renderTabStrips() {
        panes.forEach(function (pane, pi) {
            var p = panesEl.querySelector('.ew-pane[data-pane="' + pi + '"]');
            if (!p) return;
            p.querySelectorAll('.ew-tab').forEach(function (el, ti) {
                if (pane.tabs[ti]) el.querySelector('.ew-tab-title').textContent = pane.tabs[ti].title;
            });
        });
    }

    /** Returns the breadcrumb HTML for a page path, naming the top folder Handbook or Code map. */
    function crumbs(page) {
        var parts = page.split('/'), out = [], path = '';
        parts.forEach(function (p, i) {
            path += (i ? '/' : '') + p;
            if (i === parts.length - 1) out.push('<span>' + esc(p) + '</span>');
            else out.push(i === 0 ? '<span>' + esc(p === 'code' ? T.code_map : T.handbook) + '</span>' : '<span>' + esc(p) + '</span>');
        });
        return out.join(' / ');
    }

    /**
     * Draws a pane's active tab: fetches its page and renders the header, badges, outline and body.
     *
     * Scrolls to the anchor when there is one, otherwise back to where the tab was. A result that arrives after
     * the tab has moved on is dropped.
     */
    function showTab(pi) {
        var pane = panes[pi]; if (!pane) return;
        var tab = pane.tabs[pane.active];
        var view = paneView(pi); if (!view) return;
        var target = cur(tab), t = splitTarget(target), page = t[0], anchor = t[1];
        if (!cache[page]) view.innerHTML = '<div class="ew-state">' + esc(T.loading) + '</div>';
        fetchPage(page).then(function (d) {
            if (pane.tabs[pane.active] !== tab || cur(tab) !== target) return;   // moved on while it loaded
            view = paneView(pi); if (!view) return;
            if (!d.success) {
                tab.title = page;
                view.innerHTML = '<div class="ew-state">' + esc(T.not_found) + '<br><code>' + esc(page) + '</code></div>';
            } else {
                tab.title = d.title;
                var toc = '';
                if (d.toc && d.toc.length) {
                    toc = '<button class="ew-outline-btn" data-outline="1">' + ICON.list + esc(T.outline) + '</button>';
                }
                var when = new Date(d.modified * 1000).toLocaleDateString(document.documentElement.lang || undefined, { day: 'numeric', month: 'short', year: 'numeric' });
                view.innerHTML =
                    '<div class="ew-page">' +
                        '<div class="ew-page-head"><span class="ew-crumbs">' + crumbs(page) + '</span>' +
                            (d.generated ? '<span class="ew-badge">' + esc(T.generated) + '</span>' : '') +
                            '<span class="ew-badge" title="' + esc(T.updated) + '">' + esc(when) + '</span>' + toc +
                        '</div>' +
                        '<article class="ew-markdown">' + d.html + '</article>' +
                    '</div>';
                view._toc = d.toc || [];
            }
            renderTabStrips();
            if (anchor) {
                // TRAP: not "target" - a var here is hoisted over the whole callback and
                // blanks the outer target, so every page looked out of date and never drew.
                var heading = view.querySelector('[id="' + CSS.escape(anchor) + '"]');
                if (heading) heading.scrollIntoView({ block: 'start' });
            } else {
                view.scrollTop = tab.scroll || 0;
            }
            tab.scroll = 0;
            markCurrent();
            updateToolbar();
            highlightActive();
        }).catch(function (err) {
            // Never leave a pane on "Loading..." for ever - say what went wrong.
            var v = paneView(pi);
            if (v) v.innerHTML = '<div class="ew-state">' + esc(T.not_found) + '<br><code>' + esc(String(err && err.message || err)) + '</code></div>';
        });
        updateToolbar();
    }

    /** Syncs back/forward, the address box, the window title and ?page= in the URL with the active tab. */
    function updateToolbar() {
        var pane = panes[activePane]; if (!pane) return;
        var tab = pane.tabs[pane.active];
        btnBack.disabled = tab.index <= 0;
        btnFwd.disabled = tab.index >= tab.history.length - 1;
        if (document.activeElement !== addr) addr.value = cur(tab);
        document.title = (tab.title || '') + ' - ' + (document.title.split(' - ').slice(-1)[0] || '');
        try { history.replaceState(null, '', '?page=' + encodeURIComponent(cur(tab)).replace(/%2F/g, '/').replace(/%23/g, '#')); } catch (e) { /* ignore */ }
    }

    /** Marks which pane is the active one. */
    function highlightActive() {
        panesEl.querySelectorAll('.ew-pane').forEach(function (p) { p.classList.toggle('ew-pane-active', +p.dataset.pane === activePane); });
    }

    // ------------------------------------------------------------ remembered tabs

    /** Remembers the open panes and tabs in localStorage, keeping the last 30 history entries per tab. */
    function save() {
        try {
            localStorage.setItem(STORE, JSON.stringify({
                activePane: activePane,
                panes: panes.map(function (p) { return { active: p.active, tabs: p.tabs.map(function (t) { return { history: t.history.slice(-30), index: Math.min(t.index, 29), title: t.title }; }) }; })
            }));
        } catch (e) { /* private window or storage off - the page still works */ }
    }

    /**
     * Puts back the panes and tabs saved in localStorage - one pane on a narrow screen.
     *
     * @return bool  true when something was restored
     */
    function restore() {
        try {
            var s = JSON.parse(localStorage.getItem(STORE) || 'null');
            if (!s || !s.panes || !s.panes.length) return false;
            panes = s.panes.slice(0, narrow() ? 1 : 2).map(function (p) {
                return { active: Math.min(p.active || 0, p.tabs.length - 1), tabs: p.tabs.map(function (t) { return { id: nextId++, history: t.history, index: Math.min(t.index, t.history.length - 1), title: t.title || t.history[0], scroll: 0 }; }) };
            }).filter(function (p) { return p.tabs.length; });
            activePane = Math.min(s.activePane || 0, panes.length - 1);
            return panes.length > 0;
        } catch (e) { return false; }
    }

    // ------------------------------------------------------------ sidebar: tree and search

    /** Builds one sidebar tree item - a folder that expands, or a link to a page. */
    function treeRow(item, isDir) {
        var li = document.createElement('li');
        li.className = 'ew-tree-node';
        var a = document.createElement('a');
        a.className = 'ew-tree-row';
        if (isDir) {
            a.innerHTML = ICON.caret + ICON.folder + '<span class="ew-tree-label">' + esc(item.name) + '</span>';
            a.dataset.dir = item.path;
        } else {
            a.innerHTML = '<svg viewBox="0 0 24 24"></svg>' + ICON.open + '<span class="ew-tree-label">' + esc(item.title) + '</span>';
            a.dataset.ewPage = item.page;
            a.href = '?page=' + item.page;
            a.title = item.page;
        }
        li.appendChild(a);
        return li;
    }

    /** Fetches one folder's pages and subfolders and adds them to the sidebar tree under an element. */
    function loadDir(dir, into) {
        return fetch(C.api + 'tree.php?dir=' + encodeURIComponent(dir), { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                var ul = document.createElement('ul');
                ul.className = 'ew-tree-node';
                (d.pages || []).forEach(function (p) { ul.appendChild(treeRow(p, false)); });
                (d.dirs || []).forEach(function (dd) { ul.appendChild(treeRow(dd, true)); });
                if (!(d.pages || []).length && !(d.dirs || []).length) ul.innerHTML = '<li class="ew-tree-empty">' + esc(dir.split('/').length === 1 && dir === 'code' ? T.nothing_yet : T.empty_folder) + '</li>';
                into.appendChild(ul);
                markCurrent();
            });
    }

    /** Rebuilds the sidebar with its two top sections, Handbook and Code map. */
    function buildTree() {
        sidebar.innerHTML = '';
        [['handbook', T.handbook], ['code', T.code_map]].forEach(function (s) {
            var h = document.createElement('div');
            h.className = 'ew-tree-section';
            h.textContent = s[1];
            sidebar.appendChild(h);
            var box = document.createElement('div');
            sidebar.appendChild(box);
            loadDir(s[0], box);
        });
    }

    /** Highlights the sidebar entry for the page the active tab is showing. */
    function markCurrent() {
        var pane = panes[activePane]; if (!pane) return;
        var page = splitTarget(cur(pane.tabs[pane.active]))[0];
        sidebar.querySelectorAll('.ew-tree-row[data-ew-page]').forEach(function (a) { a.classList.toggle('ew-current', a.dataset.ewPage === page); });
    }

    var searchTimer = null;
    /**
     * Searches the wiki and shows the results in the sidebar; under two characters it shows the tree again.
     *
     * Results for a query the box no longer holds are thrown away.
     */
    function runSearch(q) {
        if (q.trim().length < 2) { buildTree(); return; }
        fetch(C.api + 'search.php?q=' + encodeURIComponent(q), { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (search.value !== q) return;
                var rs = d.results || [];
                var html = '<div class="ew-results-head"><span>' + esc(T.results_for.replace('{q}', q)) + '</span><button data-clear="1">' + esc(T.clear) + '</button></div>';
                if (!rs.length) html += '<div class="ew-tree-empty">' + esc(T.no_results) + '</div>';
                rs.forEach(function (r) {
                    html += '<a class="ew-result" data-ew-page="' + esc(r.page) + '" href="?page=' + esc(r.page) + '"><strong>' + esc(r.title) + '</strong>' +
                        (r.snippet ? '<span>' + esc(r.snippet) + '</span>' : '') + '<em>' + esc(r.page) + '</em></a>';
                });
                sidebar.innerHTML = html;
            });
    }

    // ------------------------------------------------------------ right-click menu

    var menu = null;
    /** Removes the right-click menu, if one is open. */
    function closeMenu() { if (menu) { menu.remove(); menu = null; } }
    /**
     * Shows a right-click menu of items (icon, label, run) at a point, kept inside the window; '-' is a separator.
     */
    function showMenu(x, y, items) {
        closeMenu();
        menu = document.createElement('div');
        menu.className = 'ew-menu';
        items.forEach(function (it) {
            if (it === '-') { menu.appendChild(document.createElement('hr')); return; }
            var b = document.createElement('button');
            b.innerHTML = (it.icon || '') + '<span>' + esc(it.label) + '</span>';
            b.addEventListener('click', function () { closeMenu(); it.run(); });
            menu.appendChild(b);
        });
        document.body.appendChild(menu);
        var r = menu.getBoundingClientRect();
        menu.style.left = Math.min(x, window.innerWidth - r.width - 8) + 'px';
        menu.style.top = Math.min(y, window.innerHeight - r.height - 8) + 'px';
    }

    /** Shows the right-click menu for a page link: Open, Open in new tab, Open beside and Copy link. */
    function linkMenu(x, y, target, paneIdx) {
        showMenu(x, y, [
            { icon: ICON.open, label: T.open, run: function () { navigate(paneIdx, target); } },
            { icon: ICON.tab, label: T.open_new_tab, run: function () { openIn(paneIdx, target, false); } },
            { icon: ICON.beside, label: T.open_beside, run: function () { openBeside(target, paneIdx); } },
            '-',
            { icon: ICON.link, label: T.copy_link, run: function () { copy(pageUrl(target)); } }
        ]);
    }

    /**
     * Copies text to the clipboard, falling back to a hidden textarea outside a secure context, then shows a toast.
     */
    function copy(text) {
        /** Shows the "link copied" toast. */
        var done = function () { toast(T.link_copied); };
        if (navigator.clipboard && window.isSecureContext) navigator.clipboard.writeText(text).then(done, function () { fallbackCopy(text); done(); });
        else { fallbackCopy(text); done(); }
    }
    /** Copies text with a hidden textarea and execCommand, for browsers without the async clipboard. */
    function fallbackCopy(text) {
        var ta = document.createElement('textarea');
        ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
        document.body.appendChild(ta); ta.select();
        try { document.execCommand('copy'); } catch (e) { /* nothing more to try */ }
        ta.remove();
    }
    var toastEl = null, toastTimer = null;
    /** Shows a short message at the bottom of the page for 1.6 seconds. */
    function toast(msg) {
        if (!toastEl) { toastEl = document.createElement('div'); toastEl.className = 'ew-toast'; document.body.appendChild(toastEl); }
        toastEl.textContent = msg;
        toastEl.classList.add('ew-show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { toastEl.classList.remove('ew-show'); }, 1600);
    }

    // ------------------------------------------------------------ split divider

    /** Makes the divider between two panes draggable, sharing the width between them (20% to 80% each). */
    function bindDivider(div) {
        div.addEventListener('mousedown', function (e) {
            e.preventDefault();
            div.classList.add('ew-dragging');
            var rect = panesEl.getBoundingClientRect();
            /** Resizes the two panes to follow the mouse while the divider is dragged. */
            function move(ev) {
                var f = Math.min(0.8, Math.max(0.2, (ev.clientX - rect.left) / rect.width));
                panes[0].flex = f + ' 1 0'; panes[1].flex = (1 - f) + ' 1 0';
                var ps = panesEl.querySelectorAll('.ew-pane');
                ps[0].style.flex = panes[0].flex; ps[1].style.flex = panes[1].flex;
            }
            /** Ends a divider drag and removes its mouse listeners. */
            function up() { div.classList.remove('ew-dragging'); document.removeEventListener('mousemove', move); document.removeEventListener('mouseup', up); }
            document.addEventListener('mousemove', move);
            document.addEventListener('mouseup', up);
        });
    }

    // ------------------------------------------------------------ events

    /** Returns the index of the pane an element is in, or the active pane when it is in none. */
    function paneOf(el) { var p = el.closest('.ew-pane'); return p ? +p.dataset.pane : activePane; }

    panesEl.addEventListener('mousedown', function (e) {
        var pi = paneOf(e.target);
        if (pi !== activePane) { activePane = pi; highlightActive(); updateToolbar(); markCurrent(); }
    });

    panesEl.addEventListener('click', function (e) {
        var pi = paneOf(e.target);
        var close = e.target.closest('[data-close]');
        if (close) { e.stopPropagation(); closeTab(pi, +close.dataset.close); return; }
        if (e.target.closest('[data-newtab]')) { openIn(pi, C.start, false); return; }
        var tabEl = e.target.closest('.ew-tab');
        if (tabEl) { panes[pi].active = +tabEl.dataset.tab; activePane = pi; renderAll(); panes.forEach(function (_, i) { showTab(i); }); save(); return; }
        var ob = e.target.closest('[data-outline]');
        if (ob) { toggleOutline(ob, pi); return; }
        var ol = e.target.closest('[data-anchor]');
        if (ol) {
            e.preventDefault();
            var view = paneView(pi), t = view && view.querySelector('[id="' + CSS.escape(ol.dataset.anchor) + '"]');
            if (t) t.scrollIntoView({ block: 'start' });
            closeOutlines();
            return;
        }
        var a = e.target.closest('a[data-ew-page]');
        if (a) {
            e.preventDefault();
            if (e.ctrlKey || e.metaKey) openIn(pi, a.dataset.ewPage, true);
            else if (e.shiftKey) openBeside(a.dataset.ewPage, pi);
            else navigate(pi, a.dataset.ewPage);
            return;
        }
        var anchor = e.target.closest('a[href^="#"]');
        if (anchor) {
            e.preventDefault();
            var id = decodeURIComponent(anchor.getAttribute('href').slice(1));
            var page = splitTarget(cur(panes[pi].tabs[panes[pi].active]))[0];
            navigate(pi, page + '#' + id);
        }
    });

    // Middle-click: a new tab behind, as in a browser; on a tab it closes it.
    panesEl.addEventListener('auxclick', function (e) {
        if (e.button !== 1) return;
        var pi = paneOf(e.target);
        var tabEl = e.target.closest('.ew-tab');
        if (tabEl) { e.preventDefault(); closeTab(pi, +tabEl.dataset.tab); return; }
        var a = e.target.closest('a[data-ew-page]');
        if (a) { e.preventDefault(); openIn(pi, a.dataset.ewPage, true); }
    });

    panesEl.addEventListener('contextmenu', function (e) {
        var pi = paneOf(e.target);
        var a = e.target.closest('a[data-ew-page]');
        if (a) { e.preventDefault(); linkMenu(e.clientX, e.clientY, a.dataset.ewPage, pi); return; }
        var tabEl = e.target.closest('.ew-tab');
        if (tabEl) {
            e.preventDefault();
            var ti = +tabEl.dataset.tab, tab = panes[pi].tabs[ti];
            var items = [
                { icon: ICON.close, label: T.close_tab, run: function () { closeTab(pi, ti); } },
                { icon: ICON.tab, label: T.close_others, run: function () { panes[pi].tabs = [tab]; panes[pi].active = 0; renderAll(); panes.forEach(function (_, i) { showTab(i); }); save(); } }
            ];
            if (!narrow()) items.push({ icon: ICON.beside, label: T.move_beside, run: function () { var t = cur(tab); closeTab(pi, panes[pi].tabs.indexOf(tab)); openBeside(t, Math.min(pi, panes.length - 1)); } });
            items.push('-', { icon: ICON.link, label: T.copy_link, run: function () { copy(pageUrl(cur(tab))); } });
            showMenu(e.clientX, e.clientY, items);
        }
    });

    /** Closes any open page outline. */
    function closeOutlines() { document.querySelectorAll('.ew-outline').forEach(function (o) { o.remove(); }); }
    /** Opens or closes the outline (the page's headings) under a pane's Outline button. */
    function toggleOutline(btn, pi) {
        var open = btn.querySelector('.ew-outline');
        closeOutlines();
        if (open) return;
        var view = paneView(pi), toc = (view && view._toc) || [];
        var box = document.createElement('div');
        box.className = 'ew-outline';
        box.innerHTML = toc.map(function (h) { return '<a href="#' + esc(h.id) + '" data-anchor="' + esc(h.id) + '" class="ew-ol-' + h.level + '">' + esc(h.text) + '</a>'; }).join('');
        btn.appendChild(box);
    }

    sidebar.addEventListener('click', function (e) {
        if (e.target.closest('[data-clear]')) { search.value = ''; buildTree(); return; }
        var d = e.target.closest('[data-dir]');
        if (d) {
            e.preventDefault();
            var li = d.parentNode, kids = li.querySelector('ul');
            if (kids) { kids.remove(); d.classList.remove('ew-open'); }
            else { d.classList.add('ew-open'); loadDir(d.dataset.dir, li); }
            return;
        }
        var a = e.target.closest('a[data-ew-page]');
        if (a) {
            e.preventDefault();
            if (e.ctrlKey || e.metaKey) openIn(activePane, a.dataset.ewPage, true);
            else navigate(activePane, a.dataset.ewPage);
            if (narrow()) app.classList.remove('ew-sidebar-open');
        }
    });
    sidebar.addEventListener('auxclick', function (e) {
        var a = e.target.closest('a[data-ew-page]');
        if (a && e.button === 1) { e.preventDefault(); openIn(activePane, a.dataset.ewPage, true); }
    });
    sidebar.addEventListener('contextmenu', function (e) {
        var a = e.target.closest('a[data-ew-page]');
        if (a) { e.preventDefault(); linkMenu(e.clientX, e.clientY, a.dataset.ewPage, activePane); }
    });

    document.addEventListener('click', function (e) {
        if (menu && !menu.contains(e.target)) closeMenu();
        if (!e.target.closest('.ew-outline-btn')) closeOutlines();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeMenu(); closeOutlines(); }
        if (e.altKey && e.key === 'ArrowLeft') { e.preventDefault(); go(-1); }
        if (e.altKey && e.key === 'ArrowRight') { e.preventDefault(); go(1); }
    });
    window.addEventListener('resize', closeMenu);
    window.addEventListener('scroll', closeMenu, true);

    btnBack.addEventListener('click', function () { go(-1); });
    btnFwd.addEventListener('click', function () { go(1); });
    document.getElementById('ewReload').addEventListener('click', function () { cache = {}; panes.forEach(function (_, i) { showTab(i); }); if (!search.value) buildTree(); });
    document.getElementById('ewSidebarBtn').addEventListener('click', function () {
        if (narrow()) app.classList.toggle('ew-sidebar-open');
        else {
            app.classList.toggle('ew-no-sidebar');
            try { localStorage.setItem(STORE + '.sidebar', app.classList.contains('ew-no-sidebar') ? '0' : '1'); } catch (e) { /* ignore */ }
        }
    });
    addr.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            var v = addr.value.trim().replace(/^\/+/, '').replace(/\.md(#|$)/, '$1');
            if (v) navigate(activePane, v);
            addr.blur();
        } else if (e.key === 'Escape') { addr.blur(); updateToolbar(); }
    });
    addr.addEventListener('blur', updateToolbar);
    search.addEventListener('input', function () {
        clearTimeout(searchTimer);
        var q = search.value;
        searchTimer = setTimeout(function () { runSearch(q); }, 250);
    });
    search.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            var first = sidebar.querySelector('.ew-result');
            if (first) navigate(activePane, first.dataset.ewPage);
        }
    });

    // ------------------------------------------------------------ Rescan: small steps until done

    var rescanBtn = document.getElementById('ewRescan'), rescanText = document.getElementById('ewRescanText');
    /**
     * Runs the Code map scanner one step at a time through the API, showing progress, until it reports done.
     *
     * Rejects when the API fails or another scan is already running.
     * @return Promise  the final scan result
     */
    function rescanStep() {
        return fetch(C.api + 'scan.php', { method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/json' }, body: '{}' })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (!d.success) throw new Error(d.error || 'error');
                if (d.phase === 'busy') throw new Error(d.message);
                rescanText.textContent = T.scanning.replace('{done}', d.done_files).replace('{total}', d.total_files);
                return d.done ? d : rescanStep();
            });
    }
    rescanBtn.addEventListener('click', function () {
        rescanBtn.disabled = true;
        rescanText.textContent = T.scanning.replace('{done}', 0).replace('{total}', '...');
        rescanStep().then(function (d) {
            cache = {};
            panes.forEach(function (_, i) { showTab(i); });
            if (!search.value) buildTree();
            toast(T.scan_done.replace('{changed}', d.changed).replace('{pages}', d.pages));
        }).catch(function (e) { toast(T.scan_failed.replace('{error}', e.message)); })
          .then(function () { rescanBtn.disabled = false; rescanText.textContent = T.rescan; });
    });

    // ------------------------------------------------------------ start

    try { if (localStorage.getItem(STORE + '.sidebar') === '0' && !narrow()) app.classList.add('ew-no-sidebar'); } catch (e) { /* ignore */ }
    var restored = restore();
    if (!restored) panes = [{ tabs: [newTabObj(C.open || C.start)], active: 0 }];
    else if (C.open) {
        // A link to a page always opens it - in the tab already showing it, or a new one.
        var found = false;
        panes.forEach(function (p, pi) { p.tabs.forEach(function (t, ti) { if (!found && cur(t) === C.open) { p.active = ti; activePane = pi; found = true; } }); });
        if (!found) { var p0 = panes[activePane]; p0.tabs.splice(p0.active + 1, 0, newTabObj(C.open)); p0.active++; }
    }
    renderAll();
    panes.forEach(function (_, i) { showTab(i); });
    buildTree();
    save();
})();
