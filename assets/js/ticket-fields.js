/**
 * Custom ticket fields (#179) - one renderer for every screen that shows them:
 * the New ticket form, the ticket panel in the inbox, and the portal's New
 * ticket form.
 *
 * 🔑 ONE renderer, because the type rules - a yes/no field has THREE states,
 * a number carries its unit, a date has a mode - are exactly the things that
 * must not end up subtly different on two screens. Custom asset fields learned
 * this first (cfFieldRow in asset-management/index.php).
 *
 * The field list itself comes from the server (TicketFieldsService), which also
 * enforces everything; what this file checks in the browser is only a courtesy.
 */
(function () {
    'use strict';

    const esc = s => String(s == null ? '' : s)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    const say = (key, english, params) =>
        (typeof window.tf === 'function') ? window.tf('common.ticket_fields.' + key, english, params) : english;

    /** Stored 'Y-m-d H:i:s' -> what the matching input expects. */
    function dateValue(v, mode) {
        if (!v) return '';
        const s = String(v).replace(' ', 'T');
        if (mode === 'time') return s.substring(11, 16);
        if (mode === 'datetime') return s.substring(0, 16);
        return s.substring(0, 10);
    }

    /**
     * One field as HTML.
     *
     * @param f        a field from the server: key, label, type, config, options, help_text, required_on_create, value
     * @param opts     variant: 'toolbar' (the ticket panel) or 'form' (a New ticket form);
     *                 onchange: JS to run when it changes (the panel saves each field as it changes);
     *                 required: mark required_on_create fields as required (the New ticket forms);
     *                 inputClass / selectClass / labelClass / groupClass: the form's own classes, so a
     *                 custom field looks exactly like the built-in fields beside it
     */
    function control(f, opts) {
        opts = opts || {};
        const toolbar = opts.variant === 'toolbar';
        const isSelect = f.type === 'boolean' || f.type === 'dropdown';
        const cls = toolbar ? 'toolbar-select' : ((isSelect ? opts.selectClass : opts.inputClass) || opts.inputClass || '');
        const cfg = f.config || {};
        const value = f.value === undefined ? null : f.value;
        const req = opts.required && f.required_on_create;
        const attrs = `data-tf-key="${esc(f.key)}" class="${cls}"${opts.onchange ? ` onchange="${esc(opts.onchange)}"` : ''}${req ? ' required' : ''}`;
        let input;
        switch (f.type) {
            case 'boolean':
                // ⚠️ THREE states, not two: "Not set" is a real answer and must
                // stay reachable - absent is not No.
                input = `<select ${attrs}>
                    <option value="" ${value === null ? 'selected' : ''}>${esc(say('not_set', 'Not set'))}</option>
                    <option value="1" ${value === true ? 'selected' : ''}>${esc(say('yes', 'Yes'))}</option>
                    <option value="0" ${value === false ? 'selected' : ''}>${esc(say('no', 'No'))}</option>
                </select>`;
                break;
            case 'dropdown':
                input = `<select ${attrs}>
                    <option value="">${esc(say('not_set', 'Not set'))}</option>
                    ${(f.options || []).map(o => `<option value="${esc(o)}" ${value === o ? 'selected' : ''}>${esc(o)}</option>`).join('')}
                </select>`;
                break;
            case 'number': {
                const step = cfg.decimals ? (1 / Math.pow(10, cfg.decimals)) : 1;
                input = `<input type="number" step="${step}" value="${value !== null ? esc(value) : ''}" ${attrs}>`;
                if (cfg.unit) input = `<span class="tf-numrow">${input}<span class="tf-unit">${esc(cfg.unit)}</span></span>`;
                break;
            }
            case 'date': {
                const mode = cfg.date_mode || 'date';
                const type = mode === 'time' ? 'time' : (mode === 'datetime' ? 'datetime-local' : 'date');
                input = `<input type="${type}" value="${dateValue(value, mode)}" ${attrs}>`;
                break;
            }
            case 'url':
                input = `<input type="url" placeholder="https://" value="${esc(value || '')}" ${attrs}>`;
                break;
            case 'email':
                input = `<input type="email" value="${esc(value || '')}" ${attrs}>`;
                break;
            default:
                input = cfg.multiline
                    ? `<textarea rows="${toolbar ? 2 : 3}" ${attrs}>${esc(value || '')}</textarea>`
                    : `<input type="text" value="${esc(value || '')}" ${attrs}>`;
        }
        const star = req || (toolbar && f.required_on_create) ? ' <span class="tf-req">*</span>' : '';
        if (toolbar) {
            // A long text field takes the whole row of the panel's grid.
            const wide = (f.type === 'text' && cfg.multiline) ? ' tf-wide' : '';
            return `<div class="toolbar-field tf-field${wide}"${f.help_text ? ` title="${esc(f.help_text)}"` : ''}>
                <label class="toolbar-label">${esc(f.label)}${star}</label>${input}</div>`;
        }
        return `<div class="${opts.groupClass || 'form-group'} tf-field">
            <label${opts.labelClass ? ` class="${opts.labelClass}"` : ''}>${esc(f.label)}${star}</label>${input}
            ${f.help_text ? `<small class="tf-hint">${esc(f.help_text)}</small>` : ''}</div>`;
    }

    /** Every field as HTML, in order. */
    function render(fields, opts) {
        return (fields || []).map(f => control(f, opts)).join('');
    }

    /**
     * The answers currently in a container: {key: raw value}. An empty control
     * is sent as '' - "clear it" - never left out, because left out means "leave alone".
     */
    function collect(container) {
        const out = {};
        if (!container) return out;
        container.querySelectorAll('[data-tf-key]').forEach(el => { out[el.dataset.tfKey] = el.value; });
        return out;
    }

    /** The label of the first field required when logging that is still empty, or null. */
    function firstMissing(container, fields) {
        const values = collect(container);
        const f = (fields || []).find(x => x.required_on_create && (values[x.key] === undefined || values[x.key] === ''));
        return f ? f.label : null;
    }

    /**
     * Answers as a read-only list (discussion #193 - the portal shows a requester
     * what they gave): a yes/no as Yes or No, a date in the reader's own date
     * format, a number with its unit, long text with its line breaks.
     *
     * @param fields  [{label, type, config, value, display}], answered ones only
     */
    function readOnly(fields) {
        const text = f => {
            if (f.type === 'boolean') return say(f.value ? 'yes' : 'no', f.value ? 'Yes' : 'No');
            if (f.type === 'date') {
                // Naive wall-clock values: never shifted by a timezone.
                const mode = (f.config && f.config.date_mode) || 'date';
                const fn = mode === 'time' ? window.fmtNaiveTime : mode === 'datetime' ? window.fmtNaiveDateTime : window.fmtNaiveDate;
                return (typeof fn === 'function' && fn(f.value)) || f.display;
            }
            return f.display;
        };
        return '<dl class="tf-answers">' + (fields || []).map(f =>
            '<div class="tf-answer"><dt>' + esc(f.label) + '</dt><dd>' + esc(text(f)) + '</dd></div>').join('') + '</dl>';
    }

    window.TicketFields = { render, control, collect, firstMissing, dateValue, readOnly };
})();
