# assets/js/projects-tour.js

> JavaScript - 175 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Developer Guide](https://github.com/edmozley/freeitsm/wiki/Projects-Developer-Guide)

Projects help - "How it fits together" (3.3.0): an interactive map of the parts of a project and what feeds what, with a 12-step walk through an office move, and a filter showing what each way of running starts with.

Mounted into #prjTour by projects/tutorial.php - its own full-width page since
3.3.0 (?walk=1 / data-start="walk" opens on the walk; Left / Right step it). The words are projects.tour.*;
the tools each method switches on come from the presets
(window.PRJ_TOUR = {methods: {simple: [...], ...}, labels: {simple: 'Simple', ...}}),
so the map can never disagree with includes/projects/methodologies.php.

Accessible without the picture: every box is a button, and the panel beside
the map lists in words what the chosen part feeds and is fed by (each a
button too). Lines are decoration, redrawn on resize.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 21 | Translates a key under projects.tour.*, or returns the key when the i18n helper is not loaded. |
| `TT(k)` | 23 | Translates a tool's name under projects.tools.*. |
| `esc(s)` | 25 | Escapes a value for safe use in HTML. |
| `cfg()` | 58 | The methods and their tools from window.PRJ_TOUR, or empty when the page sent none. |
| `mount(el)` | 61 | Builds the map into el - the explore / walk switch, the method filter, a column of part buttons per group and the side panel - and wires its clicks, arrow keys and resize. |
| `onClick(e)` | 88 | Handles clicks on the map - a part selects it (leaving the walk), the mode switch changes mode, and the walk's Back / Next / Restart step it. |
| `syncMode()` | 97 | Highlights the button of the current mode, explore or walk. |
| `isOff(tool)` | 100 | True when the chosen method does not switch on the tool behind a part. |
| `related(id)` | 102 | What a part feeds and what feeds it. |
| `draw()` | 105 | Redraws the map's state - lit, related, dimmed and switched-off parts - the side panel and the lines. |
| `nodePanel(id)` | 122 | The side panel for a chosen part - what it is, where to find it, which methods switch it on, and buttons for what it feeds and is fed by. |
| `chips(list)` | 127 | A button per related part. |
| `walkPanel()` | 137 | The side panel for the current walk step - step count and dots, its title and text, and Back / Next (Restart on the last). |
| `lines()` | 148 | Lines between the lit box and what it feeds / is fed by (explore), or between the step's boxes (walk). |
| `rect(id)` | 154 | A part's box on the map, relative to the map's corner. |
| `ends(a, b)` | 156 | From edge to edge, so the arrowhead is never under a box: side by side across columns, else top/bottom. |

## Loaded by

- [projects/tutorial.php](/code/files/projects/tutorial.php.md)

