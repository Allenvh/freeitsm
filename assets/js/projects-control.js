/**
 * Projects - the Change control tab (3.3.0). Rules: includes/projects/control.php.
 *
 * Top: the agreed plan (a baseline) against the plan as it is now, headline
 * figures then what moved item by item. Pick an older baseline to compare
 * with that one instead. Below: change requests - raise, edit or withdraw one
 * still waiting, approve or reject (the server says who may: a setting).
 * The variance is worked out on the server; this file only draws it.
 *
 * @wiki Projects-Internals-6-Governance
 */
(function () {
    'use strict';
    const P = window.Prj;
    /** Translates a key under projects.control.*. */
    const T = (k, p) => P.T('control.' + k, p);
    const esc = P.esc;
    let ctx = null;
    let wired = false;
    let pick = null;        // the baseline id being compared; null = the latest
    let deciding = null;    // {id, decision} while the decide box is open

    /** Posts a change-control action to tools.php for the current project. */
    const call = body => P.api('tools.php', Object.assign({ project_id: ctx.projectId }, body));
    /** True when the viewer may change this project. */
    const canChange = () => !!(ctx.data.permissions && ctx.data.permissions.can_change);
    /** The project's currency - from the budget, else the plan as it stands now, else GBP. */
    const cur = () => (ctx.data.budget && ctx.data.budget.currency) || (ctx.data.control.now && ctx.data.control.now.currency) || (window.Money ? Money.def() : 'GBP');

    /** An amount formatted in the project's currency in the viewer's format (GH #189), or "-" when empty. */
    function money(v) {
        if (v === null || v === undefined || v === '') return '-';
        if (window.Money) return window.Money.format(v, cur());
        try { return new Intl.NumberFormat(undefined, { style: 'currency', currency: cur() }).format(v); }
        catch (e) { return cur() + ' ' + Number(v).toFixed(2); }
    }
    /** A difference with its sign in front (+ or -) and the size formatted by fmt. */
    const signed = (n, fmt) => (n > 0 ? '+' : n < 0 ? '-' : '') + fmt(Math.abs(n));
    /** A number of days in words, singular or plural. */
    const days = n => T(Math.abs(n) === 1 ? 'day' : 'days', { count: Math.abs(n) });
    /** Hours to one decimal place, such as "12.5 h". */
    const hrs = n => (Math.round(n * 10) / 10).toLocaleString() + ' h';
    /** A date formatted for the viewer, or "-" when there is none. */
    const date = d => d ? P.fmtDate(d) : '-';

    /** Why a baseline was taken, in words - a stage, an approved change request, the project starting, or by hand (with its label). */
    function reasonText(b) {
        if (b.reason === 'stage') return T('reason_stage', { name: b.stage_name || '?' });
        if (b.reason === 'change') return T('reason_change', { ref: 'CR-' + (b.change_number || '?') });
        if (b.reason === 'start') return T('reason_start');
        return b.label ? T('reason_manual_label', { label: b.label }) : T('reason_manual');
    }
    /** A baseline's name - "Baseline n" plus its label when it has one. */
    function baselineName(b) { return T('baseline_n', { n: b.number }) + (b.label ? ' - ' + b.label : ''); }

    // ---- The comparison --------------------------------------------------------------
    /** The baseline panel - pick a baseline, the headline table (start, finish, budget, tasks, hours, must-haves) baseline against now, and what moved item by item. */
    function compare(c) {
        const bl = c.baselines;
        let html = '<div class="prj-panel"><div class="prj-panel-head"><h3>' + esc(T('baseline_title')) + '</h3>'
            + (canChange() ? '<button type="button" class="btn btn-secondary sm" data-ctl-baseline>' + esc(T('take')) + '</button>' : '') + '</div>';
        if (!bl.length) {
            return html + '<p class="prj-muted">' + esc(T('no_baseline')) + ' ' + esc(T('auto_' + (c.auto || 'stage'))) + '</p></div>';
        }
        const b = bl.find(x => x.id === pick) || bl[0];
        html += '<div class="prj-ctl-pick">';
        if (bl.length > 1) {
            html += '<label for="ctlPick" class="prj-muted sm">' + esc(T('compare_with')) + '</label><select id="ctlPick">'
                + bl.map(x => '<option value="' + x.id + '"' + (x.id === b.id ? ' selected' : '') + '>' + esc(baselineName(x)) + '</option>').join('') + '</select>';
        } else html += '<strong>' + esc(baselineName(b)) + '</strong>';
        html += '<span class="prj-muted sm">' + esc(T('taken', { date: P.fmtDate(String(b.created_datetime).slice(0, 10)), name: b.created_by_name || T('system'), why: reasonText(b) })) + '</span></div>';

        const v = b.variance, base = b.plan, now = c.now;
        /** One row of the comparison table, the change shown in red when bad. */
        const row = (label, from, to, delta, bad) => '<tr><td>' + esc(label) + '</td><td class="num">' + esc(from) + '</td><td class="num">' + esc(to) + '</td>'
            + '<td class="num' + (bad ? ' bad' : '') + '">' + esc(delta) + '</td></tr>';
        html += '<div class="prj-table-wrap"><table class="prj-budget-table prj-ctl-table"><thead><tr><th>' + esc(T('measure')) + '</th><th class="num">' + esc(T('col_baseline')) + '</th>'
            + '<th class="num">' + esc(T('col_now')) + '</th><th class="num">' + esc(T('col_change')) + '</th></tr></thead><tbody>';
        html += row(T('start'), date(base.start_date), date(now.start_date), v.start_days ? signed(v.start_days, days) : '-', false);
        html += row(T('finish'), date(base.target_end_date), date(now.target_end_date), v.finish_days ? signed(v.finish_days, days) : '-', v.finish_days > 0);
        html += row(T('budget'), money(base.budget_planned), money(now.budget_planned),
            v.budget ? signed(v.budget, money) + (v.budget_pct !== null ? ' (' + signed(v.budget_pct, n => n + '%') + ')' : '') : '-', v.budget > 0);
        html += row(T('tasks'), String(base.task_count), String(now.task_count), v.tasks ? signed(v.tasks, String) : '-', false);
        html += row(T('hours'), base.estimate_hours !== null ? hrs(base.estimate_hours) : '-', now.estimate_hours !== null ? hrs(now.estimate_hours) : '-', v.hours ? signed(v.hours, hrs) : '-', v.hours > 0);
        html += row(T('must'), String(base.must_count), String(now.must_count), v.must ? signed(v.must, String) : '-', false);
        html += '</tbody></table></div>';

        // What moved, item by item.
        const items = [];
        /** The sentence for a milestone or stage that was moved, added or removed since the baseline. */
        const dated = (kind, x) => {
            if (x.kind === 'moved') return T('moved_' + kind, { name: x.name, from: date(x.from), to: date(x.to), change: signed(x.days, days) });
            return T(x.kind + '_' + kind, { name: x.name, date: date(x.kind === 'added' ? x.to : x.from) });
        };
        v.milestones.forEach(x => items.push({ late: x.kind === 'moved' && x.days > 0, text: dated('milestone', x) }));
        v.stages.forEach(x => items.push({ late: x.kind === 'moved' && x.days > 0, text: dated('stage', x) }));
        v.must_added.forEach(t => items.push({ text: T('must_added', { name: t }) }));
        v.must_removed.forEach(t => items.push({ text: T('must_removed', { name: t }) }));
        v.lines.forEach(x => items.push({ text: x.kind === 'moved' ? T('line_moved', { name: x.title, from: money(x.from), to: money(x.to) })
            : T('line_' + x.kind, { name: x.title, amount: money(x.kind === 'added' ? x.to : x.from) }) }));
        html += '<h4 class="prj-ctl-sub">' + esc(T('what_moved')) + '</h4>';
        html += items.length ? '<ul class="prj-ctl-moved">' + items.map(i => '<li' + (i.late ? ' class="late"' : '') + '>' + esc(i.text) + '</li>').join('') + '</ul>'
            : '<p class="prj-muted sm">' + esc(T('nothing_moved')) + '</p>';
        return html + '</div>';
    }

    // ---- Change requests -------------------------------------------------------------
    /** One change request's card - reference, title, status, impact chips, description and reason, who raised and decided it, what approving did, and the actions the viewer may take. */
    function request(r) {
        const chips = [];
        if (r.impact_days) chips.push('<span class="prj-ctl-impact' + (r.impact_days > 0 ? ' up' : '') + '">' + esc(T('impact_time', { change: signed(r.impact_days, days) })) + '</span>');
        if (r.impact_cost) chips.push('<span class="prj-ctl-impact' + (r.impact_cost > 0 ? ' up' : '') + '">' + esc(T('impact_cost', { change: signed(r.impact_cost, money) })) + '</span>');
        if (!r.impact_days && !r.impact_cost && !r.impact_scope) chips.push('<span class="prj-ctl-impact">' + esc(T('impact_none')) + '</span>');
        let html = '<li class="prj-ctl-cr st-' + esc(r.status) + '">'
            + '<div class="prj-ctl-cr-head"><span class="prj-ctl-ref">CR-' + r.number + '</span><strong>' + esc(r.title) + '</strong>'
            + '<span class="prj-ctl-pill cp-' + esc(r.status) + '">' + esc(T('status_' + r.status)) + '</span></div>';
        html += '<div class="prj-ctl-impacts">' + chips.join('') + '</div>';
        if (r.impact_scope) html += '<p class="prj-ctl-scope"><span class="prj-muted">' + esc(T('impact_scope')) + ':</span> ' + esc(r.impact_scope) + '</p>';
        if (r.description) html += '<p class="prj-ctl-desc">' + esc(r.description).replace(/\n/g, '<br>') + '</p>';
        if (r.reason) html += '<p class="prj-ctl-desc"><span class="prj-muted">' + esc(T('why')) + ':</span> ' + esc(r.reason).replace(/\n/g, '<br>') + '</p>';
        const meta = [T('raised', { name: r.raised_by_name || T('system'), date: P.fmtDate(String(r.raised_datetime).slice(0, 10)) })];
        if (r.decided_datetime) meta.push(T('decided_' + r.status, { name: r.decided_by_name || T('system'), date: P.fmtDate(String(r.decided_datetime).slice(0, 10)) }));
        html += '<div class="prj-muted sm">' + esc(meta.join(' - ')) + '</div>';
        if (r.decision_notes) html += '<p class="prj-ctl-desc"><span class="prj-muted">' + esc(T('notes')) + ':</span> ' + esc(r.decision_notes) + '</p>';
        if (r.status === 'approved') {
            const did = [];
            if (r.applied && r.applied.target_to) did.push(T('did_target', { from: date(r.applied.target_from), to: date(r.applied.target_to) }));
            if (r.applied && r.applied.budget_line_id) did.push(T('did_budget', { amount: money(r.applied.budget_amount) }));
            if (r.baseline_number) did.push(T('did_baseline', { n: r.baseline_number }));
            if (did.length) html += '<p class="prj-ctl-did">' + esc(did.join('; ')) + '</p>';
        }
        const acts = [];
        if (r.can_decide) {
            acts.push('<button type="button" class="btn btn-primary prj-btn sm" data-ctl-decide="approved" data-id="' + r.id + '">' + esc(T('approve')) + '</button>');
            acts.push('<button type="button" class="btn btn-secondary sm" data-ctl-decide="rejected" data-id="' + r.id + '">' + esc(T('reject')) + '</button>');
        }
        if (r.can_edit) {
            acts.push('<button type="button" class="btn btn-secondary sm" data-ctl-edit="' + r.id + '">' + esc(P.TC('edit')) + '</button>');
            acts.push('<button type="button" class="btn btn-secondary sm" data-ctl-withdraw="' + r.id + '">' + esc(T('withdraw')) + '</button>');
        }
        if (acts.length) html += '<div class="prj-ctl-acts">' + acts.join('') + '</div>';
        return html + '</li>';
    }

    /** Draws the Change control tab - the baseline comparison and the change requests with who may approve and what approving does. */
    function render() {
        const box = document.getElementById('pvControl');
        if (!box) return;
        const c = ctx.data.control;
        if (!c) { box.innerHTML = '<div class="prj-plan-empty">' + esc(T('not_ready')) + '</div>'; return; }
        let html = '<p class="prj-muted" style="margin-top:0">' + esc(T('intro')) + '</p>';
        html += compare(c);
        const waiting = c.requests.filter(r => r.status === 'proposed').length;
        html += '<div class="prj-panel" style="margin-top:16px"><div class="prj-panel-head"><h3>' + esc(T('requests')) + (waiting ? ' <span class="prj-ctl-count">' + esc(T('waiting', { count: waiting })) + '</span>' : '') + '</h3>'
            + (canChange() ? '<button type="button" class="btn btn-primary prj-btn sm" data-ctl-raise>' + esc(T('raise')) + '</button>' : '') + '</div>';
        html += '<p class="prj-muted sm">' + esc(T('who_' + (c.approver || 'owner'))) + ' ' + esc(T('apply_' + (c.apply || 'plan'))) + '</p>';
        html += c.requests.length ? '<ul class="prj-ctl-list">' + c.requests.map(request).join('') + '</ul>' : '<p class="prj-muted">' + esc(T('no_requests')) + '</p>';
        box.innerHTML = html + '</div>';
    }

    // ---- Boxes --------------------------------------------------------------------------
    /** Shorthand for document.getElementById. */
    const $ = id => document.getElementById(id);

    /** Opens the change request dialog filled from r, or empty to raise a new one. */
    function openRequest(r) {
        $('pcTitle').textContent = r ? 'CR-' + r.number + ': ' + r.title : T('raise_title');
        $('pcId').value = r ? r.id : '';
        $('pcName').value = r ? r.title : '';
        $('pcDesc').value = r ? (r.description || '') : '';
        $('pcReason').value = r ? (r.reason || '') : '';
        $('pcDays').value = r && r.impact_days !== null ? r.impact_days : '';
        $('pcCost').value = r && r.impact_cost !== null ? r.impact_cost : '';
        $('pcScope').value = r ? (r.impact_scope || '') : '';
        $('pcCostLabel').textContent = T('field_cost', { currency: cur() });
        $('pcError').hidden = true;
        P.openModal('prjChangeModal');
        setTimeout(() => $('pcName').focus(), 60);
    }

    /** Saves the change request dialog through tools.php and redraws the project. */
    async function saveRequest() {
        const err = $('pcError');
        try {
            await call({ action: 'change_save', id: $('pcId').value || undefined, title: $('pcName').value.trim(), description: $('pcDesc').value,
                reason: $('pcReason').value, impact_days: $('pcDays').value.trim(), impact_cost: $('pcCost').value.trim(), impact_scope: $('pcScope').value });
            P.closeModal('prjChangeModal'); P.toast(T('saved')); await ctx.refresh();
        } catch (e) { err.textContent = e.message; err.hidden = false; }
    }

    /** What approving will do, said before anybody presses it. */
    function approveEffect(r) {
        const c = ctx.data.control;
        const next = (c.baselines[0] ? c.baselines[0].number : 0) + 1;
        const parts = [];
        if (c.apply === 'plan') {
            const target = ctx.data.project.target_end_date;
            if (r.impact_days && target) {
                const d = new Date(target + 'T00:00:00Z'); d.setUTCDate(d.getUTCDate() + r.impact_days);
                parts.push(T('will_target', { from: date(target), to: date(d.toISOString().slice(0, 10)) }));
            }
            if (r.impact_cost && ctx.data.budget) parts.push(T('will_budget', { amount: signed(r.impact_cost, money) }));
        }
        parts.push(T('will_baseline', { n: next }));
        return T('will', { list: parts.join('; ') });
    }

    /** Opens the approve / reject dialog for a change request, saying beforehand what approving will do. */
    function openDecide(id, decision) {
        const r = ctx.data.control.requests.find(x => x.id === id);
        if (!r) return;
        deciding = { id: id, decision: decision };
        $('pdTitle').textContent = T(decision === 'approved' ? 'approve_title' : 'reject_title', { ref: 'CR-' + r.number });
        $('pdIntro').textContent = decision === 'approved' ? approveEffect(r) : T('reject_effect');
        $('pdNotes').value = '';
        $('pdSave').textContent = T(decision === 'approved' ? 'approve' : 'reject');
        $('pdSave').classList.toggle('danger', decision === 'rejected');
        $('pdError').hidden = true;
        P.openModal('prjDecideModal');
    }

    /** Records the approve or reject decision with its notes and redraws the project. */
    async function decide() {
        try {
            await call({ action: 'change_decide', id: deciding.id, decision: deciding.decision, notes: $('pdNotes').value });
            P.closeModal('prjDecideModal'); P.toast(T(deciding.decision === 'approved' ? 'approved' : 'rejected')); await ctx.refresh();
        } catch (e) { $('pdError').textContent = e.message; $('pdError').hidden = false; }
    }

    /** Takes a baseline of the plan as it is now, with the typed label, and goes back to comparing with the latest. */
    async function takeBaseline() {
        try {
            await call({ action: 'baseline_take', label: $('pbsLabel').value.trim() });
            P.closeModal('prjBaselineModal'); pick = null; P.toast(T('taken_toast')); await ctx.refresh();
        } catch (e) { $('pbsError').textContent = e.message; $('pbsError').hidden = false; }
    }

    /** Wires the tab's buttons (raise, take a baseline, edit, approve, reject, withdraw), the baseline picker and the dialogs' save buttons, once. */
    function wire() {
        if (wired) return;
        wired = true;
        $('pvControl').addEventListener('click', async e => {
            if (e.target.closest('[data-ctl-raise]')) { openRequest(null); return; }
            if (e.target.closest('[data-ctl-baseline]')) {
                $('pbsLabel').value = ''; $('pbsError').hidden = true; P.openModal('prjBaselineModal'); setTimeout(() => $('pbsLabel').focus(), 60); return;
            }
            const ed = e.target.closest('[data-ctl-edit]');
            if (ed) { openRequest(ctx.data.control.requests.find(x => x.id === Number(ed.dataset.ctlEdit))); return; }
            const dc = e.target.closest('[data-ctl-decide]');
            if (dc) { openDecide(Number(dc.dataset.id), dc.dataset.ctlDecide); return; }
            const wd = e.target.closest('[data-ctl-withdraw]');
            if (wd) {
                const ok = await window.showConfirm({ title: T('withdraw_title'), message: T('withdraw_body'), okLabel: T('withdraw'), okClass: 'danger' });
                if (!ok) return;
                try { await call({ action: 'change_withdraw', id: Number(wd.dataset.ctlWithdraw) }); await ctx.refresh(); } catch (er) { P.toast(er.message, 'error'); }
            }
        });
        $('pvControl').addEventListener('change', e => {
            if (e.target.id === 'ctlPick') { pick = Number(e.target.value); render(); }
        });
        $('pcSave').addEventListener('click', saveRequest);
        $('pdSave').addEventListener('click', decide);
        $('pbsSave').addEventListener('click', takeBaseline);
    }

    window.PrjControl = {
        render(c) { ctx = c; wire(); render(); },
    };
})();
