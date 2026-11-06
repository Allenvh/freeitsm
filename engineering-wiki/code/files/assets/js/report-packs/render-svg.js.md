# assets/js/report-packs/render-svg.js

> JavaScript - 133 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Report Packs Internals 2 The Layout Engine](https://github.com/edmozley/freeitsm/wiki/Report-Packs-Internals-2-The-Layout-Engine)

Report Packs - draw the engine's pages on screen, as SVG in millimetres.

This renderer makes no layout decisions. Every x, y and width comes from
engine.js, and each text run is pinned to the width the engine measured
(textLength) - so if the browser's Arial differs a hair from the PDF's
Helvetica, the run is nudged to fit rather than wrapping differently.

Text is set with textContent only; nothing from a pack is ever parsed as markup.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `el(name, attrs, parent)` | 23 | Creates an SVG element with the given attributes (null and undefined skipped), appending it to parent when given. |
| `fontAttrs(st)` | 31 | Returns the SVG font attributes for a run style - family, size in mm, weight, style and colour. |
| `drawText(g, x, y, text, st, w)` | 45 | Draws a run of text pinned to the engine's measured width, with its highlight, underline or strike-through. |
| `drawPrim(g, d)` | 60 | Draws one engine primitive (text, rect, line or dot leader) into the SVG group. |
| `drawRich(g, rich, logoUrl)` | 83 | Draws laid-out rich text - the logo (or a grey box when there is none), list markers and text runs - at its position. |
| `page(pg, G, opts)` | 99 | Draw one page into a new <svg>. opts: {logoUrl, chartImage(item) -> dataURL\|null} |

## Loaded by

- [reporting/packs/designer.php](/code/files/reporting/packs/designer.php.md)

