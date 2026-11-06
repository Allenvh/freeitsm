/**
 * Search panel dismissal — Escape, and optionally clicking away (GH #144).
 *
 * Reported by mbsouth: the search panel should close when you click a result or
 * click outside it. Clicking a result is already the behaviour in Change
 * Management, Problem Management and Contracts, because in those modules the
 * result opens a detail view that takes over the screen. Tickets deliberately
 * keeps the panel open — its results load into the reading pane BESIDE the
 * panel, so you can click down a list of candidates without searching again.
 * That is a real working style, so it is left alone.
 *
 * What this file adds:
 *   1. Escape closes the panel. No setting — three of the four modules were
 *      missing it and nobody wants Escape not to work.
 *   2. Clicking away closes the panel, IF the analyst asked for it
 *      (System > Preferences > Display; window.SEARCH_PANEL_CLOSE_OUTSIDE).
 *
 * 🔑 Why "outside" is not simply "not the panel". Ed's objection, and it is the
 * right one: you find a ticket, then click its Properties to change something —
 * a literal reading of "outside" would close your search at exactly the moment
 * you were acting on its result. So the click tells us the intent:
 *   - the ticket you just opened (the reading pane, properties included) is
 *     INSIDE: you are working on what the search gave you;
 *   - the folder pane, the ticket list, the toolbar and bare page chrome are
 *     OUTSIDE: you have moved on.
 * Anything else on top (a confirm dialog, a toast) is also treated as inside,
 * because a click there is aimed at that thing, not at dismissing a panel
 * behind it.
 *
 * Registration is per module: each calls initSearchPanelDismiss() with its own
 * panel and close function, since the panels have different ids and the modules
 * do not share a close routine.
 */
(function () {
    'use strict';

    if (window.initSearchPanelDismiss) return;   // guard: re-included on a page

    /**
     * Wires one module's search panel to close on Escape and, when the analyst's
     * preference is on, on a click outside it (and outside opts.inside).
     *
     * @param {Object}   opts
     * @param {string}   opts.panelId   element id of the floating panel
     * @param {Function} opts.close     the module's own close routine
     * @param {string[]} [opts.inside]  extra selectors that count as INSIDE
     *                                  (Tickets passes its reading pane)
     * @param {Function} [opts.isOpen]  override for "is it showing?"
     */
    window.initSearchPanelDismiss = function (opts) {
        var panelId = opts.panelId;
        var close   = opts.close;
        var extra   = opts.inside || [];

        /** Returns the panel element. */
        function panel() { return document.getElementById(panelId); }

        /**
         * Whether the panel is showing - the module's own test when given, otherwise the panel's
         * active class.
         */
        function isOpen() {
            if (opts.isOpen) return !!opts.isOpen();
            var p = panel();
            return !!p && p.classList.contains('active');
        }

        // Escape, always. Ignored while a text field in the panel is mid-edit
        // with an IME composing, and while another dialog is on top — that
        // dialog's own Escape handler owns the key at that point.
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape' || !isOpen()) return;
            if (e.isComposing) return;
            if (document.querySelector('[role="dialog"], .fitsm-confirm-overlay.active')) return;
            close();
        });

        // Clicking away, opt-in. Registered once; the preference is read at
        // click time so turning it on takes effect without a reload.
        document.addEventListener('mousedown', function (e) {
            if (!window.SEARCH_PANEL_CLOSE_OUTSIDE || !isOpen()) return;
            var t = e.target;
            if (!t || !t.closest) return;

            // The panel itself, including its results and the drag header.
            if (t.closest('#' + panelId)) return;

            // Whatever the module considers "the thing the search just gave me".
            for (var i = 0; i < extra.length; i++) {
                if (t.closest(extra[i])) return;
            }

            // Anything layered on top owns its own clicks.
            if (t.closest('[role="dialog"], .fitsm-confirm-overlay, .toast-container')) return;

            close();
        });
    };
})();
