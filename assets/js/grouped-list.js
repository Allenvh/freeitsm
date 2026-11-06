/**
 * FreeITSM grouped list (GH #208)
 *
 * Takes rows a page has already filtered and sorted, and lays them out one of
 * four ways:
 *   - flat    no grouping: one list, paged
 *   - tree    collapsible groups, up to two levels, with Expand / Collapse
 *   - tabs    one tab per first-level group (the settings-screen tab bar), the
 *             second level as headed sections inside the tab
 *   - jump    every group on one page, with a sticky side list to jump between
 *             them (a dropdown on a phone - mobile.css)
 *
 * The page owns the controls and the filtering; this owns the layout, the
 * per-group paging, and which groups are open. It knows nothing about analysts,
 * so Teams or Requesters can use it as they are.
 *
 * ⚠️ A row can be in MORE THAN ONE group. A grouper returns an array, and the row
 * is shown under every group it names - an analyst in two teams is in both team
 * groups. Counting each row once would mean choosing a winner, and the list
 * would then say something untrue about the other group.
 *
 * Usage:
 *   const list = createGroupedList({
 *     mount, scrollEl,                        // where to draw; what scrolls (jump nav)
 *     groupers: { team: row => [{ key, label, sort? }, ...] },
 *     renderTable: (rows, ctx) => html,       // one leaf's rows; ctx.keys = group keys
 *     emptyHtml, labels: { expand, collapse, prev, next, range, of, jumpTo },
 *     onStateChange: state => {}              // open groups / tab changed by the reader
 *   });
 *   list.render(rows, { allRows, filtered, view })
 *
 * view: { group1, group2, layout, page_size, open: [path]|null, tab }
 */
(function () {
    'use strict';

    const SEP = '\u001f';   // joins group keys into a path; cannot appear in a key we build

    /**
     * Builds one grouped list inside a mount element.
     *
     * Exposed as window.createGroupedList.
     * @return object  render(rows, opts), expandAll(), collapseAll()
     */
    function createGroupedList(cfg) {
        const mount = cfg.mount;
        const scrollEl = cfg.scrollEl || window;
        const L = Object.assign({
            expand: 'Expand', collapse: 'Collapse', prev: 'Previous', next: 'Next',
            range: '{from}-{to} of {total}', of: '{shown} of {total}', jumpTo: 'Jump to',
        }, cfg.labels || {});

        let rows = [], allRows = [], filtered = false;
        let view = { group1: '', group2: '', layout: 'tree', page_size: 0, open: null, tab: '' };
        const pages = {};        // path -> page number, for this visit only
        let openSet = null;      // the reader's open groups (persisted through onStateChange)
        let filterClosed = new Set();   // groups closed by hand while a filter is on (not saved)
        let lastFilterSig = '';
        let jumpSections = [];

        mount.addEventListener('click', onClick);
        mount.addEventListener('change', onChange);
        if (scrollEl) scrollEl.addEventListener('scroll', spyJump, { passive: true });

        /** Escape text for HTML, quotes included. */
        function esc(s) {
            return String(s == null ? '' : s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
        }

        function fmt(s, vars) { return String(s).replace(/\{(\w+)\}/g, (m, k) => (k in vars ? vars[k] : m)); }

        /**
         * Group rows by one grouper, then (when given) each group again by the next.
         *
         * @return array  [{ key, label, path, rows, children }] sorted by sort, then label
         */
        function build(list, levels, parentPath) {
            const grouper = cfg.groupers[levels[0]];
            if (!grouper) return [];
            const map = new Map();
            list.forEach(r => {
                const seen = new Set();
                (grouper(r) || []).forEach(g => {
                    if (seen.has(g.key)) return;
                    seen.add(g.key);
                    if (!map.has(g.key)) map.set(g.key, { key: g.key, label: g.label, sort: g.sort == null ? 1 : g.sort, rows: [], spellings: {} });
                    const grp = map.get(g.key);
                    grp.rows.push(r);
                    grp.spellings[g.label] = (grp.spellings[g.label] || 0) + 1;
                });
            });
            // Free text grouped case-insensitively ("IT", "it"): head the group
            // with the spelling most rows use, not whichever sorted first.
            map.forEach(grp => {
                grp.label = Object.keys(grp.spellings).sort((a, b) => grp.spellings[b] - grp.spellings[a])[0];
                delete grp.spellings;
            });
            const groups = Array.from(map.values()).sort((a, b) =>
                (a.sort - b.sort) || String(a.label).localeCompare(String(b.label), undefined, { sensitivity: 'base', numeric: true }));
            groups.forEach(g => {
                g.path = parentPath ? parentPath + SEP + g.key : String(g.key);
                g.children = levels.length > 1 ? build(g.rows, levels.slice(1), g.path) : null;
            });
            return groups;
        }

        /** Rows per path in the unfiltered list, for "3 of 12" while filtering. */
        function totals(levels) {
            const out = {};
            (function walk(gs) { gs.forEach(g => { out[g.path] = g.rows.length; if (g.children) walk(g.children); }); })(build(allRows, levels, ''));
            return out;
        }

        function levels() {
            const lv = [];
            if (view.group1 && cfg.groupers[view.group1]) lv.push(view.group1);
            if (lv.length && view.group2 && view.group2 !== view.group1 && cfg.groupers[view.group2]) lv.push(view.group2);
            return lv;
        }

        /**
         * Draw everything for a new set of rows or a new view.
         *
         * @param array list  the rows after the page's filters and sort
         * @param object opts  { allRows, filtered, view }
         */
        function render(list, opts) {
            opts = opts || {};
            rows = list || [];
            allRows = opts.allRows || rows;
            filtered = !!opts.filtered;
            if (opts.view) {
                view = Object.assign({}, view, opts.view);
                openSet = Array.isArray(view.open) ? new Set(view.open) : null;
            }
            // A new filter starts with every matching group open again.
            const sig = filtered ? (opts.filterSig || 'on') : '';
            if (sig !== lastFilterSig) { filterClosed = new Set(); lastFilterSig = sig; }
            draw();
        }

        function draw() {
            const lv = levels();
            jumpSections = [];
            if (!rows.length) { mount.innerHTML = cfg.emptyHtml || ''; return; }
            if (!lv.length) { mount.innerHTML = '<div class="gl-flat">' + leaf(rows, 'flat', []) + '</div>'; return; }

            const groups = build(rows, lv, '');
            const tot = filtered ? totals(lv) : null;
            const count = g => tot && tot[g.path] !== undefined && tot[g.path] !== g.rows.length
                ? fmt(L.of, { shown: g.rows.length, total: tot[g.path] }) : String(g.rows.length);

            if (openSet === null) openSet = new Set(groups.length ? [groups[0].path] : []);

            if (view.layout === 'tabs') mount.innerHTML = drawTabs(groups, count);
            else if (view.layout === 'jump') mount.innerHTML = drawJump(groups, count);
            else mount.innerHTML = drawTree(groups, count);

            if (view.layout === 'jump') {
                jumpSections = Array.from(mount.querySelectorAll('.gl-jump-section'));
                spyJump();
            }
        }

        // ── Tree ─────────────────────────────────────────────────────────────
        function isOpen(path) {
            return filtered ? !filterClosed.has(path) : openSet.has(path);
        }

        function drawTree(groups, count) {
            const node = (g, depth) => {
                const open = isOpen(g.path);
                const keys = g.path.split(SEP);
                const body = g.children ? g.children.map(c => node(c, depth + 1)).join('') : leaf(g.rows, g.path, keys);
                return `<div class="gl-node gl-depth-${depth}${open ? ' is-open' : ''}">
                    <button type="button" class="gl-head" data-gl-toggle="${esc(g.path)}" aria-expanded="${open}">
                        <svg class="gl-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 6 15 12 9 18"></polyline></svg>
                        <span class="gl-label">${esc(g.label)}</span><span class="gl-count">${esc(count(g))}</span>
                    </button>
                    ${open ? `<div class="gl-body">${body}</div>` : ''}
                </div>`;
            };
            return `<div class="gl-bar">
                    <button type="button" class="btn btn-secondary gl-mini" data-gl-all="1">${esc(L.expand)}</button>
                    <button type="button" class="btn btn-secondary gl-mini" data-gl-all="0">${esc(L.collapse)}</button>
                </div>
                <div class="gl-tree">${groups.map(g => node(g, 1)).join('')}</div>`;
        }

        function allPaths() {
            const out = [];
            (function walk(gs) { gs.forEach(g => { out.push(g.path); if (g.children) walk(g.children); }); })(build(rows, levels(), ''));
            return out;
        }

        function setAll(open) {
            const paths = allPaths();
            if (filtered) filterClosed = open ? new Set() : new Set(paths);
            else { openSet = open ? new Set(paths) : new Set(); saveState(); }
            draw();
        }

        function toggle(path) {
            if (filtered) {
                if (filterClosed.has(path)) filterClosed.delete(path); else filterClosed.add(path);
            } else {
                if (openSet.has(path)) openSet.delete(path); else openSet.add(path);
                saveState();
            }
            draw();
        }

        // ── Tabs ─────────────────────────────────────────────────────────────
        function drawTabs(groups, count) {
            let active = groups.find(g => g.path === view.tab) || groups[0];
            const bar = `<div class="tabs gl-tabs" role="tablist">${groups.map(g =>
                `<button type="button" class="tab${g === active ? ' active' : ''}" role="tab" aria-selected="${g === active}" data-gl-tab="${esc(g.path)}">${esc(g.label)} <span class="gl-count">${esc(count(g))}</span></button>`).join('')}</div>`;
            const content = active.children
                ? active.children.map(c => `<section class="gl-section">
                        <h3 class="gl-section-title">${esc(c.label)} <span class="gl-count">${esc(count(c))}</span></h3>
                        ${leaf(c.rows, c.path, c.path.split(SEP))}
                    </section>`).join('')
                : leaf(active.rows, active.path, [active.key]);
            return bar + `<div class="gl-tab-panel" role="tabpanel">${content}</div>`;
        }

        // ── Jump nav ─────────────────────────────────────────────────────────
        function drawJump(groups, count) {
            let n = 0;
            const nav = [], opts = [], body = [];
            groups.forEach(g => {
                const id = 'gl-sec-' + (n++);
                nav.push(`<a href="#${id}" class="gl-jump-link gl-depth-1" data-gl-jump="${id}"><span class="gl-label">${esc(g.label)}</span><span class="gl-count">${esc(count(g))}</span></a>`);
                opts.push(`<option value="${id}">${esc(g.label)} (${esc(count(g))})</option>`);
                if (g.children) {
                    const inner = g.children.map(c => {
                        const cid = 'gl-sec-' + (n++);
                        nav.push(`<a href="#${cid}" class="gl-jump-link gl-depth-2" data-gl-jump="${cid}"><span class="gl-label">${esc(c.label)}</span><span class="gl-count">${esc(count(c))}</span></a>`);
                        opts.push(`<option value="${cid}"> ${esc(c.label)} (${esc(count(c))})</option>`);
                        return `<section class="gl-jump-section gl-depth-2" id="${cid}">
                            <h4 class="gl-section-title">${esc(c.label)} <span class="gl-count">${esc(count(c))}</span></h4>
                            ${leaf(c.rows, c.path, c.path.split(SEP))}
                        </section>`;
                    }).join('');
                    body.push(`<section class="gl-jump-section gl-depth-1" id="${id}"><h3 class="gl-section-title">${esc(g.label)} <span class="gl-count">${esc(count(g))}</span></h3>${inner}</section>`);
                } else {
                    body.push(`<section class="gl-jump-section gl-depth-1" id="${id}">
                        <h3 class="gl-section-title">${esc(g.label)} <span class="gl-count">${esc(count(g))}</span></h3>
                        ${leaf(g.rows, g.path, [g.key])}
                    </section>`);
                }
            });
            return `<div class="gl-jump">
                <nav class="gl-jump-nav" aria-label="${esc(L.jumpTo)}">${nav.join('')}</nav>
                <select class="gl-jump-select" aria-label="${esc(L.jumpTo)}" data-gl-jump-select="1">${opts.join('')}</select>
                <div class="gl-jump-body">${body.join('')}</div>
            </div>`;
        }

        function jumpTo(id) {
            const target = document.getElementById(id);
            if (!target) return;
            if (scrollEl === window) { target.scrollIntoView({ block: 'start' }); return; }
            const top = target.getBoundingClientRect().top - scrollEl.getBoundingClientRect().top + scrollEl.scrollTop - 8;
            scrollEl.scrollTo({ top: top });
        }

        /** Highlight the section at the top of the scroll area in the side list. */
        function spyJump() {
            if (!jumpSections.length) return;
            const edge = (scrollEl === window ? 0 : scrollEl.getBoundingClientRect().top) + 60;
            let current = jumpSections[0];
            jumpSections.forEach(s => { if (s.getBoundingClientRect().top <= edge) current = s; });
            mount.querySelectorAll('.gl-jump-link').forEach(a => a.classList.toggle('active', a.dataset.glJump === current.id));
            const sel = mount.querySelector('.gl-jump-select');
            if (sel && sel.value !== current.id) sel.value = current.id;
        }

        // ── Leaf: the rows themselves, paged ────────────────────────────────
        function leaf(list, path, keys) {
            const size = Number(view.page_size) || 0;
            if (!size || list.length <= size) return cfg.renderTable(list, { path, keys });
            const pageCount = Math.ceil(list.length / size);
            const page = Math.min(Math.max(1, pages[path] || 1), pageCount);
            const from = (page - 1) * size;
            const slice = list.slice(from, from + size);
            return cfg.renderTable(slice, { path, keys }) + `<div class="gl-pager">
                <span class="gl-range">${esc(fmt(L.range, { from: from + 1, to: from + slice.length, total: list.length }))}</span>
                <button type="button" class="btn btn-secondary gl-mini" data-gl-page="${esc(path)}" data-gl-to="${page - 1}" ${page <= 1 ? 'disabled' : ''}>${esc(L.prev)}</button>
                <button type="button" class="btn btn-secondary gl-mini" data-gl-page="${esc(path)}" data-gl-to="${page + 1}" ${page >= pageCount ? 'disabled' : ''}>${esc(L.next)}</button>
            </div>`;
        }

        // ── Events ───────────────────────────────────────────────────────────
        function onClick(e) {
            const el = e.target.closest('[data-gl-toggle],[data-gl-all],[data-gl-tab],[data-gl-page],[data-gl-jump]');
            if (!el || !mount.contains(el)) return;
            if (el.dataset.glToggle !== undefined) toggle(el.dataset.glToggle);
            else if (el.dataset.glAll !== undefined) setAll(el.dataset.glAll === '1');
            else if (el.dataset.glTab !== undefined) { view.tab = el.dataset.glTab; saveState(); draw(); }
            else if (el.dataset.glPage !== undefined) { pages[el.dataset.glPage] = Number(el.dataset.glTo); draw(); }
            else if (el.dataset.glJump !== undefined) { e.preventDefault(); jumpTo(el.dataset.glJump); }
        }

        function onChange(e) {
            if (e.target.matches('[data-gl-jump-select]')) jumpTo(e.target.value);
        }

        function saveState() {
            if (typeof cfg.onStateChange === 'function') {
                cfg.onStateChange({ open: openSet ? Array.from(openSet) : null, tab: view.tab || '' });
            }
        }

        return { render, expandAll: () => setAll(true), collapseAll: () => setAll(false) };
    }

    window.createGroupedList = createGroupedList;
})();
