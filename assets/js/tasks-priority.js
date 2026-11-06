/**
 * How a task's priority is drawn — the one home for it.
 *
 * 🔴 The colour comes from `task_priorities.colour`, NEVER from the name.
 *
 * Every renderer used to build a CSS class out of the priority's display name
 * (`priority-dot ${t.priority.toLowerCase()}`) against four hardcoded English
 * rules — urgent / high / medium / low. Rename a priority, translate it, or add
 * a fifth, and the class matched nothing: the element still rendered, as an 8px
 * transparent circle. No error, no fallback, no dot. Reported from a German
 * install (discussion #108) where the priorities had been renamed to Hoch /
 * Mittel / Niedrig, which is the first thing a German admin would do.
 *
 * Same disease as GH #79, where every ticket intake path looked the starting
 * status up by the word 'Open' and a German site that had renamed it to Offen
 * got tickets with no status at all. The cure is the same: the display name is
 * the user's to change, so nothing may be derived from it.
 *
 * It was also wrong in English. The seeded colours and the CSS never agreed —
 * Low is configured #16a34a green in Settings and was drawn #999 grey — so the
 * swatch an admin picked was not the colour they got.
 *
 * Loaded by the board, timeline and table pages; keep it dependency-free so it
 * can sit ahead of any of them.
 */
const TasksPriority = (function () {
    'use strict';

    /** Placements offered by Settings → Tasks → Card. */
    const STYLES = ['off', 'dot', 'pill', 'border'];

    /**
     * Shown when a priority exists but has no colour set. `colour` is NULL-able
     * and the lookup screen lets it be cleared, so this is a real state rather
     * than a defensive nicety. A neutral grey dot still carries the name in its
     * tooltip, which beats rendering nothing and looking like a missing field.
     */
    const FALLBACK = '#9ca3af';

    /**
     * Only a literal hex value may reach a style attribute. Mirrors
     * inboxSafeColour() in inbox.js — the colour is admin-editable free text,
     * so it is untrusted input on its way into markup.
     */
    function safeColour(value) {
        const v = String(value == null ? '' : value).trim();
        return /^#[0-9a-fA-F]{3,8}$/.test(v) ? v : null;
    }

    /** HTML-escapes a value; null and undefined become an empty string. */
    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, c => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[c]));
    }

    /**
     * Coerce a stored value to a known style.
     * Legacy installs hold the old on/off boolean: 1 becomes a dot, which is
     * exactly what they see today, so nobody's board changes under them.
     */
    function normaliseStyle(value) {
        if (STYLES.indexOf(value) !== -1) return value;
        if (value === 1 || value === '1' || value === true) return 'dot';
        return 'off';
    }

    /**
     * The inline indicator — a dot, or a pill that also carries the name.
     * Returns '' for 'off', for 'border' (which is drawn by the container, not
     * here) and when the task has no priority at all.
     */
    function markup(name, colour, style) {
        const st = normaliseStyle(style);
        if (st !== 'dot' && st !== 'pill') return '';
        if (!name) return '';
        const c = safeColour(colour) || FALLBACK;
        const dot = `<span class="priority-dot" style="background:${c}" title="${esc(name)}"></span>`;
        if (st === 'dot') return dot;
        return `<span class="priority-pill">${dot}<span class="priority-pill-label">${esc(name)}</span></span>`;
    }

    /**
     * The left-edge accent, as attributes for the container element.
     *
     * ⚠️ This emits the element's ENTIRE style attribute, which is why callers
     * that already have inline styles pass them in as `extraStyle` rather than
     * writing their own style="". An element carrying two style attributes
     * silently drops the second, so a caller doing both would lose either its
     * width or its accent depending on the order — invisibly, and only in the
     * one placement out of four that uses this.
     *
     * No title here: the border placement is the deliberately minimal one, and
     * the elements that take it (a whole card, a timeline row) already own
     * their tooltip. The dot and pill carry the name instead.
     */
    function accentAttrs(name, colour, style, extraStyle) {
        const base = extraStyle ? String(extraStyle) : '';
        if (normaliseStyle(style) !== 'border' || !name) {
            return base ? ` style="${base}"` : '';
        }
        const c = safeColour(colour) || FALLBACK;
        return ` data-priority-accent="1" style="${base}--priority-accent:${c}"`;
    }

    return { STYLES, FALLBACK, safeColour, normaliseStyle, markup, accentAttrs };
})();

if (typeof window !== 'undefined') window.TasksPriority = TasksPriority;
