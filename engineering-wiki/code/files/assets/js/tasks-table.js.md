# assets/js/tasks-table.js

> JavaScript - 194 lines

FreeITSM Tasks — table view config

Supplies the tasks-specific pieces to the shared data-table engine
(assets/js/data-table.js): the COLUMNS catalogue (with inline-edit controls),
task loading (respecting the My/Team/Analyst sidebar), and per-cell saves to
save.php. The sidebar filter handlers and the shared right-click context menu
are wired here too; everything else is the shared engine.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `formatDate(iso)` | 24 | created_datetime is a stored UTC timestamp; render its date in the analyst's chosen display zone (parseUTCDate / tzOpts from assets/js/tz.js). |
| `loadLookups()` | 56 | Loads the active task statuses and priorities for the table. |
| `loadDropdowns()` | 68 | Loads analysts and teams and fills the Analyst and Team filter drop-downs. |
| `setFilter(filter)` | 94 | Switches to a preset filter (my, all...), clears the team and analyst filters and reloads the table. |
| `setTeamFilter(id)` | 106 | Shows one team's tasks, or goes back to My tasks when cleared. |
| `setAnalystFilter(id)` | 116 | Shows one analyst's tasks, or goes back to My tasks when cleared. |
| `esc(s)` | 127 | HTML-escapes a value; null and undefined become an empty string. |

## API endpoints it calls

- [api/tasks/get_task_statuses.php](/code/files/api/tasks/get_task_statuses.php.md)
- [api/tasks/get_task_priorities.php](/code/files/api/tasks/get_task_priorities.php.md)
- [api/tasks/list.php](/code/files/api/tasks/list.php.md)
- [api/tasks/save.php](/code/files/api/tasks/save.php.md)

## Loaded by

- [tasks/table/index.php](/code/files/tasks/table/index.php.md)

