/**
 * Rota Page JS - Weekly staff rota management
 *
 * @wiki Rota-Copy-And-Paste-Developer-Deep-Dive
 */

const ROTA_API = '../api/tickets/';
const SETTINGS_API = '../api/settings/';

// Rota labels render through the shared formatters in assets/js/tz.js, so
// weekday and month names follow the interface language while the arrangement
// follows the analyst's chosen date format (GH #105). These were four
// Intl.DateTimeFormat instances built from <html lang>, which tied how a date
// LOOKS to which language it is IN.
//
// A rota grid dictates its own shapes — a column heading is a weekday and a day
// number, never a full date — so these use fmtNaiveTemplate rather than
// fmtNaiveDate. Rota dates are date-only wall-clock values, hence naive.
const WEEKDAY_SHORT_FMT = { format: (d) => fmtNaiveWeekday(d, true) };
const MONTH_SHORT_FMT   = { format: (d) => fmtNaiveTemplate(d, 'MON') };
const DAY_NUM_FMT       = { format: (d) => fmtNaiveTemplate(d, 'D') };
const MODAL_DATE_FMT    = { format: (d) => fmtNaiveWeekday(d, false) + ' ' + fmtNaiveTemplate(d, 'D MON') };

let currentWeekStart = null; // YYYY-MM-DD (Monday)
let rotaAnalysts = [];
let rotaShifts = [];
let rotaEntries = [];
let rotaLocations = [];
let includeWeekends = false;

// ==================== Initialisation ====================

document.addEventListener('DOMContentLoaded', function() {
    // Start with the current week
    const today = new Date();
    currentWeekStart = getMonday(today);
    loadRota();
});

/** Returns the Monday of the week holding a date, as YYYY-MM-DD. */
function getMonday(d) {
    const date = new Date(d);
    const day = date.getDay(); // 0=Sun 1=Mon...6=Sat
    const diff = day === 0 ? -6 : 1 - day;
    date.setDate(date.getDate() + diff);
    return formatDate(date);
}

/** Formats a Date as a local YYYY-MM-DD. */
function formatDate(d) {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
}

// ==================== Week Navigation ====================

/** Moves the rota forward or back by a number of weeks and reloads it. */
function changeWeek(delta) {
    const d = new Date(currentWeekStart + 'T00:00:00');
    d.setDate(d.getDate() + (delta * 7));
    currentWeekStart = formatDate(d);
    loadRota();
}

/** Jumps the rota back to the current week and reloads it. */
function goToThisWeek() {
    currentWeekStart = getMonday(new Date());
    loadRota();
}

// ==================== Data Loading ====================

/**
 * Loads the week's analysts, shifts, entries and locations from get_rota.php, then redraws the title
 * and grid.
 */
async function loadRota() {
    try {
        const response = await fetch(ROTA_API + 'get_rota.php?week=' + currentWeekStart);
        const data = await response.json();

        if (data.success) {
            rotaAnalysts = data.analysts || [];
            rotaShifts = data.shifts || [];
            rotaEntries = data.entries || [];
            rotaLocations = data.locations || [];
            includeWeekends = data.include_weekends == 1;

            updateTitle(data.week_start, data.week_end);
            renderRotaGrid(data.week_start);
        } else {
            console.error('Error loading rota:', data.error);
        }
    } catch (error) {
        console.error('Error loading rota:', error);
    }
}

/** Sets the week title (e.g. "3 - 7 Mar 2026"), ending on Friday unless weekends are included. */
function updateTitle(weekStart, weekEnd) {
    const start = new Date(weekStart + 'T00:00:00');
    const end = new Date(weekEnd + 'T00:00:00');

    let endDate = includeWeekends ? end : new Date(start);
    if (!includeWeekends) {
        endDate.setDate(endDate.getDate() + 4); // Friday
    }

    // Format month / day labels through Intl so they come out in the right
    // language and short form for the locale automatically.
    const startMonth = MONTH_SHORT_FMT.format(start);
    const endMonth   = MONTH_SHORT_FMT.format(endDate);

    let label;
    if (start.getMonth() === endDate.getMonth()) {
        label = `${start.getDate()} – ${endDate.getDate()} ${startMonth} ${start.getFullYear()}`;
    } else {
        label = `${start.getDate()} ${startMonth} – ${endDate.getDate()} ${endMonth} ${start.getFullYear()}`;
    }

    document.getElementById('rotaTitle').textContent = label;
}

// ==================== Grid Rendering ====================

/**
 * Rebuilds the rota grid - day headers, one row per analyst, and a cell per day showing the shift,
 * times, location and on-call badges.
 *
 * Every cell and header carries data-analyst / data-date so hover, selection and the right-click menu
 * can find it; the selection is re-marked afterwards.
 */
function renderRotaGrid(weekStart) {
    const grid = document.getElementById('rotaGrid');
    const numDays = includeWeekends ? 7 : 5;
    grid.className = 'rota-grid days-' + numDays;

    const today = formatDate(new Date());

    // Build day dates for the week. Weekday short names come from Intl so
    // they render natively for every locale (Mon / lun. / Mo / ਸੋਮ / etc.).
    const days = [];
    const startDate = new Date(weekStart + 'T00:00:00');
    for (let i = 0; i < numDays; i++) {
        const d = new Date(startDate);
        d.setDate(d.getDate() + i);
        days.push({
            date: formatDate(d),
            name: WEEKDAY_SHORT_FMT.format(d),
            dayNum: DAY_NUM_FMT.format(d),
            isToday: formatDate(d) === today
        });
    }

    // Build entries lookup: analyst_id -> date -> entry
    const entryMap = {};
    rotaEntries.forEach(e => {
        if (!entryMap[e.analyst_id]) entryMap[e.analyst_id] = {};
        entryMap[e.analyst_id][e.rota_date] = e;
    });

    const analystHeader  = escapeHtml(t('tickets.rota.analyst_col'));
    const onCallBadge    = escapeHtml(t('tickets.rota.on_call_badge'));
    const addEntryTitle  = escapeHtml(t('tickets.rota.add_entry'));

    let html = '';

    // Header row - corner cell + day headers.
    //
    // 🔑 A day header carries the same data-date its cells carry, and an
    // analyst name cell the same data-analyst. That is what makes hovering
    // either of them light up the whole line with one attribute selector, and
    // what lets the right-click menu paste down a column or across a row
    // without the grid having to be re-walked.
    html += `<div class="rota-col-header" style="text-align: left; padding-left: 12px;">${analystHeader}</div>`;
    days.forEach((day, colIdx) => {
        html += `<div class="rota-col-header rota-line-head${day.isToday ? ' today' : ''}" data-date="${day.date}" data-col="${colIdx}" oncontextmenu="return openRotaLineMenu(event, this, 'col');">
            <span class="day-name">${escapeHtml(day.name)}</span>
            <span class="day-date">${escapeHtml(day.dayNum)}</span>
        </div>`;
    });

    // Analyst rows
    if (rotaAnalysts.length === 0) {
        html += `<div class="rota-empty" style="grid-column: 1 / -1;"><p>${escapeHtml(t('tickets.rota.no_analysts'))}</p></div>`;
    } else {
        rotaAnalysts.forEach((analyst, rowIdx) => {
            // Analyst name cell
            html += `<div class="rota-analyst-name rota-line-head" data-analyst="${analyst.id}" data-row="${rowIdx}" oncontextmenu="return openRotaLineMenu(event, this, 'row');">${escapeHtml(analyst.full_name)}</div>`;

            // Day cells
            days.forEach((day, colIdx) => {
                const entry = entryMap[analyst.id] && entryMap[analyst.id][day.date];
                const todayClass = day.isToday ? ' today' : '';

                // 🔑 Every cell carries WHO and WHEN as data attributes, filled
                // or empty. The right-click menu needs to know which cell it
                // was opened on, and reading it back off the element beats
                // threading three arguments through an oncontextmenu string —
                // where an analyst name with an apostrophe would break out.
                const cellData = `data-analyst="${analyst.id}" data-date="${day.date}" data-row="${rowIdx}" data-col="${colIdx}" oncontextmenu="return openRotaCellMenu(event, this);"`;

                if (entry) {
                    const locStyle = entry.location_colour
                        ? `style="background:${entry.location_colour}; color:#fff;"`
                        : '';
                    const locLabel = escapeHtml(entry.location_name || '');
                    html += `<div class="rota-cell${todayClass}" ${cellData} data-entry="${entry.id}" onclick="openRotaEntryModal(${analyst.id}, '${day.date}', ${entry.id})">
                        <div class="rota-entry">
                            <div class="shift-name">${escapeHtml(entry.shift_name)}</div>
                            <div class="shift-times">${fmtTime(entry.start_time)} – ${fmtTime(entry.end_time)}</div>
                            <div class="badges">
                                ${locLabel ? `<span class="rota-badge" ${locStyle}>${locLabel}</span>` : ''}
                                ${entry.is_on_call == 1 ? `<span class="rota-badge on-call">${onCallBadge}</span>` : ''}
                            </div>
                        </div>
                    </div>`;
                } else {
                    html += `<div class="rota-cell${todayClass}" ${cellData} onclick="openRotaEntryModal(${analyst.id}, '${day.date}')">
                        <button class="rota-cell-add" title="${addEntryTitle}">+</button>
                    </div>`;
                }
            });
        });
    }

    grid.innerHTML = html;

    // The grid is rebuilt from scratch on every load, including the reload
    // after a save somebody else's change triggered. Re-marking the selection
    // keeps it alive across that instead of quietly losing it.
    applyRotaSelection();
}

/** Trims a HH:MM:SS time to HH:MM. */
function fmtTime(t) {
    if (!t) return '';
    return t.substring(0, 5);
}

/** HTML-escapes a string through a text node; empty values become an empty string. */
function escapeHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

// ==================== Entry Modal ====================

/**
 * Opens the rota entry dialog for an analyst and date - shift list, location radios and on-call -
 * filled from the entry when editing one.
 */
async function openRotaEntryModal(analystId, date, entryId) {
    // Find analyst name
    const analyst = rotaAnalysts.find(a => a.id == analystId);
    const analystName = analyst ? analyst.full_name : t('tickets.users.unknown_name');

    // Format date for display via Intl so weekday + month render natively
    // for the locale (e.g. fr "lundi 17 mai", de "Montag, 17. Mai").
    const d = new Date(date + 'T00:00:00');
    const dateLabel = MODAL_DATE_FMT.format(d);

    document.getElementById('entryContext').textContent = `${analystName} — ${dateLabel}`;
    document.getElementById('entryAnalystId').value = analystId;
    document.getElementById('entryDate').value = date;
    document.getElementById('entryId').value = '';

    // Populate shift dropdown
    const shiftSelect = document.getElementById('entryShift');
    const shiftPlaceholder = escapeHtml(t('tickets.rota.modal.shift_placeholder'));
    shiftSelect.innerHTML = `<option value="">${shiftPlaceholder}</option>` +
        rotaShifts.map(s => `<option value="${s.id}">${escapeHtml(s.name)} (${fmtTime(s.start_time)} – ${fmtTime(s.end_time)})</option>`).join('');

    // Render dynamic location radios driven by rota_locations lookup
    const locContainer = document.getElementById('entryLocationOptions');
    if (locContainer) {
        const defaultLoc = rotaLocations.find(l => l.is_default) || rotaLocations[0];
        const defaultId = defaultLoc ? defaultLoc.id : '';
        locContainer.innerHTML = rotaLocations.map(l => `
            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                <input type="radio" name="entryLocation" value="${l.id}" ${l.id == defaultId ? 'checked' : ''}>
                ${escapeHtml(l.name)}
            </label>
        `).join('');
    }

    document.getElementById('entryOnCall').checked = false;
    document.getElementById('entryDeleteBtn').style.display = 'none';
    document.getElementById('rotaEntryModalTitle').textContent = t('tickets.rota.modal.add_title');

    // If editing existing entry, populate values
    if (entryId) {
        const entry = rotaEntries.find(e => e.id == entryId);
        if (entry) {
            document.getElementById('entryId').value = entry.id;
            shiftSelect.value = entry.shift_id;
            if (entry.location_id) {
                const locRadio = document.querySelector(`input[name="entryLocation"][value="${entry.location_id}"]`);
                if (locRadio) locRadio.checked = true;
            }
            document.getElementById('entryOnCall').checked = entry.is_on_call == 1;
            document.getElementById('entryDeleteBtn').style.display = '';
            document.getElementById('rotaEntryModalTitle').textContent = t('tickets.rota.modal.edit_title');
        }
    }

    document.getElementById('rotaEntryModal').classList.add('active');
}

/** Closes the rota entry dialog. */
function closeRotaEntryModal() {
    document.getElementById('rotaEntryModal').classList.remove('active');
}

// Save entry
document.getElementById('rotaEntryForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const selectedLoc = document.querySelector('input[name="entryLocation"]:checked');
    const entryData = {
        id: document.getElementById('entryId').value || null,
        analyst_id: document.getElementById('entryAnalystId').value,
        rota_date: document.getElementById('entryDate').value,
        shift_id: document.getElementById('entryShift').value,
        location_id: selectedLoc ? parseInt(selectedLoc.value) : null,
        is_on_call: document.getElementById('entryOnCall').checked ? 1 : 0
    };

    try {
        const response = await fetch(ROTA_API + 'save_rota_entry.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(entryData)
        });
        const data = await response.json();
        if (data.success) {
            showToast(t('tickets.rota.toasts.saved'), 'success');
            closeRotaEntryModal();
            loadRota();
        } else {
            showToast(t('tickets.rota.toasts.error', { error: data.error }), 'error');
        }
    } catch (error) {
        showToast(t('tickets.rota.toasts.save_failed'), 'error');
    }
});

// Delete entry
async function deleteRotaEntry() {
    const id = document.getElementById('entryId').value;
    if (!id) return;
    if (!(await showConfirm({ title: 'Confirm', message: t('tickets.rota.delete_confirm'), okLabel: 'OK', okClass: 'primary' }))) return;

    try {
        const response = await fetch(ROTA_API + 'delete_rota_entry.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: parseInt(id) })
        });
        const data = await response.json();
        if (data.success) {
            showToast(t('tickets.rota.toasts.deleted'), 'success');
            closeRotaEntryModal();
            loadRota();
        } else {
            showToast(t('tickets.rota.toasts.error', { error: data.error }), 'error');
        }
    } catch (error) {
        showToast(t('tickets.rota.toasts.delete_failed'), 'error');
    }
}

// ==================== Copy and paste (Ed) ====================
//
// Two clipboards, both in memory only. Deliberately not sessionStorage: a
// clipboard that outlives the tab means opening the rota tomorrow with a
// half-remembered week still loaded and a Paste button offering to apply it,
// which is a worse failure than having to copy again.
//
// 🔑 A cell holds a SHIFT, not an entry row. What gets copied is what the entry
// says — shift, location, on-call — never its id, because pasting is an upsert
// onto a different (analyst, date) and the source row must not be touched.

let rotaCellClipboard = null;   // { shift_id, location_id, is_on_call, shift_name }
let rotaLineClipboard = null;   // { kind: 'col'|'row', label, count, entries: [...] }
let rotaWeekClipboard = null;   // { week_start, entries: [...], count }
let rotaCtxCell = null;         // the cell the context menu was opened on
let rotaCtxLine = null;         // or the column / row it was opened on

/**
 * One clipboard at a time for the grid (Ed).
 *
 * A cell holds one shift; a line holds a different shift in every cell. They
 * paste onto completely different things, so a menu offering both at once
 * would be asking the reader to work out which Paste is which. Copying
 * either one puts the other down, the way a clipboard actually behaves.
 *
 * (The whole-week clipboard is separate on purpose - it lives on its own
 * toolbar button rather than in this menu, and copying a cell should not
 * silently throw away a week you set up three screens ago.)
 */
function rotaSetClipboard(cell, line) {
    rotaCellClipboard = cell;
    rotaLineClipboard = line;
}

/** Returns the loaded rota entry for an analyst on a date, or null. */
function rotaEntryAt(analystId, date) {
    return rotaEntries.find(e => e.analyst_id == analystId && e.rota_date === date) || null;
}

/** Format a YYYY-MM-DD for a message, in the analyst's own date arrangement. */
function rotaDateLabel(date) {
    return MODAL_DATE_FMT.format(new Date(date + 'T00:00:00'));
}

// ---- Selecting more than one cell -------------------------------------
//
// Three ways to aim a paste at several cells, because three different jobs
// want different ones: drag a block when the shifts are scattered, hover a
// column heading when everybody works the same day, hover an analyst's name
// when one person works the same shift all week.
//
// 🔑 A plain click must still open the entry editor — that is what the grid
// has always done and it is the common action. So selecting is a gesture the
// editor can tell apart: a drag ACROSS cells, or ctrl/cmd-click. A press and
// release inside one cell is never a selection.

const rotaSelection = new Set();    // "analystId|YYYY-MM-DD"
let rotaDragAnchor = null;          // the cell a drag started on
let rotaDragMoved = false;          // ...and whether it ever left that cell
let rotaSuppressClick = false;      // the click that ends a drag opens nothing
let rotaHoverKey = '';              // the column / row currently lit up

/** Returns the selection key for a cell - "analystId|YYYY-MM-DD". */
function rotaCellKey(analystId, date) {
    return analystId + '|' + date;
}

/** Returns the selected cells as {analyst_id, rota_date} targets. */
function rotaSelectionTargets() {
    return Array.from(rotaSelection).map(key => {
        const parts = key.split('|');
        return { analyst_id: parts[0], rota_date: parts[1] };
    });
}

/** Marks the grid cells that are in the selection. */
function applyRotaSelection() {
    document.querySelectorAll('#rotaGrid .rota-cell').forEach(cell => {
        cell.classList.toggle('selected',
            rotaSelection.has(rotaCellKey(cell.dataset.analyst, cell.dataset.date)));
    });
}

/** Empties the cell selection and unmarks the grid. */
function clearRotaSelection() {
    if (!rotaSelection.size) return;
    rotaSelection.clear();
    applyRotaSelection();
}

/** Every cell between two corners, inclusive - the spreadsheet rectangle. */
function rotaSelectRect(from, to) {
    const r1 = Math.min(+from.dataset.row, +to.dataset.row);
    const r2 = Math.max(+from.dataset.row, +to.dataset.row);
    const c1 = Math.min(+from.dataset.col, +to.dataset.col);
    const c2 = Math.max(+from.dataset.col, +to.dataset.col);

    rotaSelection.clear();
    document.querySelectorAll('#rotaGrid .rota-cell').forEach(cell => {
        const r = +cell.dataset.row, c = +cell.dataset.col;
        if (r >= r1 && r <= r2 && c >= c1 && c <= c2) {
            rotaSelection.add(rotaCellKey(cell.dataset.analyst, cell.dataset.date));
        }
    });
    applyRotaSelection();
}

/** The day cells belonging to a column heading or an analyst name cell. */
function rotaLineCells(head) {
    const sel = head.dataset.date
        ? `.rota-cell[data-date="${head.dataset.date}"]`
        : `.rota-cell[data-analyst="${head.dataset.analyst}"]`;
    return Array.from(document.querySelectorAll('#rotaGrid ' + sel));
}

/** Removes the column / row highlight. */
function rotaHoverClear() {
    rotaHoverKey = '';
    document.querySelectorAll('#rotaGrid .line-hover').forEach(el => el.classList.remove('line-hover'));
}

/** Light up the whole column or row under the pointer. Pass null to unlight. */
function rotaHoverSet(head) {
    const key = !head ? ''
        : (head.dataset.date ? 'c' + head.dataset.date : 'r' + head.dataset.analyst);
    if (key === rotaHoverKey) return;
    rotaHoverClear();
    if (!head) return;
    rotaHoverKey = key;
    head.classList.add('line-hover');
    rotaLineCells(head).forEach(el => el.classList.add('line-hover'));
}

(function wireRotaSelection() {
    const grid = document.getElementById('rotaGrid');
    if (!grid) return;

    grid.addEventListener('mousedown', function (e) {
        rotaSuppressClick = false;
        if (e.button !== 0) return;
        const cell = e.target.closest('.rota-cell');
        if (!cell) return;

        if (e.ctrlKey || e.metaKey) {
            const key = rotaCellKey(cell.dataset.analyst, cell.dataset.date);
            if (rotaSelection.has(key)) rotaSelection.delete(key);
            else rotaSelection.add(key);
            applyRotaSelection();
            rotaSuppressClick = true;
            e.preventDefault();
            return;
        }

        rotaDragAnchor = cell;
        rotaDragMoved = false;
    });

    grid.addEventListener('mouseover', function (e) {
        rotaHoverSet(e.target.closest('.rota-line-head'));

        if (!rotaDragAnchor) return;
        const cell = e.target.closest('.rota-cell');
        if (!cell || cell === rotaDragAnchor) return;
        rotaDragMoved = true;
        rotaSelectRect(rotaDragAnchor, cell);
    });

    // Leaving the grid unlights the hovered line - unless its menu is open,
    // in which case the pointer is ON that menu and the line it is about to
    // paste into needs to stay visible.
    grid.addEventListener('mouseleave', function () {
        if (!rotaCtxLine) rotaHoverClear();
    });

    // On the document, not the grid: a drag that runs off the edge still ends.
    document.addEventListener('mouseup', function () {
        if (rotaDragMoved) rotaSuppressClick = true;
        rotaDragAnchor = null;
        rotaDragMoved = false;
    });

    // Capture phase, so this runs BEFORE the cell's own onclick and can stop
    // the event reaching it. A click that finished a drag, or picked a cell
    // with ctrl held, must not also open the entry editor.
    grid.addEventListener('click', function (e) {
        if (rotaSuppressClick) {
            rotaSuppressClick = false;
            e.stopPropagation();
            e.preventDefault();
            return;
        }
        if (e.target.closest('.rota-cell')) clearRotaSelection();
    }, true);
})();

// ---- The cell menu ----------------------------------------------------

/**
 * Opens the right-click menu on a rota cell, aimed at the selection when the cell is inside one,
 * otherwise at that cell alone.
 *
 * Sets up Copy, Clear and Paste for what is on the clipboard; a copied day or analyst line cannot be
 * pasted onto cells, and Paste says why.
 */
function openRotaCellMenu(event, cell) {
    event.preventDefault();
    rotaCtxCell = cell;
    rotaCtxLine = null;

    const analystId = cell.dataset.analyst;
    const date      = cell.dataset.date;
    const entry     = rotaEntryAt(analystId, date);
    const analyst   = rotaAnalysts.find(a => a.id == analystId);

    // Right-clicking inside a selection aims at the selection; right-clicking
    // outside one means you have moved on, so the selection goes. Keeping it
    // would leave a paste pointed somewhere other than where you clicked.
    const inSelection = rotaSelection.size > 1 && rotaSelection.has(rotaCellKey(analystId, date));
    if (!inSelection) clearRotaSelection();

    document.getElementById('rotaCtxHeader').textContent = inSelection
        ? t('tickets.rota.ctx.cells_selected', { count: rotaSelection.size })
        : (analyst ? analyst.full_name : '') + ' — ' + rotaDateLabel(date);

    // Copy has no meaning across a selection - "copy WHAT, exactly" has no
    // answer when the cells hold different shifts. One shift is what a cell
    // holds, so Copy stays a single-cell action.
    document.getElementById('rotaCtxCopy').style.display = (entry && !inSelection) ? '' : 'none';

    document.getElementById('rotaCtxCopyLabel').textContent = t('tickets.rota.ctx.copy_cell');

    if (inSelection) {
        rotaSetClearItem(rotaSelectionTargets());
    } else {
        document.getElementById('rotaCtxClear').style.display = entry ? '' : 'none';
        document.getElementById('rotaCtxClearLabel').textContent = t('tickets.rota.ctx.clear_cell');
    }

    if (rotaLineClipboard) {
        // A whole day or a whole week cannot be laid over one cell, or over a
        // block of them. Say so where the click was, rather than offering a
        // Paste that would have to guess.
        rotaSetPasteBlocked(t('tickets.rota.ctx.paste_line_nowhere'),
            rotaLineClipboard.kind === 'col'
                ? t('tickets.rota.ctx.paste_col_onto_cell_why')
                : t('tickets.rota.ctx.paste_row_onto_cell_why'));
    } else if (inSelection) {
        rotaSetPasteItems(rotaSelectionTargets());
    } else {
        rotaSetPasteItem(t('tickets.rota.ctx.paste_cell') + (rotaCellClipboard ? ' — ' + rotaCellClipboard.shift_name : ''));
        rotaHidePasteEmpty();
    }

    rotaPositionMenu(event);
    return false;
}

/**
 * The same menu, opened on a column heading or an analyst's name: copy the
 * whole line, paste one onto it, or empty it.
 */
function openRotaLineMenu(event, head, kind) {
    event.preventDefault();

    const cells = rotaLineCells(head);
    if (!cells.length) return false;

    rotaCtxCell = null;
    clearRotaSelection();

    const label = kind === 'col'
        ? rotaDateLabel(head.dataset.date)
        : ((rotaAnalysts.find(a => a.id == head.dataset.analyst) || {}).full_name || '');

    rotaCtxLine = {
        kind: kind,
        label: label,
        date: head.dataset.date || null,
        analystId: head.dataset.analyst || null,
        targets: cells.map(c => ({ analyst_id: c.dataset.analyst, rota_date: c.dataset.date })),
    };

    // The pointer is about to leave the grid for the menu, which would unlight
    // the line. Setting it here, with rotaCtxLine already assigned, is what
    // keeps the target visible for as long as the menu offering it is open.
    rotaHoverSet(head);

    document.getElementById('rotaCtxHeader').textContent = label;

    // Copy the line, if there is anything on it to copy.
    const copyBtn = document.getElementById('rotaCtxCopy');
    const filled = rotaCtxLine.targets.filter(tg => rotaEntryAt(tg.analyst_id, tg.rota_date)).length;
    copyBtn.style.display = filled ? '' : 'none';
    document.getElementById('rotaCtxCopyLabel').textContent = filled === 1
        ? t('tickets.rota.ctx.copy_line_one')
        : t('tickets.rota.ctx.copy_line', { count: filled });

    rotaSetLinePasteItem(kind, label);
    rotaSetClearItem(rotaCtxLine.targets);

    rotaPositionMenu(event);
    return false;
}

/**
 * What Paste offers on a column heading or an analyst's name, which depends
 * on what is actually on the clipboard:
 *
 *   - a LINE of the SAME kind  -> paste it, replacing this one
 *   - a LINE of the OTHER kind -> 🔑 REFUSED, and the item says why (Ed).
 *     A day is a shift per analyst and a week is a shift per day; there is
 *     no honest way to lay one over the other, and silently doing something
 *     plausible instead is worse than saying no.
 *   - one SHIFT               -> stamp it into every cell, or the empty ones
 *   - nothing                 -> "Nothing copied yet"
 */
function rotaSetLinePasteItem(kind, label) {
    const pasteBtn = document.getElementById('rotaCtxPaste');
    const pasteLbl = document.getElementById('rotaCtxPasteLabel');

    if (!rotaLineClipboard) {
        rotaSetPasteItems(rotaCtxLine.targets);
        return;
    }

    const pasteLabel = t('tickets.rota.ctx.paste_line', { source: rotaLineClipboard.label });

    if (rotaLineClipboard.kind !== kind) {
        rotaSetPasteBlocked(
            kind === 'col' ? t('tickets.rota.ctx.paste_row_onto_col') : t('tickets.rota.ctx.paste_col_onto_row'),
            kind === 'col' ? t('tickets.rota.ctx.paste_row_onto_col_why') : t('tickets.rota.ctx.paste_col_onto_row_why'));
        return;
    }

    // Pasting a line back onto itself is a no-op dressed as an action.
    const same = kind === 'col'
        ? rotaLineClipboard.sourceDate === rotaCtxLine.date
        : rotaLineClipboard.sourceAnalyst == rotaCtxLine.analystId;
    if (same) {
        rotaSetPasteBlocked(pasteLabel, t('tickets.rota.ctx.paste_same_line'));
        return;
    }

    rotaPasteBlockedReason = null;
    pasteBtn.disabled = false;
    pasteBtn.style.opacity = '';
    pasteBtn.title = '';
    pasteLbl.textContent = pasteLabel;
    rotaHidePasteEmpty();
}

/**
 * Paste is always listed, but says why it cannot be used rather than sitting
 * there as a dead option that appears to do nothing.
 */
let rotaPasteBlockedReason = null;

/**
 * Paste is offered but refused, with the reason in a tooltip AND in a toast
 * if you click it anyway (Ed asked for the tooltip).
 *
 * Deliberately NOT `disabled`: a disabled button swallows mouse events in
 * several browsers, so the tooltip explaining the refusal would be the one
 * thing you could not see.
 */
function rotaSetPasteBlocked(label, why) {
    const pasteBtn = document.getElementById('rotaCtxPaste');
    rotaPasteBlockedReason = why;
    pasteBtn.disabled = false;
    pasteBtn.style.opacity = '0.55';
    pasteBtn.title = why;
    document.getElementById('rotaCtxPasteLabel').textContent = label;
    rotaHidePasteEmpty();
}

/**
 * Sets the Paste item to the given label when a shift is on the cell clipboard, otherwise disables it
 * as "Nothing copied yet".
 */
function rotaSetPasteItem(label) {
    const pasteBtn = document.getElementById('rotaCtxPaste');
    const pasteLbl = document.getElementById('rotaCtxPasteLabel');
    pasteBtn.title = '';
    rotaPasteBlockedReason = null;
    if (rotaCellClipboard) {
        pasteBtn.disabled = false;
        pasteBtn.style.opacity = '';
        pasteLbl.textContent = label;
    } else {
        pasteBtn.disabled = true;
        pasteBtn.style.opacity = '0.5';
        pasteLbl.textContent = t('tickets.rota.ctx.nothing_copied');
    }
}

/** Hides the "paste into empty cells only" item. */
function rotaHidePasteEmpty() {
    document.getElementById('rotaCtxPasteEmpty').style.display = 'none';
}

/**
 * The two ways to paste into several cells, offered side by side (Ed).
 *
 * The choice between filling everything and filling only the gaps belongs in
 * the menu, next to the thing it is a choice about, rather than in a modal
 * that appears after you have already committed to pasting. The second item
 * only appears when there is genuinely a mix - with nothing in the way, or
 * with nothing empty, it would be the same action under two names.
 */
function rotaSetPasteItems(targets) {
    const filled = targets.filter(tg => rotaEntryAt(tg.analyst_id, tg.rota_date)).length;
    const empty  = targets.length - filled;

    rotaSetPasteItem(t('tickets.rota.ctx.paste_into', { count: targets.length }));

    const emptyBtn = document.getElementById('rotaCtxPasteEmpty');
    if (!rotaCellClipboard || !filled || !empty) {
        emptyBtn.style.display = 'none';
        return;
    }
    emptyBtn.style.display = '';
    document.getElementById('rotaCtxPasteEmptyLabel').textContent = empty === 1
        ? t('tickets.rota.ctx.paste_empty_one')
        : t('tickets.rota.ctx.paste_empty', { count: empty });
}

/**
 * Clear, across a column, a row or a selection (Ed). It counts the shifts
 * that are actually there rather than the cells it was pointed at - "Clear 3
 * shifts" on a column of seven is the number that matters, and offering
 * Clear at all on a line with nothing in it is offering to do nothing.
 */
function rotaSetClearItem(targets) {
    const filled = targets.filter(tg => rotaEntryAt(tg.analyst_id, tg.rota_date)).length;
    const clearBtn = document.getElementById('rotaCtxClear');
    if (!filled) {
        clearBtn.style.display = 'none';
        return;
    }
    clearBtn.style.display = '';
    document.getElementById('rotaCtxClearLabel').textContent = filled === 1
        ? t('tickets.rota.ctx.clear_one')
        : t('tickets.rota.ctx.clear_many', { count: filled });
}

/** Show at the pointer, then nudge back inside the viewport. A cell in the
 *  last column sits at the right-hand edge and the menu would open off screen. */
function rotaPositionMenu(event) {
    const menu = document.getElementById('rotaContextMenu');
    menu.classList.add('active');
    const r = menu.getBoundingClientRect();
    const x = Math.min(event.clientX, window.innerWidth  - r.width  - 8);
    const y = Math.min(event.clientY, window.innerHeight - r.height - 8);
    menu.style.left = Math.max(8, x) + 'px';
    menu.style.top  = Math.max(8, y) + 'px';
}

/**
 * Closes the rota right-click menu and forgets the cell or line it was opened on, clearing the line
 * highlight.
 */
function closeRotaCellMenu() {
    const menu = document.getElementById('rotaContextMenu');
    if (menu) menu.classList.remove('active');
    rotaCtxCell = null;
    if (rotaCtxLine) {
        rotaCtxLine = null;
        rotaHoverClear();
    }
}

document.addEventListener('click', function (e) {
    if (!e.target.closest('#rotaContextMenu')) closeRotaCellMenu();
});
document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    closeRotaCellMenu();
    clearRotaSelection();
});

/**
 * Runs a right-click menu choice (copy, clear, paste, paste into empty cells) on the line, the
 * selection or the cell the menu was opened on.
 *
 * A Paste the menu already refused shows its reason as a toast instead.
 */
async function rotaCtxAction(action) {
    const cell = rotaCtxCell;
    const line = rotaCtxLine;
    const blocked = rotaPasteBlockedReason;
    closeRotaCellMenu();

    // Clicking a Paste that the menu already said no to repeats the reason
    // rather than doing nothing, for anybody who missed the tooltip.
    if ((action === 'paste' || action === 'paste_empty') && blocked) {
        showToast(blocked, 'error');
        return;
    }

    if (line) {
        if (action === 'clear')      await rotaClearCells(line.targets, line.label);
        else if (action === 'copy')  copyRotaLine(line);
        else if (rotaLineClipboard)  await pasteRotaLine(line);
        else                         await rotaPasteInto(line.targets, action === 'paste_empty' ? 'empty' : 'all');
        return;
    }
    if (!cell) return;

    const analystId = cell.dataset.analyst;
    const date      = cell.dataset.date;
    const entry     = rotaEntryAt(analystId, date);

    if (action === 'copy') {
        if (!entry) return;
        rotaSetClipboard({
            shift_id:    entry.shift_id,
            location_id: entry.location_id,
            is_on_call:  entry.is_on_call == 1 ? 1 : 0,
            shift_name:  entry.shift_name,
        }, null);
        showToast(t('tickets.rota.copy.cell_copied', { shift: entry.shift_name }), 'success');
        return;
    }

    if (action === 'clear') {
        // Across a selection this is the many-cell clear; on one cell it is
        // the single delete it has always been.
        if (rotaSelection.size > 1 && rotaSelection.has(rotaCellKey(analystId, date))) {
            await rotaClearCells(rotaSelectionTargets(), t('tickets.rota.ctx.cells_selected', { count: rotaSelection.size }));
            return;
        }
        if (!entry) return;
        const ok = await showConfirm({ title: 'Confirm', message: t('tickets.rota.delete_confirm'), okLabel: 'OK', okClass: 'danger' });
        if (!ok) return;
        await rotaPost('delete_rota_entry.php', { id: parseInt(entry.id) }, 'tickets.rota.toasts.deleted', 'tickets.rota.toasts.delete_failed');
        return;
    }

    if (action === 'paste' || action === 'paste_empty') {
        // A selection the menu was opened inside is the target; otherwise the
        // one cell that was right-clicked.
        const targets = (rotaSelection.size > 1 && rotaSelection.has(rotaCellKey(analystId, date)))
            ? rotaSelectionTargets()
            : [{ analyst_id: analystId, rota_date: date }];
        await rotaPasteInto(targets, action === 'paste_empty' ? 'empty' : 'all');
    }
}

/**
 * Paste the copied shift into one cell, or into many.
 *
 * `mode` is already decided by the time we get here - the menu offered both
 * ways in plain words - so the only thing left to ask about is overwriting,
 * and only when it is actually going to happen. The confirmation differs
 * between one cell and many because the useful sentence differs: for one it
 * can name both shifts and the person, for thirty it has to be a count.
 */
async function rotaPasteInto(targets, mode) {
    if (!rotaCellClipboard) { showToast(t('tickets.rota.copy.nothing_to_paste'), 'error'); return; }
    if (!targets.length) return;

    const filled = targets.filter(tg => rotaEntryAt(tg.analyst_id, tg.rota_date));

    if (targets.length === 1) {
        const analystId = targets[0].analyst_id;
        const date      = targets[0].rota_date;
        if (filled.length) {
            const analyst = rotaAnalysts.find(a => a.id == analystId);
            const ok = await showConfirm({
                title: t('tickets.rota.copy.cell_confirm_title'),
                message: t('tickets.rota.copy.cell_confirm', {
                    analyst:  analyst ? analyst.full_name : '',
                    existing: rotaEntryAt(analystId, date).shift_name,
                    date:     rotaDateLabel(date),
                    incoming: rotaCellClipboard.shift_name,
                }),
                okLabel: t('tickets.rota.ctx.paste_cell'),
                okClass: 'primary',
            });
            if (!ok) return;
        }

        // No id: save_rota_entry.php upserts on the (analyst, date) unique key,
        // so this is the same call whether the cell was empty or not.
        await rotaPost('save_rota_entry.php', {
            analyst_id:  analystId,
            rota_date:   date,
            shift_id:    rotaCellClipboard.shift_id,
            location_id: rotaCellClipboard.location_id,
            is_on_call:  rotaCellClipboard.is_on_call,
        }, 'tickets.rota.copy.pasted', 'tickets.rota.copy.paste_failed');
        return;
    }

    // Nothing is lost by filling the gaps, and nothing is lost when there was
    // nothing there. Only an overwrite that will actually overwrite is worth
    // stopping for, and the message says how many shifts go.
    if (mode === 'all' && filled.length) {
        const ok = await showConfirm({
            title: t('tickets.rota.copy.overwrite_title'),
            message: t('tickets.rota.copy.overwrite_confirm', {
                shift: rotaCellClipboard.shift_name,
                total: targets.length,
                filled: filled.length,
            }),
            okLabel: t('tickets.rota.copy.overwrite_ok'),
            okClass: 'danger',
        });
        if (!ok) return;
    }

    try {
        const res = await fetch(ROTA_API + 'paste_rota_cells.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                shift_id:    rotaCellClipboard.shift_id,
                location_id: rotaCellClipboard.location_id,
                is_on_call:  rotaCellClipboard.is_on_call,
                mode:        mode,
                targets:     targets,
            }),
        });
        const data = await res.json();
        if (!data.success) {
            showToast(t('tickets.rota.toasts.error', { error: data.error }), 'error');
            return;
        }
        showToast(data.skipped_filled
            ? t('tickets.rota.copy.cells_pasted_some', { written: data.written, skipped: data.skipped_filled })
            : t('tickets.rota.copy.cells_pasted', { count: data.written }), 'success');
        // Never silent. A cell the server refused - a deactivated analyst, a
        // day this grid does not draw - is a cell somebody expected to fill.
        if (data.skipped_invalid > 0) {
            showToast(t('tickets.rota.copy.cells_skipped', { count: data.skipped_invalid }), 'error');
        }
        clearRotaSelection();
        loadRota();
    } catch (e) {
        showToast(t('tickets.rota.copy.paste_failed'), 'error');
    }
}


/** POST, toast the outcome, reload the grid. Shared by every write above. */
async function rotaPost(endpoint, body, okKey, failKey) {
    try {
        const res = await fetch(ROTA_API + endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body),
        });
        const data = await res.json();
        if (data.success) {
            showToast(t(okKey), 'success');
            loadRota();
            return data;
        }
        showToast(t('tickets.rota.toasts.error', { error: data.error }), 'error');
    } catch (e) {
        showToast(t(failKey), 'error');
    }
    return null;
}

// ---- A whole column or a whole row (Ed) -------------------------------

/**
 * Copy every shift on a line.
 *
 * 🔑 What each entry is keyed BY is the whole design, and it differs by kind
 * for the same reason the week clipboard stores a day offset rather than a
 * date: the key has to be the part that survives the move.
 *
 *   - a COLUMN is one day across the team, so each entry keeps its ANALYST
 *     and lands on the same person on a different day;
 *   - a ROW is one analyst across the week, so each entry keeps its DAY
 *     OFFSET and lands on the same day for a different person.
 *
 * Which is also why one cannot be pasted onto the other: their keys are not
 * the same kind of thing.
 */
function copyRotaLine(line) {
    const entries = [];
    line.targets.forEach(tg => {
        const entry = rotaEntryAt(tg.analyst_id, tg.rota_date);
        if (!entry) return;
        const base = {
            shift_id:    entry.shift_id,
            location_id: entry.location_id,
            is_on_call:  entry.is_on_call == 1 ? 1 : 0,
        };
        if (line.kind === 'col') {
            base.analyst_id = entry.analyst_id;
        } else {
            base.day_offset = Math.round(
                (new Date(tg.rota_date + 'T00:00:00') - new Date(currentWeekStart + 'T00:00:00')) / 86400000);
        }
        entries.push(base);
    });

    if (!entries.length) {
        showToast(t('tickets.rota.copy.line_empty'), 'error');
        return;
    }

    rotaSetClipboard(null, {
        kind: line.kind,
        label: line.label,
        count: entries.length,
        entries: entries,
        sourceDate: line.date,
        sourceAnalyst: line.analystId,
    });
    showToast(line.kind === 'col'
        ? t('tickets.rota.copy.col_copied', { count: entries.length, date: line.label })
        : t('tickets.rota.copy.row_copied', { count: entries.length, analyst: line.label }), 'success');
}

/**
 * Paste a copied line over another one of the same kind.
 *
 * Like a week paste this REPLACES - a merge would leave a day matching
 * neither the one you copied nor the one you had - so the confirm names how
 * much is going as well as how much is arriving.
 */
async function pasteRotaLine(line) {
    if (!rotaLineClipboard || rotaLineClipboard.kind !== line.kind) return;

    const existing = line.targets.filter(tg => rotaEntryAt(tg.analyst_id, tg.rota_date)).length;

    const ok = await showConfirm({
        title: t('tickets.rota.copy.line_confirm_title'),
        message: existing
            ? t('tickets.rota.copy.line_confirm', {
                source: rotaLineClipboard.label, target: line.label,
                incoming: rotaLineClipboard.count, existing: existing,
            })
            : t('tickets.rota.copy.line_confirm_empty', {
                source: rotaLineClipboard.label, target: line.label,
                incoming: rotaLineClipboard.count,
            }),
        okLabel: t('tickets.rota.ctx.paste_cell'),
        okClass: existing ? 'danger' : 'primary',
    });
    if (!ok) return;

    try {
        const res = await fetch(ROTA_API + 'paste_rota_line.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                kind:       line.kind,
                week_start: currentWeekStart,
                date:       line.date,
                analyst_id: line.analystId,
                entries:    rotaLineClipboard.entries,
            }),
        });
        const data = await res.json();
        if (!data.success) {
            showToast(t('tickets.rota.toasts.error', { error: data.error }), 'error');
            return;
        }
        showToast(t('tickets.rota.copy.line_pasted', { written: data.written, removed: data.removed }), 'success');
        // Never silent: a shift retired between the copy and the paste drops
        // those rows, and losing somebody's shift quietly is the worst thing
        // this feature could do.
        if (data.skipped > 0) {
            showToast(t('tickets.rota.copy.week_skipped', { count: data.skipped }), 'error');
        }
        loadRota();
    } catch (e) {
        showToast(t('tickets.rota.copy.paste_failed'), 'error');
    }
}

/**
 * Empty a column, a row or a selection (Ed).
 *
 * This one only ever destroys, so the confirm always appears and names both
 * what is being emptied and how many shifts go with it. `target` is the day,
 * the analyst's name, or "9 cells selected" - whichever the menu was opened
 * on, so the sentence matches the gesture that produced it.
 */
async function rotaClearCells(targets, target) {
    const filled = targets.filter(tg => rotaEntryAt(tg.analyst_id, tg.rota_date));
    if (!filled.length) return;

    const ok = await showConfirm({
        title: t('tickets.rota.copy.clear_title'),
        message: filled.length === 1
            ? t('tickets.rota.copy.clear_confirm_one', { target: target })
            : t('tickets.rota.copy.clear_confirm', { target: target, count: filled.length }),
        okLabel: t('common.delete'),
        okClass: 'danger',
    });
    if (!ok) return;

    try {
        const res = await fetch(ROTA_API + 'clear_rota_cells.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ targets: targets }),
        });
        const data = await res.json();
        if (!data.success) {
            showToast(t('tickets.rota.toasts.error', { error: data.error }), 'error');
            return;
        }
        showToast(t('tickets.rota.copy.cleared', { count: data.removed }), 'success');
        clearRotaSelection();
        loadRota();
    } catch (e) {
        showToast(t('tickets.rota.toasts.delete_failed'), 'error');
    }
}

// ---- The whole week ---------------------------------------------------

/** The entries the grid is actually drawing, as {offset, entry} pairs. */
function rotaVisibleEntries(weekStart) {
    const numDays = includeWeekends ? 7 : 5;
    const start = new Date(weekStart + 'T00:00:00');
    const out = [];
    rotaEntries.forEach(e => {
        const offset = Math.round((new Date(e.rota_date + 'T00:00:00') - start) / 86400000);
        if (offset < 0 || offset >= numDays) return;               // a hidden weekend day
        if (!rotaAnalysts.some(a => a.id == e.analyst_id)) return;  // a deactivated analyst
        out.push({ offset: offset, entry: e });
    });
    return out;
}

/**
 * Copies the visible week's entries to the week clipboard as day offsets, so they can be pasted onto a
 * different week.
 */
function copyRotaWeek() {
    // Stored as a DAY OFFSET, not a date. The whole point is to land these on a
    // different week, and the offset is the only part that survives the move.
    const entries = rotaVisibleEntries(currentWeekStart).map(x => ({
        analyst_id:  x.entry.analyst_id,
        day_offset:  x.offset,
        shift_id:    x.entry.shift_id,
        location_id: x.entry.location_id,
        is_on_call:  x.entry.is_on_call == 1 ? 1 : 0,
    }));

    if (!entries.length) {
        showToast(t('tickets.rota.copy.week_empty'), 'error');
        return;
    }

    rotaWeekClipboard = { week_start: currentWeekStart, entries: entries, count: entries.length };
    updatePasteWeekButton();
    showToast(t('tickets.rota.copy.week_copied', { count: entries.length }), 'success');
}

/**
 * Shows the Paste week button, with the copied week in its tooltip, only while a week is on the
 * clipboard.
 */
function updatePasteWeekButton() {
    const btn = document.getElementById('rotaPasteWeekBtn');
    if (!btn) return;
    if (!rotaWeekClipboard) { btn.style.display = 'none'; return; }
    btn.style.display = '';
    btn.textContent = t('tickets.rota.copy.paste_week_btn');
    btn.title = t('tickets.rota.copy.clipboard_week', { date: rotaDateLabel(rotaWeekClipboard.week_start) });
}

/**
 * Pastes the copied week onto the week on screen through paste_rota_week.php, after a confirmation
 * that counts the entries it replaces.
 *
 * Warns when rows were skipped because their shift no longer exists.
 */
async function pasteRotaWeek() {
    if (!rotaWeekClipboard) return;

    if (rotaWeekClipboard.week_start === currentWeekStart) {
        showToast(t('tickets.rota.copy.week_same'), 'error');
        return;
    }

    // How much is in the week being pasted over, counted exactly the way the
    // copy counts — so the number in the warning is the number that disappears.
    const existing = rotaVisibleEntries(currentWeekStart).length;

    const ok = await showConfirm({
        title: existing ? t('tickets.rota.copy.week_confirm_title') : t('tickets.rota.copy.paste_week_btn'),
        message: existing
            ? t('tickets.rota.copy.week_confirm', { existing: existing, incoming: rotaWeekClipboard.count })
            : t('tickets.rota.copy.week_confirm_empty', { incoming: rotaWeekClipboard.count }),
        okLabel: t('tickets.rota.copy.paste_week_btn'),
        okClass: existing ? 'danger' : 'primary',
    });
    if (!ok) return;

    try {
        const res = await fetch(ROTA_API + 'paste_rota_week.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ week_start: currentWeekStart, entries: rotaWeekClipboard.entries }),
        });
        const data = await res.json();
        if (!data.success) {
            showToast(t('tickets.rota.toasts.error', { error: data.error }), 'error');
            return;
        }
        showToast(t('tickets.rota.copy.week_pasted', { written: data.written, removed: data.removed }), 'success');
        // Never silent: a shift retired between the copy and the paste drops
        // those rows, and a paste that quietly loses somebody's shift is the
        // worst thing this feature could do.
        if (data.skipped > 0) {
            showToast(t('tickets.rota.copy.week_skipped', { count: data.skipped }), 'error');
        }
        loadRota();
    } catch (e) {
        showToast(t('tickets.rota.copy.paste_failed'), 'error');
    }
}
