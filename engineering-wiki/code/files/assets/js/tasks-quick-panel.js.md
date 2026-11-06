# assets/js/tasks-quick-panel.js

> JavaScript - 198 lines

FreeITSM Tasks — Shared right-side quick-look detail panel

Used by the calendar and the timeline (any view where the full dashboard
editor would be overkill but the user still wants to peek-and-edit
without losing their place). Shows: title (editable), status / priority /
assignee / team / start / due dates (editable), tags (read-only chips),
description (read-only HTML preview). A launch-icon link in the header
takes the user to the dashboard's full editor for richer changes
(subtasks, comments, rich-text description).

The page provides:
  - apiBase:        base URL for get.php / save.php (e.g. '../api/tasks/')
  - `getLookups()`:   return { analysts, teams, statuses, priorities }
  - `onUpdate()`:     called after the panel closes — typically `loadTasks()`
                    so the calendar/timeline re-renders with any edits
  - fullEditUrl(id): URL the header launch icon links to

The panel markup is injected by this module on first `init()` (so pages
only need to load the script, not include duplicate HTML).

## Functions

| Function | Line | What it does |
|---|--:|---|
| `init(config)` | 34 | Sets the page's panel configuration, injects the panel markup if needed, and wires Escape to close it once. |
| `ensureMarkup()` | 51 | Adds the quick-look panel and its overlay to the page, unless they are already there. |
| `T(k)` | 54 | Translates a key under tasks.detail.*. |
| `open(id)` | 70 | Opens the quick-look panel on a task, showing a loading line until get.php returns it. |
| `close()` | 87 | Closes the panel and, if a task was open, calls onUpdate so the calendar or timeline shows any edits. |
| `saveField(field, value)` | 100 | Saves one field of the open task through save.php straight away; a failure is only logged to the console. |
| `render(task)` | 115 | Fills the panel with the task's editable title, status, priority, assignee, team and dates, plus its tags and a description preview. |
| `T(k)` | 118 | Translates a key under tasks.detail.*. |
| `esc(text)` | 189 | HTML-escapes a value through a text node; null and undefined become an empty string. |

## Loaded by

- [tasks/calendar/index.php](/code/files/tasks/calendar/index.php.md)
- [tasks/timeline/index.php](/code/files/tasks/timeline/index.php.md)

