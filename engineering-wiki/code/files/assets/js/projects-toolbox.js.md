# assets/js/projects-toolbox.js

> JavaScript - 110 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 6 Governance](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-6-Governance)

Projects - the Toolbox on the Overview (3.3.0).

A project starts with the tools its way of running switches on (Simple: People
only; Agile: People, Scope, RAID; Staged: all eight - includes/projects/
methodologies.php). Somebody new to running projects should not meet a RACI
matrix and earned value on day one, so the Overview lists the tools NOT yet in
use - what each is for and when you would want it - with Add, and moves up the
ones the project itself suggests ("4 people on it - RACI says who does what").

Adding a tool writes the project's tailoring, exactly as the Edit form's Tools
boxes do (save.php, field `tailoring`), so it can be taken away there again.
RACI needs People and Scope (its columns and rows), so adding it adds those.

Off for the whole install with Projects -> Settings -> General -> Toolbox
(lookups `toolbox`); "Hide" hides it for this project in this browser.

  PrjToolbox.render({ data, L, projectId, refresh })   from projects-view.js

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 26 | Translates a key under projects.toolbox.*. |
| `hiddenKey(id)` | 34 | The browser storage key that remembers the Toolbox was hidden on this project. |
| `isHidden(id)` | 36 | True when the Toolbox was hidden for this project in this browser. |
| `reason(tool, d)` | 39 | Why the project itself suggests a tool, or null. Reads only what get.php already sent. |
| `render(c)` | 60 | Draws the Toolbox on the Overview - a card per tool not yet in use, the ones the project suggests first, each with what it is for and Add; hidden when switched off, finished, read-only, nothing is left to add or the viewer hid it. |
| `add(tool)` | 86 | Switches a tool on for the project (with the tools it needs - RACI brings People and Scope) by saving its tailoring, then redraws. |

## Loaded by

- [projects/view.php](/code/files/projects/view.php.md)

