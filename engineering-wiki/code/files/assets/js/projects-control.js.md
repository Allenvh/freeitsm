# assets/js/projects-control.js

> JavaScript - 269 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 6 Governance](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-6-Governance)

Projects - the Change control tab (3.3.0). Rules: includes/projects/control.php.

Top: the agreed plan (a baseline) against the plan as it is now, headline
figures then what moved item by item. Pick an older baseline to compare
with that one instead. Below: change requests - raise, edit or withdraw one
still waiting, approve or reject (the server says who may: a setting).
The variance is worked out on the server; this file only draws it.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 16 | Translates a key under projects.control.*. |
| `call(body)` | 24 | Posts a change-control action to tools.php for the current project. |
| `canChange()` | 26 | True when the viewer may change this project. |
| `cur()` | 28 | The project's currency - from the budget, else the plan as it stands now, else GBP. |
| `money(v)` | 31 | An amount formatted in the project's currency for the viewer's locale, or "-" when empty. |
| `signed(n, fmt)` | 37 | A difference with its sign in front (+ or -) and the size formatted by fmt. |
| `days(n)` | 39 | A number of days in words, singular or plural. |
| `hrs(n)` | 41 | Hours to one decimal place, such as "12.5 h". |
| `date(d)` | 43 | A date formatted for the viewer, or "-" when there is none. |
| `reasonText(b)` | 46 | Why a baseline was taken, in words - a stage, an approved change request, the project starting, or by hand (with its label). |
| `baselineName(b)` | 53 | A baseline's name - "Baseline n" plus its label when it has one. |
| `compare(c)` | 57 | The baseline panel - pick a baseline, the headline table (start, finish, budget, tasks, hours, must-haves) baseline against now, and what moved item by item. |
| `row(label, from, to, delta, bad)` | 74 | One row of the comparison table, the change shown in red when bad. |
| `dated(kind, x)` | 90 | The sentence for a milestone or stage that was moved, added or removed since the baseline. |
| `request(r)` | 108 | One change request's card - reference, title, status, impact chips, description and reason, who raised and decided it, what approving did, and the actions the viewer may take. |
| `render()` | 145 | Draws the Change control tab - the baseline comparison and the change requests with who may approve and what approving does. |
| `$(id)` | 162 | Shorthand for document.getElementById. |
| `openRequest(r)` | 165 | Opens the change request dialog filled from r, or empty to raise a new one. |
| `saveRequest()` | 181 | Saves the change request dialog through tools.php and redraws the project. |
| `approveEffect(r)` | 191 | What approving will do, said before anybody presses it. |
| `openDecide(id, decision)` | 208 | Opens the approve / reject dialog for a change request, saying beforehand what approving will do. |
| `decide()` | 222 | Records the approve or reject decision with its notes and redraws the project. |
| `takeBaseline()` | 230 | Takes a baseline of the plan as it is now, with the typed label, and goes back to comparing with the latest. |
| `wire()` | 238 | Wires the tab's buttons (raise, take a baseline, edit, approve, reject, withdraw), the baseline picker and the dialogs' save buttons, once. |

## Loaded by

- [projects/view.php](/code/files/projects/view.php.md)

