/**
 * The text history viewer for a change's long fields (#180 Part 2).
 *
 *   openRevisionViewer(changeId, field, revisionId?, compareTo?)  - compareTo: a revision id to compare against (an approval round's)
 *
 * Lists every saved version of one field (newest first, the baseline last and
 * labelled), shows the chosen version against the one before it - or any other -
 * as a word-by-word diff, or side by side as it was saved.
 *
 * TRAP: a revision is author-written HTML shown to somebody else. Every version is
 * rendered through safeHtmlFragment() (assets/js/safe-html.js), never assigned to
 * innerHTML raw, and the diff works on TEXT (HTML stripped), so no markup from a
 * revision ever reaches the page except through the cleaner.
 *
 * Needs: safe-html.js, i18n.js (window.t), escapeHtml() and formatDateTime() from
 * change-management.js, and the #revisionModal markup in change-management/index.php.
 */
(function () {
    'use strict';

    const API = () => window.API_BASE;
    const T = (k, p) => window.t('change-management.revisions.' + k, p);

    // Word diffs above this many token pairs are not worth the browser's time (an LCS
    // table of 4 million cells); both versions are shown side by side instead.
    const DIFF_CELL_LIMIT = 4000000;

    let state = { changeId: 0, field: '', list: [], selected: 0, compareTo: null, tab: 'changes', cache: {} };

    /** Renders a revision's content safely: HTML through the shared cleaner, text escaped. */
    function safeContent(rev) {
        if (!rev || rev.content === null || rev.content === '') {
            return `<em class="rev-empty">${escapeHtml(T('empty_value'))}</em>`;
        }
        if (rev.format === 'html') {
            if (typeof safeHtmlFragment !== 'function') {
                console.error('FreeITSM: assets/js/safe-html.js did not load - revision shown as plain text.');
                return `<pre class="rev-plain">${escapeHtml(rev.content)}</pre>`;
            }
            return safeHtmlFragment(rev.content);
        }
        return `<pre class="rev-plain">${escapeHtml(rev.content)}</pre>`;
    }

    /** The readable text of a revision, for the diff: block elements become line breaks. */
    function plainText(rev) {
        if (!rev || !rev.content) return '';
        if (rev.format !== 'html') return rev.content;
        const doc = new DOMParser().parseFromString(rev.content, 'text/html');
        doc.querySelectorAll('br').forEach(b => b.replaceWith('\n'));
        doc.querySelectorAll('p, div, li, h1, h2, h3, h4, h5, h6, tr, blockquote, pre')
           .forEach(el => el.append('\n'));
        return (doc.body ? doc.body.textContent : '').replace(/\n{3,}/g, '\n\n').trim();
    }

    /** Words and the whitespace between them, so a diff can be put back together exactly. */
    function tokens(s) {
        return s.match(/\s+|[^\s]+/g) || [];
    }

    /**
     * A word-level diff of two strings as HTML (<del>/<ins>, everything escaped), or
     * null when the texts are too long to diff in the browser.
     */
    function wordDiff(oldText, newText) {
        const a = tokens(oldText), b = tokens(newText);
        if (a.length * b.length > DIFF_CELL_LIMIT) return null;
        // Longest common subsequence, backwards, in a flat typed array.
        const n = a.length, m = b.length, w = m + 1;
        const L = new Uint32Array((n + 1) * w);
        for (let i = n - 1; i >= 0; i--) {
            for (let j = m - 1; j >= 0; j--) {
                L[i * w + j] = a[i] === b[j] ? L[(i + 1) * w + j + 1] + 1
                                             : Math.max(L[(i + 1) * w + j], L[i * w + j + 1]);
            }
        }
        // Runs of the same kind are merged, so an inserted sentence is one highlight.
        const runs = [];
        const put = (tag, s) => {
            const last = runs[runs.length - 1];
            if (last && last[0] === tag) last[1] += s; else runs.push([tag, s]);
        };
        let i = 0, j = 0;
        while (i < n && j < m) {
            if (a[i] === b[j]) { put('', a[i]); i++; j++; }
            else if (L[(i + 1) * w + j] >= L[i * w + j + 1]) { put('del', a[i]); i++; }
            else { put('ins', b[j]); j++; }
        }
        while (i < n) put('del', a[i++]);
        while (j < m) put('ins', b[j++]);
        return runs.map(([tag, s]) => tag ? `<${tag}>${escapeHtml(s)}</${tag}>` : escapeHtml(s)).join('');
    }

    /** One line describing who saved a version, and when, in words that never overclaim. */
    function describe(rev) {
        const when = formatDateTime(rev.created_at);
        if (rev.is_baseline) return T('baseline');
        if (rev.field === 'comment') return T('deleted_comment', { name: rev.editor_name || T('unknown_editor'), date: when });
        if (rev.source === 'system') return T('system_edit', { date: when });
        const who = T('by', { name: rev.editor_name || T('unknown_editor'), date: when });
        return rev.source === 'api' ? who + ' · ' + T('source_api') : who;
    }

    /** Fetches one revision with its content, cached for the life of the viewer. */
    async function fetchRevision(id) {
        if (state.cache[id]) return state.cache[id];
        const res = await fetch(API() + 'get_revision.php?change_id=' + state.changeId + '&revision_id=' + id);
        const data = await res.json();
        if (!data.success) throw new Error(data.error || 'load failed');
        state.cache[id] = data.revision;
        return data.revision;
    }

    function renderList() {
        const el = document.getElementById('revisionList');
        el.innerHTML = state.list.map(r => `
            <button type="button" class="rev-item${r.id === state.selected ? ' active' : ''}${r.is_baseline ? ' baseline' : ''}"
                    onclick="revisionSelect(${r.id})">
                <span class="rev-item-who">${escapeHtml(describe(r))}</span>
                <span class="rev-item-meta">#${r.id}</span>
            </button>`).join('');
    }

    function renderCompareSelect(current) {
        const sel = document.getElementById('revisionCompare');
        const others = state.list.filter(r => r.id !== current.id);
        sel.innerHTML = `<option value="">${escapeHtml(current.previous_id ? T('previous') : T('nothing_before'))}</option>`
            + others.map(r => `<option value="${r.id}"${r.id === state.compareTo ? ' selected' : ''}>#${r.id} · ${escapeHtml(describe(r))}</option>`).join('');
    }

    async function renderBody() {
        const body = document.getElementById('revisionBody');
        body.innerHTML = `<div class="rev-loading">${escapeHtml(T('loading'))}</div>`;
        try {
            const current = await fetchRevision(state.selected);
            renderCompareSelect(current);
            const otherId = state.compareTo || current.previous_id;
            const other = otherId ? await fetchRevision(otherId) : null;
            // Older on the left, newer on the right, whichever way they were picked.
            const [older, newer] = (other && other.id > current.id) ? [current, other] : [other, current];

            const note = current.is_baseline
                ? `<div class="rev-baseline-note">${escapeHtml(T('baseline_note', { date: formatDateTime(current.created_at) }))}</div>` : '';

            document.querySelectorAll('.rev-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === state.tab));

            if (state.tab === 'changes' && older) {
                const diff = wordDiff(plainText(older), plainText(newer));
                if (diff !== null) {
                    const same = plainText(older) === plainText(newer);
                    body.innerHTML = note + (same ? `<div class="rev-baseline-note">${escapeHtml(T('no_difference'))}</div>` : '')
                        + `<div class="rev-diff">${diff}</div>`;
                    return;
                }
                body.innerHTML = note + `<div class="rev-baseline-note">${escapeHtml(T('too_long_to_diff'))}</div>` + sideBySide(older, newer);
                return;
            }
            body.innerHTML = note + (older ? sideBySide(older, newer) : `<div class="rev-pane"><div class="rev-pane-head">${escapeHtml(T('this_version'))}</div><div class="rev-pane-body">${safeContent(current)}</div></div>`);
        } catch (e) {
            body.innerHTML = `<div class="rev-loading">${escapeHtml(T('load_failed'))}</div>`;
        }
    }

    function sideBySide(older, newer) {
        const pane = r => `<div class="rev-pane"><div class="rev-pane-head">#${r.id} · ${escapeHtml(describe(r))}</div><div class="rev-pane-body">${safeContent(r)}</div></div>`;
        return `<div class="rev-side">${pane(older)}${pane(newer)}</div>`;
    }

    /** Opens the viewer on one field of a change, at a given revision or the newest. */
    window.openRevisionViewer = async function (changeId, field, revisionId, compareTo) {
        state = { changeId, field, list: [], selected: 0, compareTo: compareTo || null, tab: 'changes', cache: {} };
        document.getElementById('revisionTitle').textContent =
            T('title', { field: window.t('change-management.revisions.field.' + field) });
        document.getElementById('revisionList').innerHTML = '';
        document.getElementById('revisionBody').innerHTML = `<div class="rev-loading">${escapeHtml(T('loading'))}</div>`;
        document.getElementById('revisionModal').classList.add('active');
        try {
            const res = await fetch(API() + 'get_revisions.php?change_id=' + changeId + '&field=' + encodeURIComponent(field));
            const data = await res.json();
            if (!data.success) throw new Error(data.error);
            state.list = data.revisions || [];
            if (!state.list.length) {
                document.getElementById('revisionBody').innerHTML = `<div class="rev-loading">${escapeHtml(T('none'))}</div>`;
                return;
            }
            state.selected = (revisionId && state.list.some(r => r.id === revisionId)) ? revisionId : state.list[0].id;
            renderList();
            renderBody();
        } catch (e) {
            document.getElementById('revisionBody').innerHTML = `<div class="rev-loading">${escapeHtml(T('load_failed'))}</div>`;
        }
    };

    window.revisionSelect = function (id) {
        state.selected = id;
        state.compareTo = null;
        renderList();
        renderBody();
    };

    window.revisionCompareChanged = function (value) {
        state.compareTo = value ? parseInt(value, 10) : null;
        renderBody();
    };

    window.revisionTab = function (tab) {
        state.tab = tab;
        renderBody();
    };

    window.closeRevisionViewer = function () {
        document.getElementById('revisionModal').classList.remove('active');
    };
})();
