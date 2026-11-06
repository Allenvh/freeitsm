# assets/js/projects-insights.js

> JavaScript - 101 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 5 Health and Charts](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-5-Health-and-Charts)

Projects - the Overview's status and flow charts (3.3.0): Task status a stacked bar per stage (and the whole project): how many tasks sit in each of the install's task statuses, with how many are overdue and how many wait on another task Progress by stage done against not done, per stage, with the percentage Flow over time the cumulative flow diagram (includes/projects/flow.php)

🔑 A status keeps ITS colour in both charts: its slot is its place in the
board's order, never its place in a chart (color follows the entity). Six
validated slots; a seventh status and on share the neutral. Days before the
flow was first recorded are two neutral bands - finished, and open - with a
marker where real history starts.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 21 | Translates a key under projects.insights.*. |
| `slotOf(i)` | 26 | The chart colour slot for the status at board position i - the first six get slots 1-6, the rest share the neutral. |
| `render(c)` | 29 | Lays out the Overview's three chart panels (task status, progress by stage, flow over time) and draws them; empties the box when the project has no tasks. |
| `draw()` | 41 | Drawn when the Overview is showing - the charts measure their width. |

## Loaded by

- [projects/view.php](/code/files/projects/view.php.md)

