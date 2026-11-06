# assets/js/files-viewer.js

> JavaScript - 691 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Files Developer Guide](https://github.com/edmozley/freeitsm/wiki/Files-Developer-Guide)

Files — the viewer. Opens a file in a desktop window (or full screen on a phone) without downloading it.

PDF          Mozilla pdf.js (assets/js/vendor/pdfjs/), pages drawn to canvas
               as they scroll into view; pdf.js fetches only the byte ranges it
               needs, so a 300-page manual opens at page one straight away.
  Word .docx   mammoth.js turns the document into plain HTML, which is then
               SANITISED here against an allowlist before it touches the page.
  Spreadsheets SheetJS, one tab per sheet, cells written as text only.
  Images, video, audio  the browser's own elements.
  Text and code shown as text, never as markup.
  Zip archives  browsed like a folder (SheetJS's own zip reader, XLSX.CFB); any
               entry opens in this same viewer. Entries are NOT fetched from the
               server one by one - the browser unpacks the zip in memory and the
               viewer works from those bytes (pdf.js from data, media from a blob:
               URL). Opening or downloading an entry is still audited: view.php's
               'entry' action records which one.

The bytes come from api/files/view.php through a short-lived token that lives
in the person's session; download.php is only used when they may download.

VIEW-ONLY (no Download permission): no download button, no right-click, no
text selection, nodownload on media, and printing blanks the page. A deterrent,
not DRM - the watermark (a per-folder setting) is what makes a leak traceable,
and it is drawn over whatever is shown, so it is in any screenshot.

TRAP: libraries load on first use (pdf.js alone is ~1.7 MB) - never add them
to files/index.php's <script> tags.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `api(body)` | 46 | POSTs an action to api/files/view.php and rejects when the reply says success: false. |
| `h(tag, cls, html)` | 52 | Creates an element with an optional class and inner HTML. |
| `fmtSize(n)` | 54 | Formats a byte count as B, KB, MB, GB or TB. |
| `icon(path)` | 61 | Wraps SVG path markup in a 16px stroked toolbar icon. |
| `loadScript(src)` | 74 | Loads a library script once, on first use, and returns the same promise to every later caller. |
| `onerror()` | 81 | Rejects the load with the script's address when it fails to load. |
| `loadPdfjs()` | 89 | Imports pdf.js (an ES module) once and points it at its worker script. |
| `fetchBytes(url, cap)` | 105 | Fetches a file's bytes as an ArrayBuffer, refusing early when Content-Length is over the cap. |
| `sanitise(html)` | 127 | Cleans mammoth's HTML against an allowlist before it is shown. |
| `extOf(name)` | 175 | Returns a file name's extension in lower case, or an empty string. |
| `kindOf(name)` | 177 | Returns which viewer handles a file name (pdf, docx, sheet, image, video, audio, zip, text) or 'none'. |
| `auditEntry(entry, what)` | 183 | Opening or saving something INSIDE a zip happens in the browser - so tell the server, for the audit trail. |
| `colName(i)` | 188 | ── Spreadsheet column letters ───────────────────────────────────────── |
| `renderInto(host, info, opts)` | 194 | Draw a file into `host` (a window body or the phone overlay). info = view.php's open response. Returns a controller {destroy()}. |
| `getBytes(cap)` | 200 | The file's bytes - from memory for a zip entry, else from view.php. |
| `mediaUrl()` | 208 | A URL an <img>/<video> can use: view.php's, or a blob: for a zip entry (typed by us, never SVG). |
| `baseDestroy()` | 267 | Frees every blob: URL this viewer made for zip entries. |
| `error(e)` | 269 | Replaces the stage with an error message, dropping the kind's stage styling so the text is visible. |
| `noPreview()` | 274 | Shows a large file icon, the name, the size and a Download button for a kind the viewer cannot show. |
| `zoomTools(onZoom)` | 287 | Puts zoom out, zoom in and fit buttons in the bar and calls onZoom('out' \| 'in' \| 'fit') when one is clicked. |
| `renderPdf()` | 310 | ── PDF ──────────────────────────────────────────────────────────── |
| `fitScale()` | 318 | Works out the scale that makes the first page fill the stage's current width (0.25 to 3). |
| `destroy()` | 348 | Stops the PDF viewer - observers disconnected and the pdf.js loading task destroyed. |
| `layout()` | 375 | Sizes every page box for the current scale and redraws the pages on or near the screen. |
| `draw(pg)` | 394 | Renders one PDF page to a canvas at the current scale (sharp on high-density screens). |
| `updatePage()` | 420 | Updates the "n of total" label from the page that sits a third of the way down the stage. |
| `renderDocx()` | 429 | ── Word ─────────────────────────────────────────────────────────── |
| `renderSheet()` | 453 | ── Spreadsheets ─────────────────────────────────────────────────── |
| `show(name)` | 465 | Draws one sheet as a grid with column letters and row numbers, cells as escaped text. |
| `renderImage()` | 505 | ── Images ───────────────────────────────────────────────────────── |
| `fitZoom()` | 516 | Returns the zoom that fits the whole image in the stage, never above 100%. |
| `apply()` | 518 | Sets the image's width for the current zoom and updates the percentage. |
| `onload()` | 520 | Puts the loaded image on the stage, fitted to it. |
| `onerror()` | 522 | Shows "The file could not be shown" when the image fails to load. |
| `renderMedia()` | 527 | ── Video and audio ──────────────────────────────────────────────── |
| `onerror()` | 537 | Shows that the browser cannot play the file when the video or audio fails. |
| `destroy()` | 543 | Stops the media and drops its source so the browser lets go of the stream. |
| `renderZip()` | 550 | ── Zip archives ─────────────────────────────────────────────────── A folder view of the archive; opening an entry swaps the stage for that entry's own viewer, with a way back to the list. Nested zips just work - an entry that is a zip is browsed the same way. |
| `showList()` | 579 | Lists the zip folder currently open - subfolders first, then files in natural order - with breadcrumbs back up. |
| `openEntry(f)` | 613 | Opens a file inside the zip in a nested viewer, from the bytes already in memory, with a Back bar. |
| `destroy()` | 642 | Tears down the zip entry viewer that is open, if any. |
| `renderText()` | 648 | ── Text ─────────────────────────────────────────────────────────── |
| `open(id, versionId, opts)` | 659 | Open a file in a viewer window. versionId optional (Properties -> Versions). |
| `onClose()` | 669 | Tears down the viewer (PDF tasks, media, blob URLs) when its window closes. |
| `openPhone(info)` | 678 | Phones: the same viewer, full screen over the list. |

## Loaded by

- [files/index.php](/code/files/files/index.php.md)

## Traps

> [!WARNING]
> **Line 134:** elements inside <svg>/<math> report LOWER-case tag names, so

> [!WARNING]
> **Line 312:** "fit" must be MEASURED when it is asked for. It was worked out once

> [!WARNING]
> **Line 560:** SheetJS's reader adds a marker entry "\u0001Sh33tJ5"; no real

