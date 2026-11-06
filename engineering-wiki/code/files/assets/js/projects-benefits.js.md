# assets/js/projects-benefits.js

> JavaScript - 174 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 6 Governance](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-6-Governance)

Projects - the Benefits tab (3.3.0). Rules: includes/projects/benefits.php.

Each benefit: where it started, where it is now (the latest measurement) and
where it should get to, with which way is better, its owner and its next
review. State and progress come from the server; this file only draws.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 14 | Translates a key under projects.benefits.*. |
| `$(id)` | 17 | Shorthand for document.getElementById. |
| `call(body)` | 24 | Posts a benefits action to tools.php for the current project. |
| `canChange()` | 26 | True when the viewer may change this project. |
| `val(v, unit)` | 28 | A figure formatted for the viewer's locale with its unit, or "-" when there is none. |
| `card(b)` | 31 | One benefit's card - state pill, measure, baseline / now / target figures, progress bar, owner and review dates, actions and, when opened, its measurement history. |
| `render()` | 67 | Draws the Benefits tab - the intro, an Add button for those who can change the project, and a card per benefit. |
| `openBenefit(b)` | 80 | Opens the benefit dialog filled from b, or empty for a new one; the owner list is the project's analyst members plus the project manager. |
| `saveBenefit()` | 108 | Saves the benefit dialog through tools.php and redraws the project. |
| `deleteBenefit()` | 119 | Asks, then deletes the benefit open in the dialog. |
| `openMeasure(b)` | 127 | Opens the record-a-measurement dialog for a benefit, dated today. |
| `saveMeasure()` | 141 | Records the measurement through tools.php and redraws the project. |
| `wire()` | 149 | Wires the tab's buttons (add, edit, record, history toggle, delete a measurement) and the dialogs' save and delete, once. |
| `find(id)` | 153 | The benefit with this id. |

## Loaded by

- [projects/view.php](/code/files/projects/view.php.md)

