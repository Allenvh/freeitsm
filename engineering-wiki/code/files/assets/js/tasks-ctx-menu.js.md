# assets/js/tasks-ctx-menu.js

> JavaScript - 411 lines

FreeITSM Tasks — Shared right-click context menu

One menu used by the dashboard (right-click a board / list card) and the
timeline (right-click a Gantt bar). Each page provides:
  - targetSelector: CSS selector that identifies a clickable task element
  - getTaskId(el):  return the numeric task id from that element
  - getTask(id):    return the task object (so submenus know the current
                    analyst/team/status/priority for the ✓ marker)
  - `getLookups()`:   return { analysts, teams, statuses, priorities }
  - `onUpdate()`:     called after a successful save (typically `loadTasks()`)
  - apiBase:        base URL for save.php (e.g. '../api/tasks/')
  - onCreateSubtask(id) [optional]: dashboard wires this to open the detail
                    panel and focus the add-subtask input. Omit on pages
                    that don't have a detail panel — the menu hides the
                    "Create subtask" item when this is missing.

Markup contract: a single .ctx-menu with id="ctxMenu" containing four
submenu containers (#ctxAnalyst / #ctxTeam / #ctxStatus / #ctxPriority).
The optional "Create subtask" item carries data-action="subtask".

## Functions

| Function | Line | What it does |
|---|--:|---|
| `init(config)` | 39 | Sets the page's menu configuration, loads the companies for "Move to company" and wires the document listeners once. |
| `loadMoveCompanies()` | 81 | Same source as the tickets and changes "Move to company" submenus. accessible=1 means the server decides what this analyst may see, so the menu cannot offer a company the move would then refuse. |
| `close()` | 92 | Closes the menu. |
| `isOpen()` | 94 | Whether the menu is showing. |
| `onDocCtx(e)` | 100 | Opens the menu when a task element is right-clicked, and closes it on a right-click anywhere else. |
| `openCtx(e, taskId)` | 111 | Opens the menu for a task at the pointer, kept inside the window, with submenus flipped when they would run off screen. |
| `closeCtx()` | 130 | Hides the menu and forgets the task it was opened on. |
| `buildSubmenus(task)` | 144 | Fills the submenus for a task - analyst, team, status, company, priority and due-date shortcuts, each with its current value ticked. |
| `T(k)` | 147 | Translates a key under tasks.*. |
| `opt(field, value, label, current, swatch)` | 150 | Returns one submenu option for a field and value, ticked when it is the task's current value. |
| `sw(c)` | 156 | Returns a colour swatch for a status or priority option. |
| `ymd(d)` | 208 | ── Due date shortcuts ──────────────────────────────────────────── ⚠️ Built from LOCAL date parts, never `toISOString()`: a due date is a bare date, and `toISOString()` converts to UTC first, so anyone west of Greenwich would get yesterday. Same trap as GH #116, different field. |
| `onMenuClick(e)` | 251 | Runs a menu choice - a submenu value is saved (or the task moved company), and the actions (subtask, open, repeat, assign to me, complete / reopen, log time, copy link, delete) are carried out or handed to the page. |
| `moveToCompany(tenantId)` | 346 | Move the task - and everything beneath it - to another company. |
| `setField(field, value)` | 369 | Saves one field of the menu's task through save.php, then toasts and calls onUpdate. |
| `esc(text)` | 397 | ── Local helpers (mirrors of esc/escAttr in tasks.js + tasks-timeline.js) ── |
| `escAttr(value)` | 404 | Escapes a value for use inside a double-quoted HTML attribute. |

## API endpoints it calls

- [api/system/get_tenants.php](/code/files/api/system/get_tenants.php.md)

## Loaded by

- [tasks/index.php](/code/files/tasks/index.php.md)
- [tasks/table/index.php](/code/files/tasks/table/index.php.md)
- [tasks/timeline/index.php](/code/files/tasks/timeline/index.php.md)

