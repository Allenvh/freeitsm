# assets/js/report-packs/engine.js

> JavaScript - 977 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Report Packs Internals 2 The Layout Engine](https://github.com/edmozley/freeitsm/wiki/Report-Packs-Internals-2-The-Layout-Engine)

Report Packs - the layout engine.

Turns a pack's design (+ each block's data) into PAGES: every line of text,
table cell, bar and picture with its exact position in millimetres. Two
renderers draw those pages - render-svg.js on screen, render-pdf.js into the
PDF - and neither makes a layout decision of its own. That is the whole
reason the PDF matches the screen: there is only one place where a line
breaks or a table runs onto the next page, and it is here.

TEXT IS MEASURED WITH THE PDF'S OWN FONT METRICS
------------------------------------------------
Widths come from jsPDF (Helvetica, Times, Courier - the PDF standard fonts),
not from the browser. The screen then draws each line at the x the engine
chose, with textLength pinning its width, so a browser whose Arial is a
hair wider than Helvetica still breaks at the same word.

Those fonts cover Western European text (WinAnsi), which includes Spanish
accents and ñ. Characters outside it are drawn on screen but cannot go into
the PDF; `hasUnsupported()` lets the designer say so instead of letting them
silently turn into question marks.

Units: millimetres throughout. Font sizes are points; PT converts.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `styleKey(b, i)` | 46 | Returns the jsPDF font style name for bold / italic flags - normal, bold, italic or bolditalic. |
| `charUnits(font, style, ch)` | 54 | Returns a character's width in font units from jsPDF's metrics, cached per font and style. |
| `textWidth(str, st)` | 73 | Width in mm of `str` set in this style. |
| `hasUnsupported(str)` | 82 | Whether a string holds a character outside WinAnsi, which the PDF standard fonts cannot print. |
| `layoutDoc(doc, width, ctx)` | 103 | Lay out a rich-text document in a box `width` mm wide. ctx: {font, size, color, fields: {page, pages, ...}, logo: {w, h} \| null} Returns {height, lines: [...], images: [...]} with y measured from the top. |
| `tokenise(runs, base, ctx)` | 185 | Runs -> tokens: words, spaces and hard breaks, each with its style and width. |
| `fieldValue(name, fields)` | 211 | Returns a field's value (page, pages, title...) as a string, or empty when it is not set. |
| `breakLines(tokens, avail)` | 217 | Greedy line breaking. A word wider than the line is split by characters. |
| `push(hard)` | 224 | Ends the current line (dropping trailing spaces), records whether a hard break ended it, and starts a new one. |
| `mergeRuns(items)` | 260 | Join neighbouring tokens of identical style into one run (fewer SVG/PDF calls). |
| `sameStyle(a, b)` | 271 | Whether two run styles match in font, size, bold, italic, underline, strike, colour and highlight. |
| `wrapPlain(text, width, st)` | 277 | Plain text wrapped into lines - for table cells and captions. |
| `themeStyle(theme, extra)` | 300 | Returns a text style from the theme's font and size in dark grey, with any extra properties laid over it. |
| `txt(x, baseline, text, st, w)` | 305 | A line of plain text as a draw primitive. |
| `rect(x, y, w, h, fill, extra)` | 309 | Returns a rectangle draw primitive, with any extra properties (stroke, radius, dash) merged in. |
| `hline(x1, x2, y, color, width)` | 314 | Returns a horizontal line draw primitive; a null x2 means the full block width, filled in by `offset()`. |
| `baselineFor(top, size)` | 317 | Returns the text baseline for a line of the given point size whose top is at top (mm). |
| `onColour(hex)` | 320 | Contrast text for a coloured pill. |
| `pill(x, top, text, colour, size, theme)` | 328 | Measures and draws a rounded coloured pill with bold contrasting text; returns {w, h, draw}. |
| `measureHeading(b, theme)` | 342 | Measures a heading block - its text at the level's size in the heading colour, with a rule under level 1. |
| `measureText(b, width, theme, ctx)` | 357 | Measures a text block by laying out its rich text (inside a shaded box when box is set). |
| `titleDraw(b, data, width, theme)` | 371 | The block's title line (data blocks), with the "snapshot" caption under it. |
| `measureKpi(data, width, theme, top)` | 393 | Measures a KPI block - tiles of value, label and optional hint, as many to a row as fit at about 33mm each. |
| `fit(text, width, st)` | 416 | Truncate with an ellipsis to fit a width. |
| `measureTable(data, width, theme, top)` | 428 | Tables. Rows are the units a table splits on; the header repeats at the top of every page it continues onto. |
| `tableDraw(T, from, to, withFoot)` | 504 | One table "part": the header plus rows [from, to), positioned from y=0. |
| `shift(d, dy)` | 521 | Returns a copy of a draw primitive moved down by dy. |
| `measureUptime(data, width, theme, top)` | 528 | Service uptime: one unit per service - name, uptime, daily bars, incidents. |
| `pageGeometry(design)` | 593 | Returns the page width and height for the design's size and orientation, its margins, and the content width. |
| `layoutPart(part, width, theme, ctx)` | 602 | Header/footer/cover laid out for one page's fields. |
| `paginate(design, dataMap, ctx)` | 619 | The whole pack as pages. design the cleaned design dataMap block id -> {data} \| {error} \| undefined (still loading) ctx {fields: {...}, logo: {w,h}\|null, title, labels: {...}} |
| `fieldsAt(n, total)` | 623 | Returns the field values for a given page number and page total, plus the pack title. |
| `spanW(span)` | 633 | Returns the width in mm of a block spanning that many of the 12 grid columns. |
| `bodyTop(p)` | 657 | Returns where the body starts on a page - below the header when the page has one. |
| `bodyBottom()` | 659 | Returns where the body ends - above the footer. |
| `newPage()` | 665 | Starts a new body page and moves y to its top; the header shows unless it is turned off for the first page. |
| `placeRow(r)` | 720 | Places every block of a row side by side at its column on the current page, then moves y past the tallest. |
| `place(it, part, x)` | 730 | Put a block (or one page's part of it) on the current page at y. |
| `tocDraw(design, theme, headings, offset, part, G, headerH, footerH)` | 791 | Draws one contents page - the title on the first part, then its share of the headings with dot leaders and page numbers. |
| `blockSpan(b)` | 822 | Returns how many of the 12 columns a block spans - data, text and spacer blocks keep their own span, the rest are full width. |
| `offset(d, x, y, w)` | 827 | Move a primitive from block coordinates to page coordinates. |
| `measureBlock(b, entry, width, theme, ctx)` | 838 | Measure one block. Returns {h, draw, split?, chart?, rich?, minH?, state?}. |
| `placeholder(width, top, h, text, theme, isError)` | 894 | Draws the box a data block shows while it loads, or in red when its data failed. |
| `tableSplitter(T, title)` | 903 | Splitters: `start()` -> state; take(state, room, minUnits?) -> {n, h, draw, rest, continued} |
| `take(s, room, force)` | 910 | Takes as many table rows as fit in room (at least one when forced), with the header repeated; returns the drawn part and the rest. |
| `unitSplitter(units, title)` | 928 | Returns a splitter that breaks a list of units (one per uptime service) across pages, the title only on the first part. |
| `take(s, room, force)` | 935 | Takes as many units as fit in room (at least one when forced) and returns the drawn part and the rest. |
| `textSplitter(m)` | 950 | Returns a splitter that breaks a text block's laid-out lines across pages. |
| `take(s, room, force)` | 958 | Takes as many lines as fit in room (at least one when forced), with their images, rebased to the top of the part. |

## Loaded by

- [reporting/packs/designer.php](/code/files/reporting/packs/designer.php.md)

