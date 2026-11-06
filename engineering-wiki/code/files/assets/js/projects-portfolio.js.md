# assets/js/projects-portfolio.js

> JavaScript - 386 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 1 Architecture](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-1-Architecture)

Projects - the portfolio page (3.2.0).

Loads every project the analyst may see ONCE and filters in the browser:
the views are questions people actually ask (what is live, what do I lead,
what needs attention), and the search narrows whichever view is open. The
list is already company-scoped by the server, so nothing here decides who
may see what.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `inView(p, v)` | 29 | True when a project belongs in view v - live, mine, at risk, proposed, waiting for approval, on hold, finished or all. |
| `byTarget(a, b)` | 46 | Sort order by target finish (projects with none last), then by name. |
| `sorted(rows)` | 48 | The rows in the chosen order - priority, health or name, each then by target; 'target' keeps the server's order. |
| `healthStrip()` | 65 | One stacked bar, part to whole: how many live projects are on track, at risk, off track, or have no health yet. Health colours are STATUS colours, and each segment carries its word and count in the legend (and a tooltip), so colour never carries the meaning alone - green and amber sit close for protan vision (palette validator, 2026-10-09). |
| `word(k)` | 73 | 'none' is a live project with no health worked out - not the ring's "Finished"; it only shows when there is one. |
| `roadmap(rows)` | 89 | Every project in the view as a bar from its start to its target finish, in its own colour (identity), filled to its progress, with its next milestone as a diamond, a line for today. Health is on the row's label as a word, not on the bar - the bar's colour already means "which project". The cards are the table twin. Built from the Timeline's own classes (.prj-tl-*). |
| `dn(s)` | 92 | A Y-m-d date as a whole day number (days since 1970, UTC). |
| `x(n)` | 107 | The x position of a day number on the roadmap track. |
| `matches(p)` | 143 | True when a project matches the search text - name, goal, summary, code, owner or company. |
| `card(p, i)` | 150 | One project's card on the portfolio - colour band, code, priority, status, name, goal, progress ring, current stage, next milestone, owner, target, and chips for what needs attention. |
| `render()` | 196 | Redraws the portfolio for the current view, search, sort and layout - view counts, the summary tiles, the health strip and the cards, roadmap or charts. |
| `set(k, v)` | 208 | Writes a value into the summary tile with this key. |
| `short(s)` | 237 | A name cut to 28 characters with an ellipsis, for chart labels. |
| `charts(ordered)` | 240 | The Charts layout - progress per project, budget against actual (one chart per currency), a risk heat map whose cells list their risks, and every milestone on one calendar; the extra data is fetched once from portfolio_charts.php. |
| `name(id)` | 249 | A project's short name for chart labels, or #id when it is not in the view. |
| `money(v)` | 272 | An amount in this chart's currency, whole units, for the viewer's locale. |
| `score(s)` | 288 | The heat map colour class for a probability x impact score - high from 15, medium from 8. |
| `load()` | 316 | Loads every project the analyst may see, shows New project only to those who may create, applies the colour palette, priority labels and default sort, and draws the page. |
| `newProject()` | 335 | Opens the new project form and goes to the new project's page once it is saved. |
| `exportView(format)` | 340 | The projects in the view, in the view's order, as a spreadsheet (3.3.0). |

## API endpoints it calls

- [api/projects/export.php](/code/files/api/projects/export.php.md)

## Loaded by

- [projects/index.php](/code/files/projects/index.php.md)

