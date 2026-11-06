# assets/js/projects-budget.js

> JavaScript - 310 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 6 Governance](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-6-Governance)

Projects - the Budget tab (3.2.0). Rules: includes/projects/budget.php.

Planned against actual in the PROJECT's currency, with labour worked out from
the time logged on its tasks. Amounts in another currency (a contract) are
shown and never added; changing the project's currency is a relabel, and the
page says so before anyone confirms. Rates have dates, so a new rate never
re-prices the past - the panel shows the project's own rate history.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 16 | Translates a key under projects.budget.*. |
| `money(v, cur)` | 22 | An amount formatted in the given currency for the viewer's locale, or "-" when empty. |
| `hours(min)` | 28 | Minutes shown as hours to one decimal place, such as "2.5 h". |
| `canChange()` | 33 | True when the viewer may change this project. |
| `call(body)` | 35 | Posts a budget action to tools.php for the current project. |
| `render()` | 38 | Draws the Budget tab - the planned / actual / forecast / remaining / used tiles, the chart boxes, the currency line, the budget lines table and the labour panel with its rates. |
| `drawEarned(b, cur)` | 133 | Earned value (3.3.0): planned value (orange - the plan, as on Spend over time), actual cost (blue - what was spent, likewise) and earned value (aqua - the work done, valued at its budget), with today's indices in words as well as numbers. |
| `word(v, good, bad)` | 141 | The good or bad word for an earned-value index - good at 1 or more - or "unknown" when there is none. |
| `drawSpend(b, cur)` | 165 | Spend over time (3.3.0): cumulative planned and actual, the forecast, and what to measure them against. |
| `drawChart(b, cur)` | 195 | Planned against actual per category; labour's actual is the costed time (3.3.0). |
| `openLine(line)` | 219 | Opens the budget line dialog filled from line, or empty for a new one, with the contract and cost centre lists (keeping a switched-off cost centre the line already uses). |
| `saveLine()` | 245 | Saves the budget line dialog through tools.php and redraws the project. |
| `deleteLine()` | 258 | Asks, then deletes the budget line open in the dialog. |
| `changeCurrency()` | 266 | Asks for a new currency code and, after a warning that amounts are relabelled and not converted, changes the project's currency. |
| `wire()` | 276 | Wires the tab's buttons (add line, change currency, open a line, add and delete a rate) and the dialog's save and delete, once. |
| `render(c)` | 298 | Draws the Budget tab when the project has the budget tool switched on. |
| `shown()` | 305 | The tab was shown: the chart measures its width, so it draws now (3.3.0). |

## Loaded by

- [projects/view.php](/code/files/projects/view.php.md)

