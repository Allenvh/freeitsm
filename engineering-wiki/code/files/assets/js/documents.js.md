# assets/js/documents.js

> JavaScript - 689 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Attached Documents Developer Guide](https://github.com/edmozley/freeitsm/wiki/Attached-Documents-Developer-Guide)

Documents panel — attach files or DMS links to any record (discussion #76).

ONE component for every module. Mount it and give it a parent:

    FreeITSMDocuments.mount(element, {
        parentType: 'contract',
        parentId:   42,
        apiBase:    '../api/documents/',
        canEdit:    true
    });

The component knows nothing about any module, and no module knows anything
about how documents are stored or authorised. The server decides both — see
includes/documents.php.

⚠️ window.t IS GUARDED HERE. The notification bell called it bare and broke on
the one page that did not load i18n.js, and because the throw happened above
the try, it never even made its request (GH #78). Same pattern as calendar.js
and war-room.js: fall back to English rather than take the page down.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `t(key, params)` | 77 | Translates a key under common.documents, falling back to the English in FALLBACK with {name} placeholders filled. |
| `esc(s)` | 93 | HTML-escapes a value for building the panel's markup. |
| `bytes(n)` | 100 | Formats a byte count as B, KB or MB. |
| `safeHref(url)` | 108 | Only ever produce an href we built, from a scheme we checked. |
| `icon(kind)` | 113 | The inline SVG icon for a document row - a chain link for a DMS link, a page for an uploaded file. |
| `Panel(el, opts)` | 124 | One Documents panel mounted on a record: reads the options, draws the panel and loads the first page of documents. |
| `uploadFiles(api, parentType, parentId, files)` | 392 | Upload files against a parent, one request per file. |
| `closeInfo()` | 580 | Removes the document details dialog and its Escape handler. |
| `onInfoKey(e)` | 586 | Closes the document details dialog on Escape. |
| `showInfo(apiBase, documentId, baseUrl)` | 593 | Opens the document details dialog: what the document is, whether its contents are searchable, and every record it is attached to. |
| `mount(el, opts)` | 670 | Mounts a Documents panel in an element (or a selector) and returns it, or null when the element is missing. |
| `upload(parentType, parentId, files, apiBase)` | 684 | Attach already-chosen files to a parent, without a panel. |

