# assets/js/tasks-timeline.js

> JavaScript - 492 lines

FreeITSM Tasks — Timeline (Gantt) View

Each parent task with at least one date is drawn as a horizontal bar from
its start_date to its due_date (a task with only a due_date is a single-day
bar). Rows are grouped by assignee, status, or shown flat.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `loadSettings()` | 70 | Loads the Tasks settings the timeline uses - whether tags show and how priority is drawn on a bar. |
| `loadDropdowns()` | 83 | Loads analysts and teams and fills the Analyst and Team filter drop-downs. |
| `loadStatuses()` | 105 | Loads the active task statuses for the quick-look panel. |
| `loadPriorities()` | 113 | Loads the active task priorities for the quick-look panel. |
| `loadTasks()` | 121 | Loads the tasks for the current filter and redraws the timeline. |
| `setFilter(filter)` | 135 | ── Filters ──────────────────────────────────────────────────────── |
| `setTeamFilter(teamId)` | 148 | Shows one team's tasks, or goes back to My tasks when cleared. |
| `setAnalystFilter(analystId)` | 159 | Shows one analyst's tasks, or goes back to My tasks when cleared. |
| `setGroupBy(value)` | 170 | Regroups the timeline rows (by analyst, by status, or none) and redraws. |
| `render()` | 173 | ── Rendering ────────────────────────────────────────────────────── |
| `buildGroups(items)` | 314 | Groups the dated tasks into timeline rows by analyst, status or one group, each sorted by start date. |
| `scrollToToday()` | 343 | Scrolls the timeline so today's marker sits a third of the way across. |
| `openTask(id)` | 352 | Row-label clicks, bar mouseup-without-drag, and bar clicks all route here |
| `ymd(d)` | 355 | ── Utilities ────────────────────────────────────────────────────── |
| `addDays(ds, n)` | 362 | Returns a Date the given number of days after a YYYY-MM-DD. |
| `dayDiff(a, b)` | 369 | Returns the whole days from date string a to date string b. |
| `fmt(ds)` | 374 | Formats a YYYY-MM-DD as a short day and month. |
| `esc(text)` | 379 | HTML-escapes a value through a text node; null and undefined become an empty string. |
| `addDayStr(ds, n)` | 387 | Returns the YYYY-MM-DD the given number of days after another. |
| `onBarMouseDown(e)` | 390 | ── Drag-to-edit dates ───────────────────────────────────────────── |
| `onDragMove(e)` | 417 | Moves or resizes the dragged bar, snapped to whole days and never narrower than one day. |
| `onDragUp(e)` | 445 | Ends a bar drag - a drag with no movement opens the task, otherwise the new start and due dates are saved through save.php and the timeline reloads. |

## Loaded by

- [tasks/timeline/index.php](/code/files/tasks/timeline/index.php.md)

