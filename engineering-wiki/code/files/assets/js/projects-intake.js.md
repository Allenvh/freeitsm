# assets/js/projects-intake.js

> JavaScript - 125 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 6 Governance](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-6-Governance)

Projects - a proposal and its approval, on the Overview (3.3.0). Rules: includes/projects/intake.php. Who may decide comes from the server (proposal.can_decide); the server checks again.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 12 | Translates a key under projects.intake.*. |
| `$(id)` | 15 | Shorthand for document.getElementById. |
| `call(body)` | 21 | Posts a proposal action to tools.php for the current project. |
| `money(v)` | 23 | An amount in the project's budget currency (GBP when it has none), or "-" when empty. |
| `render()` | 31 | Draws the proposal panel on the Overview - who proposed it, cost, benefit, business case, the decision or who it waits for, and Approve / Reject / Edit as allowed; hidden when the project has no proposal. |
| `openDecide(decision)` | 69 | Opens the approve / reject dialog, saying what the decision will do; a rejection needs notes. |
| `decide()` | 81 | Records the approve or reject decision with its notes and redraws the project. |
| `openEdit()` | 89 | Opens the proposal edit dialog with the business case, estimated cost and benefit. |
| `saveEdit()` | 100 | Saves the proposal's business case, cost and benefit through save.php and redraws the project. |
| `wire()` | 108 | Wires Approve / Reject / Edit (on the page, as the Overview is redrawn each refresh) and the dialogs' save buttons, once. |

## Loaded by

- [projects/view.php](/code/files/projects/view.php.md)

