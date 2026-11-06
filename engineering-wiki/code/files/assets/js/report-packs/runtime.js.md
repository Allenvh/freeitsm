# assets/js/report-packs/runtime.js

> JavaScript - 232 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Report Packs Internals 1 The Designer](https://github.com/edmozley/freeitsm/wiki/Report-Packs-Internals-1-The-Designer)

Report Packs - the runtime shared by the designer and by export.

Owns the things a pack needs besides its design: each block's data (fetched
with the VIEWER's rights, cached by handler + options + criteria so moving a
block never refetches it), the header fields for the period, the logo, and
the chart images. `layout()` asks the engine for pages; `exportPdf()` turns the
same pages into a file.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 16 | Translates a key under reporting.packs.*, or returns the key when the i18n helper is not loaded. |
| `create(apiBase, logoUrl)` | 22 | Creates a pack runtime for one API base and logo - the data cache, fetch queue, chart cache and the load, layout and export methods. |
| `post(path, body)` | 36 | POSTs JSON to a Report Packs endpoint; a reply that is not JSON becomes a failure with the block error message. |
| `keyFor(b)` | 42 | Returns the cache key for a data block - its handler, options and the pack's criteria. |
| `pump()` | 50 | Runs queued block_data.php fetches, at most four at once, storing each result and calling onChange (unless quiet). |
| `loadData()` | 68 | Start fetching whatever the design needs and is not cached. Resolves when all are in. |
| `fetchBlock(b)` | 87 | One block's data, through the same cache and queue (toolbox previews). |
| `refresh()` | 101 | Forget cached data (the Refresh button, or new criteria). |
| `dataMap()` | 107 | Returns block id -> cached {data} or {error} for every data block, or undefined while still loading. |
| `loadContext()` | 118 | The header fields for the current criteria. |
| `loadLogo()` | 129 | Loads the logo and rasterises it once to a PNG at print size; resolves to the logo or null. |
| `ctx()` | 155 | Returns the layout context for the engine - fields, title, logo size and the loading / snapshot labels. |
| `layout()` | 164 | Lays out the pack into pages with the current design and data. |
| `chartImage(item, scale)` | 169 | A chart item's picture at `scale`, cached on everything that changes it. |
| `checkUnsupported()` | 183 | True when some text cannot be put into the PDF's fonts. |
| `docText(d)` | 187 | Collects the plain text runs of a rich-text document into texts. |
| `fileName()` | 199 | Returns the PDF file name - the pack name plus the date period, with characters files cannot hold replaced. |
| `clean(s)` | 201 | Replaces characters not allowed in file names with "-" and collapses whitespace. |
| `buildPdf()` | 209 | Load everything, lay out, and build the PDF. Resolves to the jsPDF document. |
| `exportPdf()` | 221 | Builds the PDF and saves it as a download; resolves to the jsPDF document. |

## Loaded by

- [reporting/packs/designer.php](/code/files/reporting/packs/designer.php.md)

