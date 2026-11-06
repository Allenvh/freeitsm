# assets/js/change-table.js

> JavaScript - 146 lines

FreeITSM Change Management — table view config

Supplies the change-specific pieces to the shared data-table engine
(assets/js/data-table.js): the COLUMNS catalogue, change loading, and inline
editing for the low-risk list-level fields (priority, impact, type, assignee).

Why only those fields edit inline: the full change save (save.php) rewrites
the whole record, and the list endpoint only returns a summary — so a generic
cell-save would blank out the longtext fields it doesn't carry. Instead, each
edit posts ONE field to a dedicated update_field.php that touches one column
and writes one audit row. Status is intentionally NOT editable here so a cell
can't bypass CAB voting / the approval workflow; the description, test/rollback
plans and risk scoring stay in the full form too. Click any row to open it.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `fmt(raw)` | 29 | Server-stamped UTC timestamp (created / modified) → analyst display zone, in the analyst's chosen date and time format (parseUTCDate / fmtDateTime from assets/js/tz.js, loaded before this file). Only the display text is zoned and formatted; the column's `value` (used for sorting) stays the raw UTC string, so neither the zone nor the format can reorder the table. |
| `fmtNaive(raw)` | 42 | NAIVE wall-clock scheduling datetime (planned work start / end) — shown exactly as typed for every analyst, NO zone conversion (parseNaiveDate from tz.js). Same shape as `fmt()`. |
| `T(k)` | 50 | Translates a key under change-management.table, or returns the key when i18n.js is not loaded. |
| `loadLookups()` | 94 | Loads the active priorities, impacts and types plus the analyst list that the inline edit drop-downs offer. |

## API endpoints it calls

- [api/change-management/get_change_priorities.php](/code/files/api/change-management/get_change_priorities.php.md)
- [api/change-management/get_change_impacts.php](/code/files/api/change-management/get_change_impacts.php.md)
- [api/change-management/get_change_types.php](/code/files/api/change-management/get_change_types.php.md)
- [api/change-management/list.php](/code/files/api/change-management/list.php.md)
- [api/change-management/update_field.php](/code/files/api/change-management/update_field.php.md)

## Loaded by

- [change-management/table.php](/code/files/change-management/table.php.md)

