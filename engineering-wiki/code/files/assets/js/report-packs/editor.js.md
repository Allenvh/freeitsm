# assets/js/report-packs/editor.js

> JavaScript - 449 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Report Packs Internals 1 The Designer](https://github.com/edmozley/freeitsm/wiki/Report-Packs-Internals-1-The-Designer)

Report Packs - editing rich text in place.

A text box, the header, the footer or the cover is edited by laying a
contentEditable box over it on the page, styled to match. The ribbon's Home
tab drives it (bold, colour, lists, alignment...). When editing ends, the
HTML is read back into the paragraph model (docToHtml / htmlToDoc) and the
engine lays it out again.

The HTML here is only ever the analyst's own, built by docToHtml from the
stored model with every piece of text escaped; it is never stored. What is
stored is the model, which the server checks field by field.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 21 | Translates a key under reporting.packs.*, or returns the key when the i18n helper is not loaded. |
| `esc(s)` | 24 | HTML-escapes a string (&, <, > and double quotes). |
| `runHtml(r)` | 29 | ── Model -> HTML (for the editor) ───────────────────────────────────── |
| `docToHtml(doc, logoUrl)` | 52 | Turns the stored paragraph model into HTML for the in-place editor - headings, paragraphs, nested ul / ol lists, the logo image and field chips. |
| `closeTo(lv)` | 56 | Closes every open list at or deeper than the given level. |
| `hex(colour)` | 85 | ── HTML -> model (when editing ends) ────────────────────────────────── |
| `sizePt(v)` | 99 | Converts a CSS font size in px or pt to points, rounded to the nearest half point and clamped to 6-72; null for anything else. |
| `styleOf(node, inherited)` | 108 | Read a run style from an element and inherited style. |
| `pushRun(runs, text, st)` | 136 | Appends a text run with its style to the run list, merging it into the previous run when the styles match. |
| `collectRuns(node, st, runs)` | 153 | Walks an element's children into text runs - text, line breaks and field chips - carrying inherited styles down; block elements are skipped as they are handled separately. |
| `alignOf(el)` | 179 | Returns an element's text alignment (center, right or justify), or left. |
| `trimRuns(runs)` | 185 | Drops trailing line-break runs and empty text runs. |
| `htmlToDoc(root)` | 197 | Reads the editor's HTML back into the paragraph model - paragraphs, headings (h4-h6 become h3), list items with levels up to 4, and the logo. |
| `walk(container, listCtx)` | 203 | Walks one container's children into the model; inline nodes between blocks are gathered and flushed as a paragraph. |
| `flushLoose()` | 206 | Turns the gathered loose inline nodes into one paragraph, if they hold any text. |
| `wrapList(list)` | 263 | Wraps a copy of a list in a div so `walk()` can treat it as a container. |
| `restoreRange()` | 282 | Puts back the last selection made in the editor, when a ribbon control has taken it away. |
| `open(opts)` | 297 | Open an editor over a rectangle (CSS px, relative to `host`). opts: {host, left, top, width, minHeight, doc, theme, zoom, logoUrl, onDone(doc), onInput} |
| `scaleFont(node, pxPerMm)` | 336 | Turns an element's pt font size into on-screen px for the zoom, keeping the pt value in data-pt. |
| `onPaste(e)` | 342 | Pasting brings plain text only: a report's styles come from the report. |
| `placeCaretAtEnd(el)` | 349 | Moves the caret to the end of an element. |
| `close(commit)` | 358 | End editing. commit=false (Escape) still keeps the text - Word has no "discard". |
| `isActive()` | 375 | Whether an in-place editor is open. |
| `element()` | 377 | Returns the open editor element, or null. |
| `cmd(name, value)` | 380 | ── Commands from the ribbon ─────────────────────────────────────────── |
| `wrapSelection(fn)` | 405 | Wraps the current non-empty selection in a new span that fn styles, then selects the span. |
| `state()` | 418 | What the cursor is sitting in, so the ribbon can show it (B pressed etc.). |
| `q(c)` | 421 | Asks the browser whether a formatting command is on at the caret, treating an error as off. |
| `setLogoWidth(mm)` | 440 | Set the width (mm) of the logo the cursor is next to. |

## Loaded by

- [reporting/packs/designer.php](/code/files/reporting/packs/designer.php.md)

