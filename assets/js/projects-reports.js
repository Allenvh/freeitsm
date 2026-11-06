/**
 * Projects - the Overview's briefing and the Reports tab: the AI project
 * manager (3.2.0). Rules: includes/services/project_reports.php; the AI:
 * includes/projects/ai.php.
 *
 * The AI only PROPOSES. A briefing is marked as the AI's; a report is a draft
 * until someone who may approve it does, and an approved report is final.
 * Report text is a small Markdown subset (## heading, - bullet, **bold**),
 * drawn by md() below, which escapes everything first.
 *
 * @wiki Projects-Internals-7-Alerts-Reports-and-AI
 */
(function () {
    'use strict';
    const P = window.Prj;
    /** Translates a key under projects.reports.*. */
    const T = (k, p) => P.T('reports.' + k, p);
    const esc = P.esc;
    const KINDS = ['highlight', 'exception', 'checkpoint', 'closure'];   // closure: 3.3.0
    let ctx = null;
    let state = null;          // the GET from reports.php, fetched once per project
    let loading = false;
    let busy = false;          // a provider call in flight
    let wired = false;
    let editing = null;        // the report in the modal, or {kind} for a new one

    /** Posts a reports action to reports.php for the current project. */
    const call = body => P.api('reports.php', Object.assign({ project_id: ctx.projectId }, body));
    /** A date-time formatted for the viewer, or the raw value when the formatter is not loaded. */
    const when = s => (s && window.fmtDateTime ? window.fmtDateTime(s) : (s || ''));

    /** Escape, then the three marks the AI is told to use. Nothing else becomes markup. */
    function md(text) {
        /** Escapes one line and turns **bold** into strong text. */
        const inline = s => esc(s).replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
        let html = '';
        let list = false;
        let para = [];
        /** Writes the paragraph lines collected so far as one paragraph. */
        const flush = () => { if (para.length) { html += '<p>' + para.map(inline).join('<br>') + '</p>'; para = []; } };
        /** Closes the bullet list if one is open. */
        const close = () => { if (list) { html += '</ul>'; list = false; } };
        String(text || '').split(/\r?\n/).forEach(raw => {
            const line = raw.trim();
            if (!line) { flush(); close(); return; }
            const h = line.match(/^#{1,4}\s+(.*)$/);
            const b = line.match(/^[-*]\s+(.*)$/);
            if (h) { flush(); close(); html += '<h4>' + inline(h[1]) + '</h4>'; }
            else if (b) { flush(); if (!list) { html += '<ul>'; list = true; } html += '<li>' + inline(b[1]) + '</li>'; }
            else { close(); para.push(line); }
        });
        flush(); close();
        return html;
    }

    /** Shows a not-configured or unreachable toast when the reply carries an AI error; returns true when it did. */
    function aiError(r) {
        if (!r || !r.ai_error) return false;
        P.toast(r.ai_error === 'not_configured' ? T('not_configured') : T('unreachable', { detail: r.detail || '' }), 'error');
        return true;
    }

    /** Loads the project's briefing, reports, schedule and permissions from reports.php once, then draws. */
    async function fetchState() {
        if (loading) return;
        loading = true;
        try { state = await P.api('reports.php?project_id=' + ctx.projectId); }
        catch (e) { state = null; }
        loading = false;
        draw();
    }

    // ---- The Overview's briefing ------------------------------------------------
    /** Draws the Overview's AI briefing panel - the briefing and who asked for it, a Brief me / Refresh or set-up button, or a wait spinner; hidden when there is nothing to show. */
    function drawBriefing() {
        const box = document.getElementById('pvBriefing');
        if (!box) return;
        if (!state || !state.ready || (!state.ai_ready && !state.briefing && !state.can_set_up)) { box.hidden = true; return; }
        box.hidden = false;
        const b = state.briefing;
        let html = '<div class="prj-panel prj-brief"><div class="prj-panel-head"><h3>' + P.icon('sparkles', 16) + ' ' + esc(T('briefing')) + '</h3>';
        if (state.ai_ready) html += '<button type="button" class="btn btn-secondary sm" data-brief' + (busy ? ' disabled' : '') + '>' + esc(b ? T('refresh') : T('brief_me')) + '</button>';
        else if (state.can_set_up) html += '<a class="btn btn-secondary sm" href="' + esc(window.PRJ_BASE + 'projects/settings/?tab=ai') + '">' + esc(T('setup')) + '</a>';
        html += '</div>';
        if (busy === 'briefing') html += '<p class="prj-muted prj-brief-wait"><span class="prj-spin"></span>' + esc(T('thinking')) + '</p>';
        else if (b) html += '<div class="prj-md">' + md(b.body) + '</div><p class="prj-brief-foot">' + esc(T('briefing_when', { when: when(b.created_at), name: b.created_by || '-' })) + '</p>';
        else html += '<p class="prj-muted">' + esc(state.ai_ready ? T('briefing_none') : T('briefing_off')) + '</p>';
        box.innerHTML = html + '</div>';
    }

    /** Asks the AI for a fresh briefing and redraws the panel. */
    async function brief() {
        if (busy) return;
        busy = 'briefing'; drawBriefing();
        try {
            const r = await call({ action: 'briefing', refresh: true });
            if (!aiError(r)) state.briefing = r.briefing;
        } catch (e) { P.toast(e.message, 'error'); }
        busy = false; drawBriefing();
    }

    // ---- The Reports tab --------------------------------------------------------
    /** A report's badges - kind, status, and AI-drafted or AI-drafted-then-edited. */
    function badges(r) {
        let s = '<span class="prj-rep-kind k-' + esc(r.kind) + '">' + esc(T('kind_' + r.kind)) + '</span>'
            + '<span class="prj-rep-status s-' + esc(r.status) + '">' + esc(T('status_' + r.status)) + '</span>';
        if (r.ai_drafted) s += '<span class="prj-rep-ai">' + P.icon('sparkles', 12) + esc(r.ai_edited ? T('ai_edited') : T('ai_badge')) + '</span>';
        return s;
    }

    /** Draws the Reports tab - the new report picker (kind, period, Draft with AI, Write), the scheduled drafts setting and the list of reports. */
    function drawReports() {
        const box = document.getElementById('pvReports');
        if (!box) return;
        if (!state) { box.innerHTML = ''; return; }
        if (!state.ready) { box.innerHTML = '<div class="prj-plan-empty">' + esc(T('not_ready')) + '</div>'; return; }
        let html = '<p class="prj-muted" style="margin-top:0">' + esc(T('intro')) + '</p>';
        if (state.can_write) {
            html += '<div class="prj-panel prj-rep-new"><div class="prj-rep-kinds">' + KINDS.map((k, i) =>
                '<label class="prj-rep-pick"><input type="radio" name="prjRepKind" value="' + k + '"' + (i === 0 ? ' checked' : '') + '>'
                + '<span><strong>' + esc(T('kind_' + k)) + '</strong><small>' + esc(T('kind_' + k + '_d')) + '</small></span></label>').join('') + '</div>'
                + '<div class="prj-rep-go"><label>' + esc(T('period')) + ' <select id="prjRepDays">' + [7, 14, 28].map(n => '<option value="' + n + '"' + (n === 14 ? ' selected' : '') + '>' + esc(T('days', { n: n })) + '</option>').join('') + '</select></label>'
                + (state.ai_ready ? '<button type="button" class="btn btn-primary prj-btn" data-rep-draft' + (busy ? ' disabled' : '') + '>' + P.icon('sparkles', 14) + ' ' + esc(T('draft')) + '</button>' : '')
                + '<button type="button" class="btn btn-secondary" data-rep-write>' + esc(T('write')) + '</button></div>'
                + (busy === 'draft' ? '<p class="prj-muted prj-brief-wait"><span class="prj-spin"></span>' + esc(T('thinking')) + '</p>' : '')
                + (!state.ai_ready ? '<p class="prj-muted" style="margin:10px 0 0">' + esc(T('briefing_off')) + '</p>' : '')
                + '</div>';
        }
        // Scheduled drafts (3.3.0): one each week / fortnight / month while the project is active.
        if (state.schedule && state.can_write) {
            const s = state.schedule;
            html += '<div class="prj-panel prj-rep-sched"><label>' + esc(T('schedule_label')) + ' <select data-rep-sched>'
                + ['off', 'weekly', 'fortnightly', 'monthly'].map(v => '<option value="' + v + '"' + (s.schedule === v ? ' selected' : '') + '>' + esc(T('schedule_' + v)) + '</option>').join('') + '</select></label>'
                + '<label' + (s.schedule === 'off' ? ' hidden' : '') + '>' + esc(T('schedule_kind')) + ' <select data-rep-sched-kind>'
                + ['highlight', 'checkpoint', 'exception'].map(k => '<option value="' + k + '"' + (s.kind === k ? ' selected' : '') + '>' + esc(T('kind_' + k)) + '</option>').join('') + '</select></label>'
                + '<small class="prj-muted">' + esc(T(s.schedule === 'off' ? 'schedule_hint_off' : (state.ai_ready ? 'schedule_hint_ai' : 'schedule_hint_facts'))) + '</small></div>';
        }
        if (!state.reports.length) html += '<div class="prj-plan-empty">' + esc(T('none')) + '</div>';
        else html += '<div class="prj-rep-list">' + state.reports.map(r => '<button type="button" class="prj-rep-row" data-rep-open="' + r.id + '">'
            + '<span class="prj-rep-badges">' + badges(r) + '</span><span class="prj-rep-title">' + esc(r.title) + '</span>'
            + '<span class="prj-rep-meta">' + esc(r.status === 'approved' ? T('approved_by', { name: r.approved_by || '-', when: when(r.approved_at) }) : T('by', { name: r.updated_by || r.created_by || T('scheduled_by'), when: when(r.updated_at) }))
            + (r.sent_at ? ' · ' + esc(T('sent_meta', { count: r.sent_count, when: when(r.sent_at) })) : '') + '</span>'
            + '</button>').join('') + '</div>';
        box.innerHTML = html;
    }

    /** Draws both the briefing and the Reports tab. */
    function draw() { drawBriefing(); drawReports(); }

    /** Asks the AI to draft a report of the chosen kind over the chosen period and opens the draft. */
    async function draftWithAi() {
        if (busy) return;
        const kind = (document.querySelector('input[name="prjRepKind"]:checked') || {}).value || 'highlight';
        const days = Number(document.getElementById('prjRepDays').value) || 14;
        busy = 'draft'; drawReports();
        try {
            const r = await call({ action: 'draft', kind: kind, days: days });
            if (!aiError(r)) { state.reports = r.reports; busy = false; drawReports(); openReport(r.id); return; }
        } catch (e) { P.toast(e.message, 'error'); }
        busy = false; drawReports();
    }

    // ---- The report dialog ------------------------------------------------------
    /** Switches the report dialog between editing the Markdown and the rendered preview. */
    function setMode(mode) {
        const m = document.getElementById('prjReportModal');
        m.querySelectorAll('[data-rep-mode]').forEach(b => b.classList.toggle('active', b.dataset.repMode === mode));
        document.getElementById('rpEditWrap').hidden = mode !== 'edit';
        const pv = document.getElementById('rpPreview');
        pv.hidden = mode !== 'preview';
        if (mode === 'preview') pv.innerHTML = md(document.getElementById('rpBody').value);
    }

    /** Opens the report dialog for a report, or a new one of the chosen kind; an approved report is read-only and the buttons follow the viewer's rights. */
    function openReport(id) {
        const r = id ? state.reports.find(x => x.id === Number(id)) : null;
        if (id && !r) return;
        editing = r || { id: 0, kind: (document.querySelector('input[name="prjRepKind"]:checked') || {}).value || 'highlight', status: 'draft', title: '', body: '' };
        const final = editing.status === 'approved';
        const mayEdit = !final && state.can_write;
        if (!r) editing.title = T('kind_' + editing.kind) + ' - ' + new Date().toLocaleDateString();
        document.getElementById('rpHead').innerHTML = r ? badges(r) : esc(T('new_title', { kind: T('kind_' + editing.kind).toLowerCase() }));
        document.getElementById('rpTitle').value = editing.title;
        document.getElementById('rpTitle').readOnly = !mayEdit;
        document.getElementById('rpBody').value = editing.body;
        document.getElementById('rpTabs').hidden = !mayEdit;
        document.getElementById('rpAiNote').hidden = !(r && r.ai_drafted && !final);
        document.getElementById('rpMeta').textContent = r ? [T('by', { name: r.created_by || '-', when: when(r.created_at) }),
            r.approved_by ? T('approved_by', { name: r.approved_by, when: when(r.approved_at) }) : ''].filter(Boolean).join(' · ') : '';
        document.getElementById('rpSave').hidden = !mayEdit;
        document.getElementById('rpApprove').hidden = final || !state.can_approve;
        document.getElementById('rpCopy').hidden = !r;
        document.getElementById('rpSend').hidden = !(final && state.can_approve && state.schedule);
        document.getElementById('rpDelete').hidden = !r || (final ? !state.can_approve : !state.can_write);
        document.getElementById('rpError').hidden = true;
        setMode(mayEdit && !(r && r.body) ? 'edit' : 'preview');
        P.openModal('prjReportModal');
    }

    /** Shows an error in the report dialog. */
    function fail(e) { const el = document.getElementById('rpError'); el.textContent = e.message; el.hidden = false; }

    /** Saves the report in the dialog and returns its id; unless quiet, closes the dialog and redraws the list. */
    async function save(quiet) {
        const body = { action: 'save', title: document.getElementById('rpTitle').value, body: document.getElementById('rpBody').value };
        if (editing.id) body.id = editing.id; else body.kind = editing.kind;
        const r = await call(body);
        state.reports = r.reports;
        editing = state.reports.find(x => x.id === Number(r.id)) || editing;
        if (!quiet) { P.closeModal('prjReportModal'); P.toast(T('saved')); drawReports(); }
        return r.id;
    }

    /** Asks, saves any unsaved edit, then approves the report - which makes it final - and redraws. */
    async function approve() {
        const ok = await window.showConfirm({ title: T('approve_title'), message: T('approve_body'), okLabel: T('approve') });
        if (!ok) return;
        try {
            // Approve what is on the screen: an unsaved edit is saved first.
            if (!document.getElementById('rpSave').hidden) await save(true);
            const r = await call({ action: 'approve', id: editing.id });
            state.reports = r.reports;
            P.closeModal('prjReportModal'); P.toast(T('approved_toast')); drawReports();
            if (ctx.refresh) ctx.refresh();   // the history line
        } catch (e) { fail(e); }
    }

    /** Asks, then deletes the report open in the dialog. */
    async function remove() {
        const ok = await window.showConfirm({ title: T('delete_title'), message: T('delete_body'), okLabel: P.TC('delete'), okClass: 'danger' });
        if (!ok) return;
        try { const r = await call({ action: 'delete', id: editing.id }); state.reports = r.reports; P.closeModal('prjReportModal'); drawReports(); }
        catch (e) { fail(e); }
    }

    // ---- Send an approved report (3.3.0) ------------------------------------------------
    /** Opens the send dialog for an approved report, listing the people it can go to with tick boxes, plus a box for another address. */
    function openSend() {
        const list = state.recipients || [];
        document.getElementById('rsWhat').textContent = T('send_what', { title: editing.title });
        document.getElementById('rsList').innerHTML = list.length
            ? list.map((p, i) => '<label class="prj-check prj-send-row"><input type="checkbox" data-rs-email="' + esc(p.email) + '"> <span><strong>' + esc(p.name) + '</strong> <small class="prj-muted">'
                + esc(p.email) + (p.why === 'owner' ? ' · ' + esc(T('send_owner')) : (p.why ? ' · ' + esc(p.why) : '')) + '</small></span></label>').join('')
            : '<p class="prj-muted">' + esc(T('send_nobody')) + '</p>';
        document.getElementById('rsOther').value = '';
        document.getElementById('rsNote').value = '';
        document.getElementById('rsError').hidden = true;
        P.openModal('prjSendModal');
    }

    /** Emails the approved report to the ticked people and any other address, saying which failed. */
    async function send() {
        const emails = [...document.querySelectorAll('#rsList [data-rs-email]:checked')].map(c => c.dataset.rsEmail);
        const other = document.getElementById('rsOther').value.trim();
        if (other) emails.push(other);
        const err = document.getElementById('rsError');
        if (!emails.length) { err.textContent = T('send_choose'); err.hidden = false; return; }
        const btn = document.getElementById('rsSend');
        btn.disabled = true;
        try {
            const r = await call({ action: 'send', id: editing.id, emails: emails, note: document.getElementById('rsNote').value });
            state.reports = r.reports;
            if (r.failed && r.failed.length) { err.textContent = T('send_failed', { list: r.failed.join(', ') }); err.hidden = false; }
            else { P.closeModal('prjSendModal'); P.closeModal('prjReportModal'); }
            if (r.sent && r.sent.length) P.toast(T('send_done', { count: r.sent.length }));
            drawReports();
            if (ctx.refresh) ctx.refresh();
        } catch (e) { err.textContent = e.message; err.hidden = false; }
        btn.disabled = false;
    }

    /** Saves how often a report is drafted automatically, and of which kind. */
    async function setSchedule() {
        const sel = document.querySelector('#pvReports [data-rep-sched]');
        const kind = document.querySelector('#pvReports [data-rep-sched-kind]');
        try {
            const r = await call({ action: 'schedule', schedule: sel.value, kind: kind ? kind.value : 'highlight' });
            state.schedule = r.schedule;
            P.toast(T('schedule_saved'));
            drawReports();
        } catch (e) { P.toast(e.message, 'error'); drawReports(); }
    }

    /** Copies the report's title and text to the clipboard. */
    async function copy() {
        const text = document.getElementById('rpTitle').value + '\n\n' + document.getElementById('rpBody').value;
        const ok = window.copyToClipboard ? await window.copyToClipboard(text) : false;
        P.toast(ok ? T('copied') : P.TC('failed'), ok ? undefined : 'error');
    }

    /** Wires the briefing and Reports tab buttons and the report and send dialogs, once. */
    function wire() {
        if (wired) return;
        wired = true;
        document.addEventListener('click', e => {
            if (e.target.closest('#pvBriefing [data-brief]')) { brief(); return; }
            if (e.target.closest('#pvReports [data-rep-draft]')) { draftWithAi(); return; }
            if (e.target.closest('#pvReports [data-rep-write]')) { openReport(0); return; }
            const o = e.target.closest('#pvReports [data-rep-open]');
            if (o) { openReport(o.dataset.repOpen); return; }
            const m = e.target.closest('#prjReportModal [data-rep-mode]');
            if (m) setMode(m.dataset.repMode);
        });
        document.getElementById('rpSave').addEventListener('click', async () => { try { await save(false); } catch (e) { fail(e); } });
        document.getElementById('rpApprove').addEventListener('click', approve);
        document.getElementById('rpDelete').addEventListener('click', remove);
        document.getElementById('rpCopy').addEventListener('click', copy);
        document.getElementById('rpSend').addEventListener('click', openSend);
        document.getElementById('rsSend').addEventListener('click', send);
        document.addEventListener('change', e => { if (e.target.closest('#pvReports [data-rep-sched], #pvReports [data-rep-sched-kind]')) setSchedule(); });
    }

    window.PrjReports = {
        md: md,
        /** Called by projects-view.js after every redraw: the Overview's slot is new each time. */
        render(c) {
            const first = !ctx || ctx.projectId !== c.projectId;
            ctx = c;
            wire();
            if (first) fetchState(); else draw();
        },
    };
})();
