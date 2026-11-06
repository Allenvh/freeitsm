/**
 * FormRender — the one walk over a form's fields.
 *
 * 🔑 WHY THIS FILE EXISTS. A form is drawn in three places, each with its own
 * markup and its own per-type switch:
 *
 *   forms/fill.php              the analyst filler   (.form-field, error divs)
 *   self-service/catalogue.php  the portal           (.cat-field, id="fN")
 *   forms/edit/index.php        the builder preview  (.preview-field, disabled)
 *
 * ⚠️ THEIR MARKUP IS NOT MERGED, AND MUST NOT BE. `form-logic.js` already makes
 * that argument and it is right: the three genuinely look different, and one
 * function emitting all three would change how every existing form renders for
 * no benefit. What they must not each own is the *walk* — the order fields come
 * in, the width each one gets, and what happens to a type nobody taught them.
 *
 * 🔴 THE FAILURE THIS EXISTS TO STOP. Before this file, an unhandled field type
 * did three different silent things:
 *
 *   filler   no `default:` at all      -> the question VANISHED from the form
 *   portal   `default:` rendered text  -> a text box pretending to be the control,
 *                                         accepting and storing a wrong answer
 *   preview  `default: return ''`      -> the question VANISHED from the preview
 *
 * All three are silent, and the portal's is the worst: it takes an answer. A
 * surface now returns null for a type it cannot draw and this file renders a
 * visible, non-submittable notice and logs to the console. Loud, in one place.
 *
 * Depends on: FormLogic (widths), window.t (i18n).
 */
(function (global) {
    'use strict';

    /**
     * Walk a form and build its markup.
     *
     * @param {Array}  fields   the form's questions — the POOL
     * @param {Object} surface  { name, field(f, ctx) }
     *                          `field` returns the markup for ONE field, or null
     *                          if this surface cannot draw that type.
     *                          ctx = { width, wrapAttrs }
     * @param {Object} [layout] { type, rows:[{cells:[{field,width,rowspan}]}] }
     *                          from the server, already reconciled against the
     *                          pool. Absent means "walk the pool in its own
     *                          order", which is what every caller did before
     *                          layouts existed and is still the fallback if an
     *                          endpoint has not been taught to send one.
     * @returns {string} html
     */
    function render(fields, surface, layout) {
        if (!surface || typeof surface.field !== 'function') {
            throw new Error('FormRender.render: surface must provide field()');
        }
        var name = surface.name || 'unnamed surface';
        var cells = layout ? cellsOf(layout, fields) : poolCells(fields);
        var html = '';

        cells.forEach(function (cell) {
            /* 🔑 THE WIDTH COMES FROM THE CELL, not from the field. For a derived
               layout the two are identical — the derivation reads the field's own
               width — but once a form has been laid out deliberately, where a
               question sits is a property of the LAYOUT and must win. */
            /* 🔑 EVERYTHING THAT APPLIES TO EVERY FIELD TYPE GOES IN HERE, ONCE.
               The width covered all eleven types from one line for this reason,
               and the label position now does the same across all three
               surfaces. Putting either inside a surface's switch would be eleven
               edits per surface and a silently unstyled twelfth type. */
            var pos = global.FormLogic ? global.FormLogic.labelPosition(cell.field) : 'above';
            var ctx = {
                width: cell.width,
                labelPosition: pos,
                /* data-wrap-id is deliberately separate from data-field-id, which
                   the value-reading code uses with two different meanings (on the
                   input for simple types, on the wrapper for groups). Conditional
                   visibility only ever looks for data-wrap-id. */
                wrapAttrs: 'data-wrap-id="' + cell.field.id + '" data-width="' + cell.width + '"'
                         + (pos === 'above' ? '' : ' data-label-pos="' + pos + '"')
            };

            var markup = surface.field(cell.field, ctx);
            if (markup === null || markup === undefined || markup === '') {
                html += unknown(cell.field, ctx, name);
                return;
            }
            html += markup;
        });

        return html;
    }

    /** The pool in its own order, each field at its own width. */
    function poolCells(fields) {
        return (fields || []).map(function (f) {
            return { field: f, width: global.FormLogic ? global.FormLogic.fieldWidth(f) : 12 };
        });
    }

    /**
     * A layout, flattened to the stream of cells the surfaces draw.
     *
     * 🔴 A FLOW LAYOUT EMITS NO ROW ELEMENTS. Its rows are structural — they
     * record which questions share a line — but the CSS grid already wraps at
     * twelve columns, so drawing real rows would change how every existing form
     * renders for no gain. Row elements arrive only when rowspan does, which is
     * the one thing a CSS grid cannot express by wrapping alone.
     *
     * ⚠️ A cell holding no question is a spacer (a grid layout's empty or merged
     * box). It occupies its width in the model and draws nothing here, which is
     * exactly what a part-filled row already does.
     */
    function cellsOf(layout, fields) {
        var byId = {};
        (fields || []).forEach(function (f) { byId[String(f.id)] = f; });

        var out = [];
        var placed = {};
        (layout.rows || []).forEach(function (row) {
            (row.cells || []).forEach(function (cell) {
                if (!cell || cell.field === null || cell.field === undefined) return;   // spacer
                var f = byId[String(cell.field)];
                if (!f) return;                    // reconciled away server-side already
                if (placed[String(cell.field)]) return;   // never draw one question twice
                placed[String(cell.field)] = true;
                out.push({ field: f, width: cell.width || 12 });
            });
        });

        /* 🔴 ANYTHING THE LAYOUT DID NOT PLACE IS STILL DRAWN, at the end.
           The server already reconciles a layout against the pool, so this should
           never find anything — which is exactly why it is here. If a layout ever
           arrives unreconciled (a stale cached response, an endpoint that was
           never taught, a hand-written one) the alternative is a question that
           silently does not appear, and a required question that cannot be
           answered because nobody can see it. That is the same silent drop this
           whole file exists to stop, arriving one layer further in. */
        (fields || []).forEach(function (f) {
            if (placed[String(f.id)]) return;
            if (global.console && console.warn) {
                console.warn('FormRender: field ' + f.id + ' is not placed by the layout — appended');
            }
            out.push({ field: f, width: global.FormLogic ? global.FormLogic.fieldWidth(f) : 12 });
        });

        return out;
    }

    /**
     * What a surface shows for a field type it cannot draw.
     *
     * 🔴 Deliberately NOT an input. The portal used to fall back to a text box,
     * which looks like a working question and stores whatever is typed into it
     * as that field's answer. Showing nothing is bad; showing something that
     * takes a wrong answer is worse. This renders a notice, carries the wrap
     * attributes so conditional visibility still governs it, and shouts.
     */
    function unknown(field, ctx, surfaceName) {
        if (global.console && console.error) {
            console.error('FormRender: ' + surfaceName + ' cannot draw field type "'
                + (field && field.field_type) + '" (field ' + (field && field.id) + ')');
        }
        var label = '';
        if (field && field.label) {
            label = String(field.label).replace(/[&<>"]/g, function (c) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c];
            });
        }
        var msg = (global.t ? global.t('forms.render.unsupported_type')
                            : 'This question cannot be shown here.');
        if (msg === 'forms.render.unsupported_type') msg = 'This question cannot be shown here.';
        return '<div class="form-field-unsupported" ' + ctx.wrapAttrs + '>'
             + (label ? '<strong>' + label + '</strong> ' : '')
             + '<span>' + msg + '</span></div>';
    }

    global.FormRender = { render: render };
})(window);
