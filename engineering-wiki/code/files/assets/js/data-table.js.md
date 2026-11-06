# assets/js/data-table.js

> JavaScript - 1320 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Saved Table Views Developer Guide](https://github.com/edmozley/freeitsm/wiki/Saved-Table-Views-Developer-Guide)

FreeITSM shared data-table engine

One implementation of the full-screen, Excel-style table view used by the
asset, tasks, calendar and change-management modules. Everything that used to
be copy-pasted per module lives here: column show/hide + drag-reorder
(persisted per-user via user_preferences), click-to-sort, search across
visible columns, per-column tickbox filter, CSV (and optional PDF) export,
and optional inline cell editing.

A module supplies only what's actually module-specific via `createDataTable()`:
  - its COLUMNS catalogue (the single source of truth for the grid)
  - how to `load()` its rows
  - the accent colour + a preference key + the export filename
  - optionally: onSaveCell (inline editing), onRowClick, getLookups, pdf

Styling is shared (assets/css/data-table.css); the accent is injected at
runtime onto :root as --dt-accent so even body-appended popovers pick it up.

Usage:
  const table = createDataTable({ ...config });
  // table.reload(), table.render(), table.findRow(id), table.getViewRows()

Column shape:
  { key, label, type: 'string'|'number'|'date', defaultVisible, defaultOrder,
    value?: row => comparable,   // sort key (default row[key])
    display?: row => string,     // shown / searched / filtered / exported
    format?: raw => string,      // legacy single-arg formatter (asset compat)
    editable?: false | { kind, ... } }

editable kinds: 'text' | 'date' | 'datetime' | 'bool' |
  { kind:'lookup', listKey, valueKey, labelKey, allowNull?, nullLabel?, colourKey? }

## Functions

| Function | Line | What it does |
|---|--:|---|
| `createDataTable(config)` | 57 | Builds one data table from a module's config and wires it up when the page has loaded. |
| `boot()` | 87 | Starts the table on DOMContentLoaded: columns, saved preferences, default view, rows, toolbar. |
| `applyDefaultView()` | 112 | Open the table with whichever view this analyst made their default. |
| `byId(id)` | 125 | Shorthand for document.getElementById. |
| `reload()` | 129 | Fetches the rows again through the module's `load()` and redraws; a failed load shows an empty table. |
| `applyColumnConfig(cols)` | 150 | Restore column order and visibility from a saved list. |
| `applySortConfig(s)` | 168 | Restores the sort from a saved {k, d} pair, ignoring a column that no longer exists. |
| `loadPreferences()` | 179 | Reads this analyst's saved column layout and sort for the table from their user preferences. |
| `savePreferences()` | 196 | Saves the column layout and sort to the analyst's user preferences, 400ms after the last change. |
| `vt(key, fallback, params)` | 242 | Translate, falling back to English — the engine has no i18n of its own. |
| `fill(s)` | 246 | Replaces {name} placeholders in a string with the matching params, leaving unknown ones as they are. |
| `captureViewConfig()` | 256 | The current state, as a view's config. Filters are Sets, so unpack them. |
| `applyViewConfig(cfg)` | 275 | Put a saved config back on the table. |
| `viewsFetch(path, opts)` | 294 | Calls one of the table-views API endpoints and returns its JSON. |
| `loadViews(q)` | 300 | Every view this analyst can see on this table, plus their teams and default. |
| `applyViewById(id, rows)` | 314 | Applies a saved view from an already-loaded list, marks it as the active view and stamps it as used. |
| `markActiveView(name)` | 334 | The toolbar button shows which view is on, or that none is. |
| `closeViewsModal()` | 346 | Closes the saved views library and drops its Escape key listener. |
| `viewsEsc(e)` | 351 | Closes the saved views library when Escape is pressed. |
| `openViewsLibrary()` | 358 | Opens the saved views library - a modal with a search box, a list/cards layout switch and the views. |
| `refreshViewsList()` | 418 | Reloads the views matching the library's search and draws them, wiring the default, edit and delete buttons. |
| `renderViewEntry(v)` | 457 | Returns the HTML for one view in the library: name, badges, description, owner, dates and its action buttons. |
| `day(s)` | 466 | Dates are plain calendar timestamps from the server. Show the day, not the minute: on a library the useful question is "is this stale?", and a time of day is noise against that. |
| `openViewEditor(view)` | 508 | The save / edit dialog. |
| `syncTeam()` | 562 | Shows the team picker only while the "A team" visibility choice is ticked. |
| `deleteView(id)` | 598 | Deletes a saved view, clears the toolbar label if it was the active one, and refreshes the library. |
| `setDefaultView(id)` | 609 | Sets or clears (id null) the view this table opens with for the analyst, then refreshes the library. |
| `alertViews(msg)` | 620 | Errors go through the host page's toast when it has one. |
| `colValue(col, row)` | 628 | Returns a cell's raw value for sorting - the column's `value()` when it has one, otherwise row[key]. |
| `colDisplay(col, row)` | 637 | Returns a cell's text as shown, searched, filtered and exported. |
| `wireToolbar()` | 650 | Wires the toolbar (search, Columns, Reset, Views, Save view, CSV, PDF) and popover closing. |
| `visibleColumns()` | 710 | Returns the column definitions that are switched on, in their current order. |
| `render()` | 715 | Redraws the header and the body. |
| `renderHead()` | 720 | Draws the header row and wires click-to-sort, the filter buttons and drag-to-reorder on each column heading. |
| `renderBody()` | 780 | Draws the filtered, sorted rows and the row count, then wires inline editing and row clicks. |
| `renderCell(row, col)` | 810 | Returns one cell's HTML - plain text, or an input or select for an editable column. |
| `renderLookupCell(row, col, id)` | 844 | Returns an editable lookup cell: a select built from the module's lookup list, with an optional colour swatch. |
| `wireEditControls(body)` | 868 | Saves an inline-edited cell when its input or select changes. |
| `normalizeValue(col, raw)` | 882 | Turns an edit control's value into what the module saves (1/0, null, a DB datetime, an int id). |
| `saveCell(row, col, raw)` | 899 | Saves one edited cell through the module's onSaveCell and shows a toast. |
| `refreshSwatch(row, col)` | 912 | Updates a lookup cell's colour swatch after a save, without redrawing the table. |
| `wireRowClicks(body, rows)` | 927 | Calls the module's onRowClick when a row is clicked, but not when the click lands on an edit control. |
| `applyFiltersAndSort()` | 944 | Returns the rows that pass the column filters and the search, sorted by the current sort column. |
| `toggleSort(key)` | 1000 | Sorts by a column, flipping the direction when it is already the sort column, and saves the preference. |
| `reorderColumn(fromKey, toKey)` | 1008 | Moves a column to where another one is, redraws and saves the preference. |
| `openFilterDropdown(colKey, anchorEl)` | 1025 | Opens the tickbox filter for one column, listing each distinct value with its count. |
| `openColumnsDrawer(anchorEl)` | 1113 | Opens the Columns popover, where columns are ticked on or off and dragged into order; each change is saved. |
| `positionPopover(pop, anchorEl)` | 1175 | Places a popover below its anchor, kept inside the window's width. |
| `closePopover()` | 1188 | Removes the open filter or columns popover, if there is one. |
| `exportCSV()` | 1195 | Downloads the filtered, sorted rows (visible columns only) as a CSV, with a BOM so Excel reads UTF-8. |
| `exportPDF()` | 1216 | Downloads the filtered, sorted rows as a landscape A4 PDF (jsPDF + autoTable), with optional logo. |
| `csvCell(v)` | 1265 | Quotes a CSV value when it holds a quote, comma or newline, doubling any quotes. |
| `formatDateStr()` | 1274 | Returns today's date as YYYY-MM-DD for export file names. |
| `toLocalInput(raw)` | 1281 | Turns a DB 'YYYY-MM-DD HH:MM:SS' value into the 'YYYY-MM-DDTHH:MM' a datetime-local input wants. |
| `fromLocalInput(v)` | 1286 | Turns a datetime-local input's value back into a DB 'YYYY-MM-DD HH:MM:SS' string. |
| `esc(s)` | 1294 | Escapes a value for use in HTML text or an attribute. |
| `cssEsc(s)` | 1304 | For querySelector attribute values (data-id) — escape quotes/backslashes. |

## Loaded by

- [asset-management/table.php](/code/files/asset-management/table.php.md)
- [calendar/table/index.php](/code/files/calendar/table/index.php.md)
- [change-management/table.php](/code/files/change-management/table.php.md)
- [domains/table/index.php](/code/files/domains/table/index.php.md)
- [tasks/table/index.php](/code/files/tasks/table/index.php.md)

