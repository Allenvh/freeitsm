# assets/js/projects-capacity.js

> JavaScript - 153 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 4 Plan and Timeline](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-4-Plan-and-Timeline)

Projects - the Capacity page (3.3.0).

One row per person, one cell per week: their load - project work plus
service-desk duty - against the hours they work in a week, coloured green,
amber or red. A row opens to show the project tasks behind the numbers.
Everything is worked out by the server (includes/projects/capacity.php);
this file only draws it.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `hrs(h)` | 22 | Hours rounded to one decimal place in the page's language, such as "7.5h". |
| `tiles(c)` | 25 | The summary tiles for this week - people over capacity, stretched, on several projects, and tasks with no estimate (plus team tasks). |
| `tile(n, label, cls)` | 32 | One summary tile with its number, label and colour class. |
| `cell(w, c)` | 40 | One person-week cell - a load bar with the service-desk share, the percentage, and a tooltip splitting project and desk hours. |
| `detail(r, c)` | 50 | The opened row's detail - each project task with hours left, due date and hours per week, plus notes on unscheduled, later and late work. |
| `render()` | 72 | Draws the Capacity page - intro, tiles, the person-by-week table with any opened rows, the legend, team and contractor notes. |
| `load()` | 117 | Loads capacity for the chosen number of weeks from capacity.php and draws it. |

## Loaded by

- [projects/capacity.php](/code/files/projects/capacity.php.md)

