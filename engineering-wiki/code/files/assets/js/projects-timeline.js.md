# assets/js/projects-timeline.js

> JavaScript - 477 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 4 Plan and Timeline](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-4-Plan-and-Timeline)

Projects - the Timeline tab (3.3.0): the plan against the calendar.

Each phase / stage / sprint is a BAND across its dates, its tasks are BARS
from start to due date, and milestones are DIAMONDS - in their stage's band,
or on a "Project milestones" row of their own. A line marks today and a
dashed one the target finish.

Moving things (mouse only): drag a bar or a band to move it, drag either end
to change one date, drag a diamond to move a milestone. Each drop is one
ordinary save through the server's rules - task_dates (TasksService, so the
Tasks board and its calendar follow), stage_save.php, milestone_save - and the
page redraws from the server's answer. Nothing here decides a rule.

🔑 TOUCH SCROLLS, IT NEVER DRAGS. A hand-rolled drag on a phone fights the
sideways scroll the chart needs (Mobile-Friendly-Techniques §22). Every bar,
band and diamond also opens on a tap - the task, the stage dialog, the
milestone dialog - so the dates can still be changed there; the drag is a
shortcut, never the only way.

Dates are whole days, counted in UTC (dayNum), so a bar never shifts by one
when the browser's time zone is ahead of or behind the server's.

DEPENDENCIES (3.3.0, includes/projects/dependencies.php): an arrow from the end
of the task waited for to the start of the one that waits - red when the
plan clashes (it starts before the other finishes). Critical path picks out
the tasks with no slack. The link button on a task's row opens its
dependencies (prjDepModal); the server refuses loops.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `dayNum(s)` | 52 | A Y-m-d date as a whole day number counted in UTC, so a bar never shifts by a day with the browser's time zone. |
| `ymd(n)` | 54 | A day number back to a Y-m-d date. |
| `labelWidth()` | 60 | The width of the name column - narrower on a phone. |
| `canChange()` | 64 | True when the viewer may change this project. |
| `timeboxKind()` | 68 | What the project's way of running calls its time boxes - phase, stage or sprint. |
| `taskSpan(t)` | 74 | A task's [start, end] as day numbers, or null when it has no date at all. |
| `stageSpan(s)` | 81 | A stage's [start, end] as day numbers, or null unless it has both dates. |
| `render(c)` | 87 | Marks the timeline as needing a redraw and draws it now if the tab is showing (it needs a width). |
| `draw(box)` | 96 | Draws the whole timeline - toolbar, month and day/week header, grid, the project milestones row, a band per stage with its tasks' bars, unassigned tasks, today and target lines and the dependency arrows - keeping the scroll position across redraws. |
| `x(n)` | 128 | The x position of a day number on the track. |
| `diamonds(list)` | 175 | The milestone diamonds for a row, each placed on its due date. |
| `taskRow(t)` | 186 | One task's row - name linking to the task, the dependencies button, and its bar (late, done, critical or dimmed) with drag handles for those who may change it. |
| `drawArrows(box, LW)` | 252 | The dependency arrows (3.3.0), drawn from the bars where they landed: from the right end of the task waited for to the left end of the one that waits, as an elbow. Red and dashed where the plan clashes. Under the bars' text, over the grid; never in the way of a click. |
| `pos(id)` | 265 | Where a task's bar sits in the body - left and right edges and the row's middle - or null when it has no bar. |
| `openDeps(taskId)` | 295 | Opens the dependencies dialog for a task - what it waits for (with lag), a picker to add one, what waits for it, and a warning when the dates clash. |
| `name(id)` | 301 | A task's title by id, or #id when it is not on the project. |
| `depCall(body)` | 325 | Sends a dependency action to tools.php, redraws the project and reopens the dialog; errors show in the dialog. |
| `scrollToToday(sc)` | 331 | Scrolls the timeline so today sits about a third of the way across. |
| `onDown(e)` | 340 | Starts a mouse drag on a task bar, stage band or milestone - moving it, or resizing from an end handle; touch never drags. |
| `onMove(e)` | 356 | Moves or resizes the dragged item in whole days once the mouse has moved more than 3px. |
| `onUp()` | 370 | Ends a drag - an unmoved press opens the item; otherwise saves the new dates (task_dates, stage_save.php or the milestone) and redraws, or puts it back on failure. |
| `click(el)` | 411 | A tap or a click without a drag: open the thing. |
| `shown()` | 474 | The tab was shown: draw if anything changed while it was hidden. |

## Loaded by

- [projects/view.php](/code/files/projects/view.php.md)

