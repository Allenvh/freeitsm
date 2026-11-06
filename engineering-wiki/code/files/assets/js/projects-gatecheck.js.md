# assets/js/projects-gatecheck.js

> JavaScript - 177 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 6 Governance](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-6-Governance)

Projects - stage gate checklists (3.3.0). Rules: includes/projects/gatecheck.php.

Fills the checklist into each gate the Gates tab draws (projects-tools.js
`renderGates()` leaves a [data-gc] box per stage and calls `fill()`), and puts
what is still open into the decide box (openBox()). Whether an item is done
comes from the server - a change item is done when the change is approved,
a document item while that document is still on the project.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 16 | Translates a key under projects.gatecheck.*. |
| `$(id)` | 19 | Shorthand for document.getElementById. |
| `call(body)` | 25 | Posts a gate checklist action to tools.php for the current project. |
| `canChange()` | 27 | True when the viewer may change this project. |
| `gate()` | 29 | The project's gate checklist data, or an empty set when the server sent none. |
| `itemsOf(stageId)` | 31 | The checklist items for one stage's gate. |
| `when(dt)` | 33 | A date-time formatted for the viewer, or empty when there is none. |
| `itemRow(i)` | 36 | One checklist item - its tick mark, kind, title and detail, plus the control its kind needs (tick box, sign button for the named person, document or change picker) and delete. |
| `fill(c)` | 71 | Every gate's checklist, into the boxes `renderGates()` left. |
| `openBox(stageId)` | 94 | What is still open at a gate, for the decide box - and whether it blocks a go. |
| `openItem(stageId, item)` | 104 | Opens the checklist item dialog for a stage, filled from item or empty for a new one; an existing item's kind cannot be changed. |
| `showKind()` | 121 | Shows the person or change picker and the hint that suit the chosen item kind. |
| `saveItem()` | 128 | Saves the checklist item dialog through tools.php and redraws the project. |
| `find(id)` | 138 | The checklist item with this id, whichever gate it is on. |
| `stageOfItem(id)` | 143 | The stage whose gate holds this item, or 0. |
| `wire()` | 146 | Wires the Gates tab's checklist controls (add, edit, sign, delete, tick, document and change pickers, gate kind) and the item dialog, once. |
| `act(body, toast)` | 151 | Sends a checklist action, shows the toast if one is given, and redraws the project - also after a failure, so the screen matches the server. |

## Loaded by

- [projects/view.php](/code/files/projects/view.php.md)

