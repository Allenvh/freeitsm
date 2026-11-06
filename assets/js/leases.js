/**
 * Assets -> Leases (3.5.0). Drives both pages:
 *   asset-management/leases/          the list + Due back   (LEASES_CFG.page = 'list')
 *   asset-management/leases/view.php  one lease            (LEASES_CFG.page = 'lease')
 *
 * Rules live server-side in includes/leases.php; this file only renders and
 * posts. Modals are built here so both pages share one copy.
 */
(function () {
    'use strict';
    var CFG = window.LEASES_CFG || {};
    var API = CFG.api;
    var BASE = CFG.base;

    /** Translates a key from the asset-management.leases language section. */
    function t(k, p) { return window.t('asset-management.leases.' + k, p); }
    /** Translates a key from the shared asset-management.common language section (Save, Cancel, Edit...). */
    function tc(k, p) { return window.t('asset-management.common.' + k, p); }
    /** Escapes a value for safe use in HTML; null and undefined become an empty string. */
    function esc(s) {
        return String(s === null || s === undefined ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }
    /** Formats a plain date for display, escaped, or a dimmed dash when there is none. */
    function d(v) { return v ? esc(window.fmtNaiveDate ? window.fmtNaiveDate(v) : v) : '<span class="ls-dim">-</span>'; }
    /** Shows a toast message (success unless a type is given). */
    function toast(msg, type) { if (window.showToast) window.showToast(msg, type || 'success'); }
    /** Today's date as YYYY-MM-DD in the user's time zone. */
    function today() { return window.ymdInZone ? window.ymdInZone(new Date()) : new Date().toISOString().slice(0, 10); }
    /** Adds n days to a YYYY-MM-DD date and returns the new YYYY-MM-DD. */
    function addDays(ymd, n) { var x = new Date(ymd + 'T00:00:00Z'); x.setUTCDate(x.getUTCDate() + n); return x.toISOString().slice(0, 10); }
    /**
     * Formats an amount in the given currency, in the viewer's format (System -> Currencies, GH #189), or as
     * a plain number when the schedule has no currency - never a guessed symbol.
     */
    function money(v, cur) {
        if (v === null || v === undefined || v === '') return '';
        if (window.Money) return cur ? esc(window.Money.format(v, cur)) : esc(window.Money.number(v));
        try { if (cur) return new Intl.NumberFormat(document.documentElement.lang || 'en', { style: 'currency', currency: cur }).format(v); } catch (e) {}
        return Number(v).toLocaleString(document.documentElement.lang || 'en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + (cur ? ' ' + esc(cur) : '');
    }

    /**
     * GETs a Leases API endpoint with optional query parameters and returns the JSON, throwing when
     * success is false.
     */
    async function get(path, params) {
        var q = params ? '?' + new URLSearchParams(params).toString() : '';
        var r = await fetch(API + path + q, { credentials: 'same-origin' });
        var j = await r.json();
        if (!j.success) throw new Error(j.error || 'Error');
        return j;
    }
    /** POSTs a JSON body to a Leases API endpoint and returns the JSON, throwing when success is false. */
    async function post(path, body) {
        var r = await fetch(API + path, { method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body || {}) });
        var j = await r.json();
        if (!j.success) throw new Error(j.error || 'Error');
        return j;
    }
    /** Shows an error as an error toast. */
    function fail(e) { toast(e.message || String(e), 'error'); }

    // "Ends" with how near it is: overdue / within 90 days.
    function endBadge(end) {
        if (!end) return d(end);
        var now = today();
        if (end < now) return d(end) + ' <span class="ls-badge danger">' + esc(t('overdue')) + '</span>';
        if (end <= addDays(now, 90)) return d(end) + ' <span class="ls-badge warn">' + esc(t('due_soon')) + '</span>';
        return d(end);
    }

    // ===================================================================
    // Modals (one generic builder)
    // ===================================================================
    function modal(title, bodyHtml, onSave, saveLabel) {
        var wrap = document.createElement('div');
        wrap.className = 'modal active ls-modal';
        wrap.innerHTML = '<div class="modal-content"><div class="modal-header">' + esc(title) + '</div>'
            + '<form class="ls-form">' + bodyHtml
            + '<div class="modal-actions"><button type="button" class="btn btn-secondary" data-x>' + esc(tc('cancel')) + '</button>'
            + '<button type="submit" class="btn btn-primary">' + esc(saveLabel || tc('save')) + '</button></div></form></div>';
        document.body.appendChild(wrap);
        /** Removes the modal from the page. */
        var close = function () { wrap.remove(); };
        wrap.querySelector('[data-x]').onclick = close;
        wrap.addEventListener('mousedown', function (e) { if (e.target === wrap) close(); });
        wrap.querySelector('form').addEventListener('submit', async function (e) {
            e.preventDefault();
            var btn = wrap.querySelector('button[type=submit]');
            btn.disabled = true;
            try { if (await onSave(wrap) !== false) close(); }
            catch (err) { fail(err); }
            finally { btn.disabled = false; }
        });
        var first = wrap.querySelector('input:not([type=hidden]),select,textarea');
        if (first) setTimeout(function () { first.focus(); }, 50);
        return wrap;
    }
    /** HTML for one labelled form group, with an optional hint line under it. */
    function field(label, inner, hint) {
        return '<div class="form-group"><label>' + esc(label) + '</label>' + inner + (hint ? '<div class="form-hint">' + esc(hint) + '</div>' : '') + '</div>';
    }
    /** HTML option list from {id, name} rows, marking the selected id and adding an optional blank first option. */
    function opts(list, sel, blank) {
        var h = blank !== undefined ? '<option value="">' + esc(blank) + '</option>' : '';
        return h + list.map(function (o) { return '<option value="' + o.id + '"' + (String(o.id) === String(sel) ? ' selected' : '') + '>' + esc(o.name) + '</option>'; }).join('');
    }
    /** The trimmed value of the named field inside a modal, or an empty string when the field is missing. */
    function val(w, name) { var el = w.querySelector('[name="' + name + '"]'); return el ? el.value.trim() : ''; }

    // ---- Lease (add / edit) ----
    /**
     * Opens the Add / Edit lease modal and saves it through save.php. Loads the options (companies,
     * suppliers, cost centres, contracts) first; changing the company reloads that company's cost centres
     * and contracts. A new lease opens its own page once saved.
     */
    async function leaseForm(lease) {
        var o;
        try { o = await get('options.php', { company_id: lease ? lease.company_id : '', keep_cost_centre: lease && lease.cost_centre_id ? lease.cost_centre_id : '' }); }
        catch (e) { fail(e); return; }
        /** Turns cost centre rows into {id, name} options labelled "code - name", marking inactive ones. */
        var ccList = function (list) { return list.map(function (c) { return { id: c.id, name: c.code + ' - ' + c.name + (c.is_active ? '' : ' (' + t('inactive') + ')') }; }); };
        var body = field(t('f_name'), '<input type="text" name="name" required maxlength="200" value="' + esc(lease ? lease.name : '') + '">', t('f_name_hint'))
            + field(t('f_reference'), '<input type="text" name="reference" maxlength="100" value="' + esc(lease ? lease.reference || '' : '') + '">')
            + (o.companies.length > 1 ? field(t('f_company'), '<select name="company_id">' + opts(o.companies, o.company_id) + '</select>',
                lease && lease.has_assets ? t('f_company_locked') : '') : '')
            + field(t('f_supplier'), '<select name="supplier_id">' + opts(o.suppliers, lease ? lease.supplier_id : '', t('none')) + '</select>')
            + field(t('f_cost_centre'), '<select name="cost_centre_id">' + opts(ccList(o.cost_centres), lease ? lease.cost_centre_id : '', t('none')) + '</select>')
            // GH #217: no contract picker while Contracts is switched off - and no contract_id sent, so a linked one is kept.
            + (CFG.contracts !== false ? field(t('f_contract'), '<select name="contract_id">' + opts(o.contracts, lease ? lease.contract_id : '', t('none')) + '</select>', t('f_contract_hint')) : '')
            + field(t('f_notice_days'), '<input type="number" name="notice_days" min="0" max="3650" value="' + esc(lease && lease.notice_days !== null ? lease.notice_days : '') + '">', t('f_notice_hint'))
            + field(t('f_notes'), '<textarea name="notes" rows="3">' + esc(lease ? lease.notes || '' : '') + '</textarea>');
        var w = modal(lease ? t('edit_lease') : t('add_lease'), body, async function (w) {
            var payload = {
                name: val(w, 'name'), reference: val(w, 'reference'),
                supplier_id: val(w, 'supplier_id') || null, cost_centre_id: val(w, 'cost_centre_id') || null,
                notice_days: val(w, 'notice_days'), notes: val(w, 'notes')
            };
            if (w.querySelector('[name=contract_id]')) payload.contract_id = val(w, 'contract_id') || null;   // GH #217
            if (w.querySelector('[name=company_id]')) payload.company_id = parseInt(val(w, 'company_id'), 10);
            if (lease) payload.id = lease.id;
            var r = await post('save.php', payload);
            toast(window.t('asset-management.toast.saved'));
            if (lease) loadLease(); else location.href = 'view.php?id=' + r.id;
        });
        // Another company: its own cost centres and contracts.
        var co = w.querySelector('[name=company_id]');
        if (co) co.addEventListener('change', async function () {
            try {
                var x = await get('options.php', { company_id: co.value });
                w.querySelector('[name=cost_centre_id]').innerHTML = opts(ccList(x.cost_centres), '', t('none'));
                var ctSel = w.querySelector('[name=contract_id]');   // GH #217: absent while Contracts is off
                if (ctSel) ctSel.innerHTML = opts(x.contracts, '', t('none'));
            } catch (e) { fail(e); }
        });
    }

    // ---- Schedule (add / edit) ----
    /**
     * Opens the Add / Edit schedule modal (dates, cost and period, currency, order number) and saves it
     * through schedule_save.php.
     */
    function scheduleForm(leaseId, s) {
        var periods = ['month', 'quarter', 'year', 'total'].map(function (p) { return { id: p, name: t('per_' + p) }; });
        var body = '<div class="ls-grid2">'
            + field(t('f_schedule_name'), '<input type="text" name="name" maxlength="200" value="' + esc(s ? s.name || '' : '') + '" placeholder="' + esc(t('f_schedule_name_ph')) + '">')
            + field(t('f_schedule_ref'), '<input type="text" name="reference" maxlength="100" value="' + esc(s ? s.reference || '' : '') + '">')
            + field(t('starts'), '<input type="date" name="start_date" value="' + esc(s ? s.start_date || '' : '') + '">')
            + field(t('ends'), '<input type="date" name="end_date" value="' + esc(s ? s.end_date || '' : '') + '">')
            + field(t('f_cost'), '<input type="text" inputmode="decimal" name="cost" value="' + esc(s && s.cost !== null ? s.cost : '') + '">')
            + field(t('f_period'), '<select name="cost_period">' + opts(periods, s ? s.cost_period : 'month') + '</select>')
            // Every currency, favourites first; a new schedule starts in the install's default (GH #189).
            + field(t('f_currency'), '<select name="currency">' + Money.optionsHtml(s ? s.currency || '' : Money.def(), { blank: '—' }) + '</select>')
            + field(t('f_order_number'), '<input type="text" name="order_number" maxlength="100" value="' + esc(s ? s.order_number || '' : '') + '">')
            + '</div>'
            + (s && s.assets && s.assets.length ? '<p class="ls-note">' + esc(t('schedule_dates_note')) + '</p>' : '')
            + field(t('f_notes'), '<textarea name="notes" rows="2">' + esc(s ? s.notes || '' : '') + '</textarea>');
        modal(s ? t('edit_schedule') : t('add_schedule'), body, async function (w) {
            var p = { name: val(w, 'name'), reference: val(w, 'reference'), start_date: val(w, 'start_date'), end_date: val(w, 'end_date'),
                      cost: val(w, 'cost'), cost_period: val(w, 'cost_period'), currency: val(w, 'currency'), order_number: val(w, 'order_number'), notes: val(w, 'notes') };
            if (s) p.id = s.id; else p.lease_id = leaseId;
            await post('schedule_save.php', p);
            toast(window.t('asset-management.toast.saved'));
            loadLease();
        });
    }

    // ---- One asset's own dates / outcome ----
    /**
     * Opens the modal for one asset on a schedule - its outcome, its own start and end dates and the
     * outcome date - and saves it through asset_update.php.
     */
    function assetForm(row, sched) {
        var outcomes = (STATE.outcomes || []).filter(function (o) { return o.is_active || o.id === row.outcome_id; });
        var body = field(t('outcome'), '<select name="outcome_id">' + opts(outcomes, row.outcome_id) + '</select>')
            + '<div class="ls-grid2">'
            + field(t('starts'), '<input type="date" name="start_date" value="' + esc(row.own_start || '') + '">', t('own_date_hint', { date: sched.start_date ? window.fmtNaiveDate(sched.start_date) : '-' }))
            + field(t('ends'), '<input type="date" name="end_date" value="' + esc(row.own_end || '') + '">', t('own_date_hint', { date: sched.end_date ? window.fmtNaiveDate(sched.end_date) : '-' }))
            + '</div>'
            + field(t('outcome_date'), '<input type="date" name="outcome_date" value="' + esc(row.outcome_date || '') + '">', t('outcome_date_hint'))
            + field(t('f_notes'), '<input type="text" name="notes" maxlength="500" value="' + esc(row.notes || '') + '">');
        modal(row.hostname || ('#' + row.asset_id), body, async function (w) {
            await post('asset_update.php', { id: row.id, outcome_id: val(w, 'outcome_id') || null, start_date: val(w, 'start_date'),
                                             end_date: val(w, 'end_date'), outcome_date: val(w, 'outcome_date'), notes: val(w, 'notes') });
            toast(window.t('asset-management.toast.saved'));
            loadLease();
        });
    }

    // ---- Put assets on a schedule ----
    /**
     * Opens the modal that searches assets and puts the ticked ones on a schedule through assets_add.php.
     * Assets already on another lease are shown but cannot be ticked; the toast reports any the server
     * refused.
     */
    function addAssetsForm(sched) {
        var picked = {};
        var body = '<div class="form-group"><input type="search" name="q" placeholder="' + esc(t('search_assets_ph')) + '" autocomplete="off"></div>'
            + '<div class="ls-pick" data-results><div class="ls-dim ls-pad">' + esc(t('search_assets_hint')) + '</div></div>'
            + '<div class="ls-dim" data-count></div>';
        var w = modal(t('add_assets_to', { name: sched.name || t('schedule') }), body, async function () {
            var ids = Object.keys(picked).map(Number);
            if (!ids.length) { toast(t('pick_some'), 'error'); return false; }
            var r = await post('assets_add.php', { schedule_id: sched.id, asset_ids: ids });
            var refused = Object.keys(r.refused || {});
            toast(t('added_n', { n: r.added }) + (refused.length ? ' ' + t('refused_n', { n: refused.length }) : ''), refused.length ? 'warning' : 'success');
            loadLease();
        }, tc('add'));
        var q = w.querySelector('[name=q]'), res = w.querySelector('[data-results]'), cnt = w.querySelector('[data-count]');
        var timer = null;
        /** Shows how many assets are ticked in the Add assets modal. */
        function count() { var n = Object.keys(picked).length; cnt.textContent = n ? t('picked_n', { n: n }) : ''; }
        q.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(async function () {
                if (q.value.trim().length < 2) { res.innerHTML = '<div class="ls-dim ls-pad">' + esc(t('search_assets_hint')) + '</div>'; return; }
                try {
                    var r = await get('asset_search.php', { lease_id: STATE.lease.id, q: q.value.trim() });
                    res.innerHTML = r.assets.length ? r.assets.map(function (a) {
                        var busy = !!a.on_lease;
                        return '<label class="ls-pick-row' + (busy ? ' busy' : '') + '"><input type="checkbox" value="' + a.id + '"' + (busy ? ' disabled' : '') + (picked[a.id] ? ' checked' : '') + '>'
                            + '<span><strong>' + esc(a.hostname || ('#' + a.id)) + '</strong> <span class="ls-dim">' + esc([a.asset_tag, a.manufacturer, a.model].filter(Boolean).join(' · ')) + '</span>'
                            + (busy ? '<br><span class="ls-badge">' + esc(t('already_on', { name: a.on_lease })) + '</span>' : '') + '</span></label>';
                    }).join('') : '<div class="ls-dim ls-pad">' + esc(t('no_assets_found')) + '</div>';
                    res.querySelectorAll('input[type=checkbox]').forEach(function (cb) {
                        cb.addEventListener('change', function () { if (cb.checked) picked[cb.value] = 1; else delete picked[cb.value]; count(); });
                    });
                } catch (e) { fail(e); }
            }, 250);
        });
    }

    // ===================================================================
    // The list page: Leases | Due back
    // ===================================================================
    var STATE = { lease: null, outcomes: [] };

    /**
     * Sets up the list page - the Leases / Due back tabs, Add, the search box and the days filter - and
     * opens the tab named in the URL hash.
     */
    function initList() {
        var tabs = document.querySelectorAll('[data-ls-tab]');
        /** Switches the list page to the Leases or Due back tab, updates the URL hash and loads that tab's data. */
        function show(which) {
            tabs.forEach(function (b) { b.classList.toggle('active', b.getAttribute('data-ls-tab') === which); });
            document.getElementById('lsListView').hidden = which !== 'leases';
            document.getElementById('lsDueView').hidden = which !== 'due';
            document.querySelectorAll('[data-ls-only]').forEach(function (el) { el.hidden = el.getAttribute('data-ls-only') !== which; });
            try { history.replaceState(null, '', which === 'due' ? '#due' : location.pathname); } catch (e) {}
            if (which === 'due') loadDue(); else loadList();
        }
        tabs.forEach(function (b) { b.addEventListener('click', function () { show(b.getAttribute('data-ls-tab')); }); });
        document.getElementById('lsAdd').addEventListener('click', function () { leaseForm(null); });
        var s = document.getElementById('lsSearch'), timer;
        s.addEventListener('input', function () { clearTimeout(timer); timer = setTimeout(loadList, 250); });
        document.getElementById('lsDays').addEventListener('change', loadDue);
        show(location.hash === '#due' ? 'due' : 'leases');
    }

    /**
     * Loads the leases matching the search box from list.php and renders the table, or the empty state
     * when there are none.
     */
    async function loadList() {
        var box = document.getElementById('lsListBody');
        try {
            var r = await get('list.php', { search: document.getElementById('lsSearch').value.trim() });
            if (!r.leases.length) {
                box.innerHTML = '<div class="ls-empty"><h3>' + esc(t('empty_title')) + '</h3><p>' + esc(t('empty_text')) + '</p></div>';
                return;
            }
            box.innerHTML = '<table class="ls-table"><thead><tr><th>' + esc(t('col_lease')) + '</th><th>' + esc(t('f_supplier')) + '</th>'
                + (CFG.multi ? '<th>' + esc(t('f_company')) + '</th>' : '')
                + '<th>' + esc(t('starts')) + '</th><th>' + esc(t('ends')) + '</th><th>' + esc(t('col_next_back')) + '</th><th class="num">' + esc(t('col_assets')) + '</th></tr></thead><tbody>'
                + r.leases.map(function (l) {
                    return '<tr class="ls-click" data-href="view.php?id=' + l.id + '"><td><a href="view.php?id=' + l.id + '"><strong>' + esc(l.name) + '</strong></a>'
                        + (l.reference ? '<div class="ls-dim">' + esc(l.reference) + '</div>' : '') + '</td>'
                        + '<td>' + esc(l.supplier_name || '') + '</td>'
                        + (CFG.multi ? '<td>' + esc(l.company_name || '') + '</td>' : '')
                        + '<td>' + d(l.start_date) + '</td><td>' + d(l.end_date) + '</td>'
                        + '<td>' + (l.active_assets ? endBadge(l.next_end) : '<span class="ls-dim">-</span>') + '</td>'
                        + '<td class="num">' + l.active_assets + (l.total_assets !== l.active_assets ? ' <span class="ls-dim">/ ' + l.total_assets + '</span>' : '') + '</td></tr>';
                }).join('') + '</tbody></table>';
            box.querySelectorAll('tr.ls-click').forEach(function (tr) {
                tr.addEventListener('click', function (e) { if (!e.target.closest('a')) location.href = tr.getAttribute('data-href'); });
            });
        } catch (e) { box.innerHTML = '<div class="ls-empty">' + esc(e.message) + '</div>'; }
    }

    /**
     * HTML table of assets due back (asset, holder, location, end, notice-by date, outcome), with a lease
     * column when asked for; overdue rows are flagged.
     */
    function dueTable(rows, withLease) {
        if (!rows.length) return '<div class="ls-empty"><p>' + esc(t('due_none')) + '</p></div>';
        return '<table class="ls-table"><thead><tr><th>' + esc(t('col_asset')) + '</th><th>' + esc(t('held_by')) + '</th><th>' + esc(t('location')) + '</th>'
            + (withLease ? '<th>' + esc(t('col_lease')) + '</th>' : '')
            + '<th>' + esc(t('ends')) + '</th><th>' + esc(t('notice_by')) + '</th><th>' + esc(t('outcome')) + '</th></tr></thead><tbody>'
            + rows.map(function (r) {
                return '<tr' + (r.overdue ? ' class="ls-overdue"' : '') + '><td><a href="' + BASE + 'asset-management/?asset_id=' + r.asset_id + '"><strong>' + esc(r.hostname || ('#' + r.asset_id)) + '</strong></a>'
                    + '<div class="ls-dim">' + esc([r.asset_tag, r.manufacturer, r.model].filter(Boolean).join(' · ')) + '</div></td>'
                    + '<td>' + esc(r.held_by || '') + '</td><td>' + esc(r.location_name || '') + '</td>'
                    + (withLease ? '<td><a href="view.php?id=' + r.lease_id + '">' + esc(r.lease_name) + '</a>' + (r.schedule_name ? '<div class="ls-dim">' + esc(r.schedule_name) + '</div>' : '') + '</td>' : '')
                    + '<td>' + endBadge(r.end_date) + (r.own_end ? ' <span class="ls-dim" title="' + esc(t('own_end_title')) + '">*</span>' : '') + '</td>'
                    + '<td>' + d(r.notice_date) + '</td><td>' + esc(r.outcome_name || t('on_lease')) + '</td></tr>';
            }).join('') + '</tbody></table>';
    }

    /**
     * Loads the assets due back within the chosen number of days from due_back.php, renders them and
     * points the Export link at the CSV of the same list.
     */
    async function loadDue() {
        var box = document.getElementById('lsDueBody');
        var days = document.getElementById('lsDays').value;
        document.getElementById('lsExport').href = API + 'due_back.php?format=csv&days=' + encodeURIComponent(days);
        try {
            var r = await get('due_back.php', { days: days });
            document.getElementById('lsDueCount').textContent = t('due_count', { n: r.assets.length });
            box.innerHTML = dueTable(r.assets, true);
        } catch (e) { box.innerHTML = '<div class="ls-empty">' + esc(e.message) + '</div>'; }
    }

    // ===================================================================
    // One lease
    // ===================================================================
    async function loadLease() {
        var box = document.getElementById('lsLease');
        try {
            var r = await get('get.php', { id: CFG.leaseId });
            if (!STATE.outcomes.length) STATE.outcomes = (await get('options.php', { company_id: r.lease.company_id })).outcomes;
            STATE.lease = r.lease;
            renderLease(r.lease);
        } catch (e) { box.innerHTML = '<div class="ls-card"><div class="ls-empty">' + esc(e.message) + '</div></div>'; }
    }

    /**
     * Renders one lease page - the header card with its facts, then a card per schedule with its assets,
     * outcomes and actions - and wires up its buttons.
     */
    function renderLease(l) {
        var all = [];
        l.schedules.forEach(function (s) { all = all.concat(s.assets); });
        l.has_assets = all.length > 0;
        var out = all.filter(function (a) { return a.active; });
        document.title = document.title.replace(/ - [^-]*$/, '') + ' - ' + l.name;
        var facts = [
            [t('f_reference'), esc(l.reference || '')],
            [t('f_supplier'), esc(l.supplier_name || '')],
            CFG.multi ? [t('f_company'), esc(l.company_name)] : null,
            [t('f_cost_centre'), esc(l.cost_centre_label || '')],
            CFG.contracts !== false ? [t('f_contract'), l.contract_id ? '<a href="' + BASE + 'contracts/view.php?id=' + l.contract_id + '">' + esc(l.contract_label) + '</a>' : ''] : null,   // GH #217
            [t('f_notice_days'), l.notice_days !== null ? esc(t('days_n', { n: l.notice_days })) : '']
        ].filter(Boolean);
        var html = '<div class="ls-card ls-hero"><div class="ls-hero-top"><div><h1>' + esc(l.name) + '</h1>'
            + '<div class="ls-dim">' + esc(t('hero_counts', { out: out.length, total: all.length })) + '</div></div>'
            + '<div class="ls-actions"><button class="btn btn-secondary" data-act="edit">' + esc(tc('edit')) + '</button>'
            + '<button class="btn btn-secondary ls-danger" data-act="delete">' + esc(tc('delete')) + '</button></div></div>'
            + '<dl class="ls-facts">' + facts.map(function (f) { return '<div><dt>' + esc(f[0]) + '</dt><dd>' + (f[1] || '<span class="ls-dim">-</span>') + '</dd></div>'; }).join('') + '</dl>'
            + (l.notes ? '<div class="ls-notes">' + esc(l.notes).replace(/\n/g, '<br>') + '</div>' : '') + '</div>';

        l.schedules.forEach(function (s) {
            var cost = s.cost !== null ? money(s.cost, s.currency) + (s.cost_period !== 'total' ? ' / ' + esc(t('per_' + s.cost_period)) : ' ' + esc(t('per_total_suffix'))) : '';
            var meta = [
                (s.start_date || s.end_date) ? d(s.start_date) + ' &rarr; ' + d(s.end_date) : '',
                s.notice_date ? esc(t('notice_by')) + ' ' + d(s.notice_date) : '',
                cost,
                s.order_number ? esc(t('f_order_number')) + ' ' + esc(s.order_number) : '',
                s.reference ? esc(s.reference) : ''
            ].filter(Boolean).join(' <span class="ls-sep">·</span> ');
            html += '<div class="ls-card ls-sched" data-sid="' + s.id + '"><div class="ls-sched-head"><div><h2>' + esc(s.name || t('schedule')) + '</h2>'
                + '<div class="ls-meta">' + (meta || '<span class="ls-dim">' + esc(t('no_dates')) + '</span>') + '</div></div>'
                + '<div class="ls-actions"><button class="btn btn-primary" data-act="add-assets">' + esc(tc('add')) + '</button>'
                + '<button class="btn btn-secondary" data-act="edit-schedule">' + esc(tc('edit')) + '</button>'
                + (l.schedules.length > 1 ? '<button class="btn btn-secondary" data-act="delete-schedule">' + esc(tc('delete')) + '</button>' : '') + '</div></div>';
            if (!s.assets.length) {
                html += '<div class="ls-empty small">' + esc(t('schedule_empty')) + '</div>';
            } else {
                html += '<table class="ls-table"><thead><tr><th>' + esc(t('col_asset')) + '</th><th>' + esc(t('held_by')) + '</th><th>' + esc(t('starts')) + '</th><th>' + esc(t('ends')) + '</th><th>' + esc(t('outcome')) + '</th><th></th></tr></thead><tbody>'
                    + s.assets.map(function (a) {
                        var outs = STATE.outcomes.filter(function (o) { return o.is_active || o.id === a.outcome_id; });
                        return '<tr data-rid="' + a.id + '"' + (a.active ? '' : ' class="ls-closed"') + '><td><a href="' + BASE + 'asset-management/?asset_id=' + a.asset_id + '"><strong>' + esc(a.hostname || ('#' + a.asset_id)) + '</strong></a>'
                            + '<div class="ls-dim">' + esc([a.asset_tag, a.manufacturer, a.model].filter(Boolean).join(' · ')) + '</div></td>'
                            + '<td>' + esc(a.held_by || '') + '</td>'
                            + '<td>' + d(a.start_date) + (a.own_start ? ' <span class="ls-badge" title="' + esc(t('own_date_title')) + '">' + esc(t('own')) + '</span>' : '') + '</td>'
                            + '<td>' + (a.active ? endBadge(a.end_date) : d(a.end_date)) + (a.own_end ? ' <span class="ls-badge" title="' + esc(t('own_date_title')) + '">' + esc(t('own')) + '</span>' : '') + '</td>'
                            + '<td><select class="ls-outcome" data-act="outcome">' + opts(outs, a.outcome_id, a.outcome_id ? undefined : t('on_lease')) + '</select>'
                            + (a.outcome_date ? '<div class="ls-dim">' + d(a.outcome_date) + '</div>' : '') + '</td>'
                            + '<td class="ls-row-actions"><button class="action-btn" data-act="asset-edit" title="' + esc(tc('edit')) + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>'
                            + '<button class="action-btn delete" data-act="asset-remove" title="' + esc(t('remove_title')) + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></td></tr>';
                    }).join('') + '</tbody></table>';
            }
            html += '</div>';
        });
        html += '<div class="ls-add-sched"><button class="btn btn-secondary" data-act="add-schedule">' + esc(t('add_schedule')) + '</button><span class="ls-dim">' + esc(t('add_schedule_hint')) + '</span></div>';
        var box = document.getElementById('lsLease');
        box.innerHTML = html;
        wireLease(box, l);
    }

    /**
     * Wires the buttons and outcome drop-downs on a lease page (edit / delete lease, add / edit / delete
     * schedule, add / edit / remove assets, change outcome), all through one click and one change
     * listener.
     */
    function wireLease(box, l) {
        /** The schedule whose card holds the clicked element. */
        var sched = function (el) { var c = el.closest('[data-sid]'); return c ? l.schedules.find(function (s) { return s.id === +c.getAttribute('data-sid'); }) : null; };
        /** The asset row of the schedule that holds the clicked element. */
        var row = function (el, s) { var tr = el.closest('[data-rid]'); return tr ? s.assets.find(function (a) { return a.id === +tr.getAttribute('data-rid'); }) : null; };
        box.addEventListener('click', async function (e) {
            var b = e.target.closest('[data-act]');
            if (!b || b.tagName === 'SELECT') return;
            var act = b.getAttribute('data-act'), s = sched(b);
            try {
                if (act === 'edit') leaseForm(l);
                else if (act === 'delete') {
                    if (!(await window.showConfirm({ title: tc('delete'), message: t('delete_confirm', { name: l.name }), okLabel: tc('delete'), okClass: 'danger' }))) return;
                    await post('delete.php', { id: l.id });
                    location.href = './';
                }
                else if (act === 'add-schedule') scheduleForm(l.id, null);
                else if (act === 'edit-schedule') scheduleForm(l.id, s);
                else if (act === 'delete-schedule') {
                    if (!(await window.showConfirm({ title: tc('delete'), message: t('delete_schedule_confirm', { name: s.name || t('schedule'), n: s.assets.length }), okLabel: tc('delete'), okClass: 'danger' }))) return;
                    await post('schedule_delete.php', { id: s.id });
                    loadLease();
                }
                else if (act === 'add-assets') addAssetsForm(s);
                else if (act === 'asset-edit') assetForm(row(b, s), s);
                else if (act === 'asset-remove') {
                    var a = row(b, s);
                    if (!(await window.showConfirm({ title: t('remove_title'), message: t('remove_confirm', { name: a.hostname || ('#' + a.asset_id) }), okLabel: t('remove'), okClass: 'danger' }))) return;
                    await post('asset_remove.php', { id: a.id });
                    loadLease();
                }
            } catch (err) { fail(err); }
        });
        box.addEventListener('change', async function (e) {
            if (!e.target.matches('select[data-act=outcome]')) return;
            var s = sched(e.target), a = row(e.target, s);
            try {
                await post('asset_update.php', { id: a.id, outcome_id: e.target.value || null });
                toast(window.t('asset-management.toast.saved'));
                loadLease();
            } catch (err) { fail(err); loadLease(); }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (CFG.page === 'list') initList();
        else if (CFG.page === 'lease') loadLease();
    });
})();
