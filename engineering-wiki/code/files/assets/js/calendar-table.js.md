# assets/js/calendar-table.js

> JavaScript - 101 lines

FreeITSM Calendar — table view config

Supplies the calendar-specific pieces to the shared data-table engine
(assets/js/data-table.js): the COLUMNS catalogue, event loading, and
inline-edit persistence. Everything else (sort/filter/search/CSV/columns
drawer/preferences) is the shared engine.

Inline editing: the calendar's save_event.php rewrites the whole row, so each
cell save posts the full in-memory event with the one changed field applied.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k)` | 19 | Translates a key, returning the key itself when translations are not loaded. |
| `fmt(raw)` | 22 | Shows a stored date-time as "YYYY-MM-DD HH:MM" - blank when there is none. |
| `loadCategories()` | 47 | Loads the calendar categories for the category column and its inline editor. |

## API endpoints it calls

- [api/calendar/get_categories.php](/code/files/api/calendar/get_categories.php.md)
- [api/calendar/get_events.php](/code/files/api/calendar/get_events.php.md)
- [api/calendar/save_event.php](/code/files/api/calendar/save_event.php.md)

## Loaded by

- [calendar/table/index.php](/code/files/calendar/table/index.php.md)

