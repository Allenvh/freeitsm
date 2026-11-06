# assets/js/projects-charts.js

> JavaScript - 748 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 5 Health and Charts](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-5-Health-and-Charts)

Projects - charts (3.3.0): the burn-up on a project's Overview, and "where the money goes" and spend over time on its Budget tab. Plain SVG, no library.

The rules they follow (the dataviz method, checked 2026-10-09):
 - Two categorical series, slots 1 and 2 (blue, orange) - validated for
   colour-blind separation and contrast on the app's own light (#fff) and
   dark (#1e2228) surfaces; the dark steps are chosen, not flipped
   (--prj-viz-1 / --prj-viz-2 in projects.css).
 - Thin marks: 2px lines, bars <= 24px with rounded data ends, 4px end dots
   with a 2px surface ring; hairline solid gridlines.
 - A legend for every two-series chart; values selectively (line ends, bar
   tips); text in text colours, never the series colour.
 - A hover layer (crosshair + one tooltip for both series on the line; per
   bar on the bars), the same on keyboard focus, and a table view - the
   tooltip never holds a value nobody can reach otherwise.
 - Names go in with textContent (they are users' words).

## Functions

| Function | Line | What it does |
|---|--:|---|
| `num(v)` | 26 | A number rounded to one decimal place in the page's language. |
| `el(name, attrs, parent)` | 29 | Creates an SVG element with the given attributes, appended to parent when one is given. |
| `html(tag, cls, text)` | 36 | Creates an HTML element with an optional class and text (set as text, never as markup). |
| `nice(max)` | 43 | A clean axis maximum and step: 0 / 5 / 10 / 15, 0 / 2,000 / 4,000. |
| `legend(series)` | 50 | The legend - a swatch and name per series. |
| `tableView(head, rows)` | 61 | The table shown in place of a chart - a header row and one row per data point, numbers right-aligned. |
| `frame(container, tableLabel, chartLabel, buildTable)` | 73 | The chart / table switch, and where the chart draws. |
| `tooltip(plot)` | 92 | Adds a hidden tooltip box to the plot area and returns it. |
| `tipRow(tip, value, name, cls)` | 99 | Adds one line to a tooltip - series swatch, value and name. |
| `burnup(container, opts)` | 113 | Burn-up: scope (everything in the project so far) and done, over time, with the target finish as a reference line. opts: {points: [{d: 'Y-m-d', scope, done}], target, series: [scopeName, doneName], unit: '' \| 'h', fmtDate, labels: {table, chart, date, target, aria}} |
| `dn(s)` | 124 | A Y-m-d date as a whole day number (days since 1970, UTC). |
| `X(d)` | 129 | The x position of a date on the burn-up. |
| `Y(v)` | 131 | The y position of a value on the burn-up. |
| `path(key)` | 151 | The SVG path through every point for one series (scope or done). |
| `show(i)` | 168 | Moves the burn-up's crosshair to point i and shows its date, done and scope in the tooltip. |
| `hide()` | 182 | Hides the crosshair and the tooltip. |
| `bars(container, opts)` | 204 | Grouped horizontal bars - two values per category (planned, actual). opts: {rows: [{label, a, b}], series: [aName, bName], fmt: v => text, labels: {table, chart, category, aria}} A missing value (null) draws no bar and reads "-". |
| `on()` | 235 | Shows the tooltip for this bar - its category, value and series. |
| `spend(container, opts)` | 259 | Spend over time (3.3.0): cumulative planned and actual spend as steps, the forecast as a dashed continuation of actual from today to the finish, and the amounts to measure against (the budget, the baseline's budget, the tolerance) as plain reference lines labelled in text colour - they are thresholds, not series, so they take no series colour. opts: {points: [{d, planned, actual\|null}], forecast: {from, to, start, end}\|null, refs: [{v, label}], today, target, fmt, fmtTick, fmtDate, series: {actual, planned, forecast}, labels: {table, chart, date, today, target, at_finish, aria}} |
| `dn(s)` | 276 | A Y-m-d date as a whole day number (days since 1970, UTC). |
| `X(d)` | 282 | The x position of a date on the spend chart. |
| `Y(v)` | 284 | The y position of an amount on the spend chart. |
| `step(list, key)` | 313 | Steps: the money moves on the day, not in a slope between days. |
| `valueAt(d)` | 337 | The latest data point on or before a date - what planned and actual stood at then. |
| `show(i)` | 340 | Moves the spend chart's crosshair to stop i and shows actual, forecast and planned at that date in the tooltip. |
| `hide()` | 355 | Hides the crosshair and the tooltip. |
| `slot(n)` | 373 | A series class for categorical slot n (1-6), or a neutral ('n', 'n2'). |
| `stack(container, opts)` | 381 | Horizontal stacked bars (3.3.0) - one row per group (a stage, a project), segments per series (task statuses). Segments keep a 2px surface gap; the row's total and an optional note sit at its end in text colour. opts: {rows: [{label, values: [n...], note?}], series: [{name, slot}], labels: {table, chart, group, total, aria}, fmt?, max?} |
| `on()` | 405 | Shows the tooltip for this segment - its row, value and series. |
| `flow(container, opts)` | 427 | Stacked area over time (3.3.0) - the cumulative flow diagram. Series are stacked bottom-up in the order given; a vertical marker can say where real history starts. Crosshair + one tooltip for every band, as the burn-up. opts: {points: [{d, values: [n...]}], series: [{name, slot}], marker?: {d, label}, fmtDate, labels: {table, chart, date, aria}} |
| `dn(s)` | 436 | A Y-m-d date as a whole day number (days since 1970, UTC). |
| `X(d)` | 441 | The x position of a date on the flow chart. |
| `Y(v)` | 443 | The y position of a stacked total on the flow chart. |
| `show(i)` | 477 | Moves the flow chart's crosshair to point i and lists every non-empty band at that date in the tooltip, top band first. |
| `hide()` | 490 | Hides the crosshair and the tooltip. |
| `burndown(container, opts)` | 512 | Burndown (3.3.0): the work still to do over time, against an IDEAL line from where it stood on the first day to nothing at the target - above the line is behind. One series (slot 1); the ideal is a dashed reference in ink. opts: {points: [{d, remaining}], target, unit, fmtDate, labels: {table, chart, date, remaining, ideal, target, aria}} |
| `dn(s)` | 515 | A Y-m-d date as a whole day number (days since 1970, UTC). |
| `ideal(d)` | 522 | The ideal work remaining on a date - a straight line from the first day's figure down to nothing at the target (or the last day). |
| `X(d)` | 531 | The x position of a date on the burndown. |
| `Xn(n)` | 533 | The x position of a day number on the burndown. |
| `Y(v)` | 535 | The y position of a value on the burndown. |
| `show(i)` | 563 | Moves the burndown's crosshair to point i and shows the remaining and ideal figures in the tooltip. |
| `hide()` | 577 | Hides the crosshair and the tooltip. |
| `milestones(container, opts)` | 600 | Milestones across rows (3.3.0) - one row per project, a diamond per milestone on a shared calendar, with today marked. State is a STATUS, so it is never colour alone: reached is a filled good diamond, missed a filled critical one, coming up an outline - and the legend, tooltip and table say it in words. opts: {rows: [{label, items: [{d, name, state}]}], today, fmtDate, labels: {table, chart, project, milestone, date, state, states: {done, missed, due}, today, aria}} |
| `dn(s)` | 611 | A Y-m-d date as a whole day number (days since 1970, UTC). |
| `X(d)` | 615 | The x position of a date on the milestones chart. |
| `on()` | 635 | Shows the tooltip for this milestone - project, name, date and state in words. |
| `lines(container, opts)` | 660 | Several lines over time on one money axis (3.3.0) - earned value: planned value, earned value and actual cost. Up to three series (slots 1-3, the all-pairs-safe set); a null value ends that line (earned value stops today). Reference lines as `spend()`; crosshair + one tooltip for every line. opts: {points: [{d, values: [n\|null...]}], series: [{name, slot}], refs?: [{v, label}], today?, fmt, fmtTick, fmtDate, labels: {table, chart, date, today, aria}} |
| `dn(s)` | 669 | A Y-m-d date as a whole day number (days since 1970, UTC). |
| `X(d)` | 674 | The x position of a date on the lines chart. |
| `Y(v)` | 676 | The y position of an amount on the lines chart. |
| `show(i)` | 713 | Moves the lines chart's crosshair to point i and lists each series' value at that date in the tooltip. |
| `hide()` | 726 | Hides the crosshair and the tooltip. |

## Loaded by

- [projects/index.php](/code/files/projects/index.php.md)
- [projects/view.php](/code/files/projects/view.php.md)

