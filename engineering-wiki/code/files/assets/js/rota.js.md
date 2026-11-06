# assets/js/rota.js

> JavaScript - 1300 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Rota Copy And Paste Developer Deep Dive](https://github.com/edmozley/freeitsm/wiki/Rota-Copy-And-Paste-Developer-Deep-Dive)

Rota Page JS - Weekly staff rota management

## Functions

| Function | Line | What it does |
|---|--:|---|
| `getMonday(d)` | 41 | Returns the Monday of the week holding a date, as YYYY-MM-DD. |
| `formatDate(d)` | 50 | Formats a Date as a local YYYY-MM-DD. |
| `changeWeek(delta)` | 60 | Moves the rota forward or back by a number of weeks and reloads it. |
| `goToThisWeek()` | 68 | Jumps the rota back to the current week and reloads it. |
| `loadRota()` | 79 | Loads the week's analysts, shifts, entries and locations from get_rota.php, then redraws the title and grid. |
| `updateTitle(weekStart, weekEnd)` | 102 | Sets the week title (e.g. "3 - 7 Mar 2026"), ending on Friday unless weekends are included. |
| `renderRotaGrid(weekStart)` | 135 | Rebuilds the rota grid - day headers, one row per analyst, and a cell per day showing the shift, times, location and on-call badges. |
| `fmtTime(t)` | 238 | Trims a HH:MM:SS time to HH:MM. |
| `escapeHtml(str)` | 244 | HTML-escapes a string through a text node; empty values become an empty string. |
| `openRotaEntryModal(analystId, date, entryId)` | 257 | Opens the rota entry dialog for an analyst and date - shift list, location radios and on-call - filled from the entry when editing one. |
| `closeRotaEntryModal()` | 315 | Closes the rota entry dialog. |
| `deleteRotaEntry()` | 353 | Delete entry |
| `rotaSetClipboard(cell, line)` | 406 | One clipboard at a time for the grid (Ed). |
| `rotaEntryAt(analystId, date)` | 412 | Returns the loaded rota entry for an analyst on a date, or null. |
| `rotaDateLabel(date)` | 417 | Format a YYYY-MM-DD for a message, in the analyst's own date arrangement. |
| `rotaCellKey(analystId, date)` | 440 | Returns the selection key for a cell - "analystId\|YYYY-MM-DD". |
| `rotaSelectionTargets()` | 445 | Returns the selected cells as {analyst_id, rota_date} targets. |
| `applyRotaSelection()` | 453 | Marks the grid cells that are in the selection. |
| `clearRotaSelection()` | 461 | Empties the cell selection and unmarks the grid. |
| `rotaSelectRect(from, to)` | 468 | Every cell between two corners, inclusive - the spreadsheet rectangle. |
| `rotaLineCells(head)` | 485 | The day cells belonging to a column heading or an analyst name cell. |
| `rotaHoverClear()` | 493 | Removes the column / row highlight. |
| `rotaHoverSet(head)` | 499 | Light up the whole column or row under the pointer. Pass null to unlight. |
| `openRotaCellMenu(event, cell)` | 581 | Opens the right-click menu on a rota cell, aimed at the selection when the cell is inside one, otherwise at that cell alone. |
| `openRotaLineMenu(event, head, kind)` | 638 | The same menu, opened on a column heading or an analyst's name: copy the whole line, paste one onto it, or empty it. |
| `rotaSetLinePasteItem(kind, label)` | 693 | What Paste offers on a column heading or an analyst's name, which depends on what is actually on the clipboard: |
| `rotaSetPasteBlocked(label, why)` | 742 | Paste is offered but refused, with the reason in a tooltip AND in a toast if you click it anyway (Ed asked for the tooltip). |
| `rotaSetPasteItem(label)` | 756 | Sets the Paste item to the given label when a shift is on the cell clipboard, otherwise disables it as "Nothing copied yet". |
| `rotaHidePasteEmpty()` | 773 | Hides the "paste into empty cells only" item. |
| `rotaSetPasteItems(targets)` | 786 | The two ways to paste into several cells, offered side by side (Ed). |
| `rotaSetClearItem(targets)` | 809 | Clear, across a column, a row or a selection (Ed). It counts the shifts that are actually there rather than the cells it was pointed at - "Clear 3 shifts" on a column of seven is the number that matters, and offering Clear at all on a line with nothing in it is offering to do nothing. |
| `rotaPositionMenu(event)` | 824 | Show at the pointer, then nudge back inside the viewport. A cell in the last column sits at the right-hand edge and the menu would open off screen. |
| `closeRotaCellMenu()` | 838 | Closes the rota right-click menu and forgets the cell or line it was opened on, clearing the line highlight. |
| `rotaCtxAction(action)` | 863 | Runs a right-click menu choice (copy, clear, paste, paste into empty cells) on the line, the selection or the cell the menu was opened on. |
| `rotaPasteInto(targets, mode)` | 934 | Paste the copied shift into one cell, or into many. |
| `rotaPost(endpoint, body, okKey, failKey)` | 1022 | POST, toast the outcome, reload the grid. Shared by every write above. |
| `copyRotaLine(line)` | 1059 | Copy every shift on a line. |
| `pasteRotaLine(line)` | 1103 | Paste a copied line over another one of the same kind. |
| `rotaClearCells(targets, target)` | 1162 | Empty a column, a row or a selection (Ed). |
| `rotaVisibleEntries(weekStart)` | 1198 | The entries the grid is actually drawing, as {offset, entry} pairs. |
| `copyRotaWeek()` | 1215 | Copies the visible week's entries to the week clipboard as day offsets, so they can be pasted onto a different week. |
| `updatePasteWeekButton()` | 1240 | Shows the Paste week button, with the copied week in its tooltip, only while a week is on the clipboard. |
| `pasteRotaWeek()` | 1255 | Pastes the copied week onto the week on screen through paste_rota_week.php, after a confirmation that counts the entries it replaces. |

## API endpoints it calls

- [api/tickets/get_rota.php](/code/files/api/tickets/get_rota.php.md)
- [api/tickets/save_rota_entry.php](/code/files/api/tickets/save_rota_entry.php.md)
- [api/tickets/delete_rota_entry.php](/code/files/api/tickets/delete_rota_entry.php.md)
- [api/tickets/paste_rota_cells.php](/code/files/api/tickets/paste_rota_cells.php.md)
- [api/tickets/paste_rota_line.php](/code/files/api/tickets/paste_rota_line.php.md)
- [api/tickets/clear_rota_cells.php](/code/files/api/tickets/clear_rota_cells.php.md)
- [api/tickets/paste_rota_week.php](/code/files/api/tickets/paste_rota_week.php.md)

## Loaded by

- [tickets/rota.php](/code/files/tickets/rota.php.md)

