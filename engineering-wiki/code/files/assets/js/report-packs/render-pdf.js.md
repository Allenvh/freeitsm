# assets/js/report-packs/render-pdf.js

> JavaScript - 147 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Report Packs Internals 2 The Layout Engine](https://github.com/edmozley/freeitsm/wiki/Report-Packs-Internals-2-The-Layout-Engine)

Report Packs - draw the engine's pages into a PDF with jsPDF.

The twin of render-svg.js: same primitives, same coordinates, same order.
Text is real PDF text in the standard fonts (searchable, selectable, sharp),
charts are images at ~300 dpi, and the logo is embedded ONCE under an alias
however many pages carry it (see the note in assets/js/form-pdf.js on what an
un-aliased logo does to file size).

## Functions

| Function | Line | What it does |
|---|--:|---|
| `rgb(hex)` | 18 | Converts a #rrggbb colour to an [r, g, b] array, falling back to dark grey. |
| `safe(text)` | 30 | Replaces characters outside WinAnsi with "?" (an open bullet becomes "-") so the PDF never prints garbage. |
| `setStyle(doc, st)` | 40 | Sets jsPDF's font, style, size and text colour from a run style. |
| `drawText(doc, x, y, text, st, w)` | 50 | Draws a run of text at its baseline, with its highlight behind it and underline or strike-through lines. |
| `drawPrim(doc, d)` | 67 | Draws one engine primitive (text, rect, line or dot leader) into the PDF. |
| `drawRich(doc, rich, logo)` | 98 | Draws laid-out rich text - the logo images (embedded once under the rp-logo alias), list markers and text runs - at its position. |
| `build(layout, opts)` | 114 | Build the PDF. layout = RPEngine.paginate(...) result. opts: {title, logo: {dataUrl}\|null, chartImage(item) -> PNG dataURL} |

## Loaded by

- [reporting/packs/designer.php](/code/files/reporting/packs/designer.php.md)

