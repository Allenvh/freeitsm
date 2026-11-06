# assets/js/tasks-calendar.js

> JavaScript - 559 lines

FreeITSM Tasks — Calendar View

Renders parent tasks onto a month grid. How a task that spans several days
(start_date earlier than due_date) is drawn depends on the calendar span
mode set in Tasks → Settings → Calendar:
  deadline — a single chip on the due date only
  span     — one continuous bar across the whole range
  repeat   — a chip in every day cell of the range

## Functions

| Function | Line | What it does |
|---|--:|---|
| `loadSettings()` | 56 | Loads the Tasks settings the calendar uses - the span mode and whether tags show on the calendar. |
| `loadStatuses()` | 65 | Loads the task statuses and redraws the colour legend. |
| `loadPriorities()` | 76 | Loads the active task priorities for the quick-look panel. |
| `loadDropdowns()` | 84 | Loads analysts and teams and fills the Analyst and Team filter drop-downs. |
| `visibleProjected()` | 114 | Future occurrences of fixed-schedule repeats (#94), honouring the same who-is-it-for filter as the real tasks beside them. |
| `loadProjected()` | 132 | Loads the projected future occurrences of repeating tasks, from two months before to a year after the month in view; a failure leaves none. |
| `ymd(d)` | 138 | Formats a Date as YYYY-MM-DD through toISOString, so in UTC. |
| `loadTasks()` | 151 | Loads the tasks for the current filter and subtask scope, then the projected repeats, and redraws the calendar. |
| `setSubtaskScope(scope)` | 167 | ── Filters ──────────────────────────────────────────────────────── |
| `setFilter(filter)` | 182 | Switches to a preset filter (my, all...), clears the team and analyst filters and reloads. |
| `setTeamFilter(teamId)` | 195 | Shows one team's tasks, or goes back to My tasks when cleared. |
| `setAnalystFilter(analystId)` | 206 | Shows one analyst's tasks, or goes back to My tasks when cleared. |
| `calPrev()` | 217 | ── Navigation ───────────────────────────────────────────────────── |
| `calNext()` | 224 | Moves forward one month, week or day, depending on the view. |
| `calToday()` | 231 | Jumps back to today (the 1st of this month in month view). |
| `setView(mode)` | 241 | Switches between month, week and day views. |
| `renderCalendar()` | 251 | ── Rendering ────────────────────────────────────────────────────── |
| `placedTasks()` | 263 | Resolve each task to the date range it occupies, respecting span mode. Projected occurrences (#94) are merged in here rather than rendered separately, so they get the same placement, span and week-wrapping logic and cannot drift from how real tasks are drawn. |
| `renderWeekRow(weekStart, todayStr, placed, monthCtx)` | 277 | Render one week's day cells + bars. monthCtx is the month number to dim out-of-month cells against (or null in week view, where no dimming applies). |
| `renderMonth()` | 328 | Draws the month view - one row per week from Monday, each high enough for its lanes of bars. |
| `renderWeek()` | 366 | Draws the week view for the Monday-to-Sunday week holding the view date, with a compact range title. |
| `renderDay()` | 402 | Draws the day view as a list of the tasks on that date, open before done, with status, assignee, dates, parent and tags. |
| `renderBars(iv)` | 464 | Returns the bar markup for one task's interval in a week row - one spanning bar in span mode, otherwise a chip per day. |
| `make(col, span, extraCls)` | 494 | Returns one bar or chip at a column and span, coloured by status and marked for done, subtask, repeating and projected. |
| `renderLegend()` | 516 | Draws the legend of active statuses and their colours. |
| `openTask(id)` | 527 | Bars and day-view rows open the shared quick-look panel |
| `mondayCol(d)` | 531 | ── Utilities ────────────────────────────────────────────────────── Monday-based column index (Mon=0 … Sun=6) |
| `ymd(d)` | 534 | Formats a Date as a local YYYY-MM-DD. |
| `dayDiff(a, b)` | 541 | Whole days from date string a to date string b (b assumed >= a) |
| `fmt(ds)` | 548 | Formats a YYYY-MM-DD as a short day and month. |
| `esc(text)` | 553 | HTML-escapes a value through a text node; null and undefined become an empty string. |

## API endpoints it calls

- [api/system/set_user_preference.php](/code/files/api/system/set_user_preference.php.md)

## Loaded by

- [tasks/calendar/index.php](/code/files/tasks/calendar/index.php.md)

