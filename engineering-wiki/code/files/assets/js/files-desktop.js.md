# assets/js/files-desktop.js

> JavaScript - 2096 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Files Developer Guide](https://github.com/edmozley/freeitsm/wiki/Files-Developer-Guide)

Files — the desktop's apps: desktop icons, the start menu, Explorer windows, uploads, the clipboard, Permissions, Properties, Recent, Search, Personalise, Help, and the phone list view.

Windows, menus and dialogs come from files-wm.js (window.FilesWM); icons from
files-icons.js. Everything the server knows comes from api/files/ - and the
server decides every permission. The levels this file reads only decide which
menu items are offered; a hidden button is a courtesy, never the protection.

Levels (includes/files/acl.php): 1 View, 2 Download, 3 Upload, 4 Modify, 5 Full control.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `LEVEL_NAMES()` | 26 | Returns the translated name of each access level (1 View to 5 Full control), keyed by number. |
| `api(path, body, method)` | 34 | ── Small helpers ────────────────────────────────────────────────────── |
| `badReply(status, text)` | 51 | A reply that was not JSON, said in words. "HTTP 200" alone told nobody anything: the usual cause is a PHP warning printed ahead of the JSON (with display_errors on) or a proxy's own page, so show the start of what came back. |
| `hasWildcard(q)` | 64 | A name test for what someone typed. With * or ? it is a Windows-style wildcard over the WHOLE name (*.png ends in .png, report* starts with report); without, it is "contains". Case never matters. The same rules as api/files/search.php, which does this for the Search window. |
| `nameMatcher(q)` | 68 | Returns a test function for a name: a whole-name wildcard match when the text holds * or ?, otherwise a case-insensitive "contains". |
| `fail(e)` | 75 | Shows an error (or anything thrown) as an error notification. |
| `fmtSize(n)` | 77 | Formats a byte count as B, KB, MB, GB or TB with sensible rounding; empty for null. |
| `fmtWhen(s)` | 87 | Formats a date-time with the app's fmtDateTime when it is loaded, else returns it as given; empty for none. |
| `typeName(name)` | 91 | Returns the friendly type of a file from its name ("PDF document", "Spreadsheet", "PNG file"), as shown in Details view and Properties. |
| `debounce(fn, ms)` | 103 | Returns a wrapper that runs fn only once calls have stopped for ms milliseconds. |
| `h(tag, cls, html)` | 108 | Creates an element with an optional class and optional inner HTML. |
| `svgIcon(path)` | 115 | Wraps SVG path markup in a 16px stroked icon, for the toolbar and menu icons in IC. |
| `geoFor(kind)` | 147 | Returns the remembered size and position for a kind of window, or null when there is none. |
| `changed(folderIds)` | 154 | Tells every open Explorer (and the phone list) that these folders changed, so each refreshes what it shows; an empty list means refresh everything. |
| `setClip(mode, entries, from)` | 163 | Puts entries on the clipboard as a cut or a copy (or clears it when there are none) and repaints the faded cut look in every Explorer. |
| `paste(target)` | 169 | Paste is a drop that came via the clipboard: files and folders through transfer.php, shortcuts through shortcut.php 'place' (dropEntries). |
| `transfer(mode, entries, from, target)` | 178 | Moves or copies files and folders into a target folder through transfer.php, reports each one that failed, then refreshes both folders. |
| `shortcutIcon(targetType, targetName, px)` | 186 | A target's icon with the shortcut arrow over its corner. |
| `shortcutMenu(type, id, canHere, disabled)` | 195 | "Create shortcut >" for a file or folder: beside it, or on my desktop. |
| `createShortcut(type, id, where, folderId)` | 206 | Creates a shortcut to a file, folder or Knowledge article through shortcut.php - in a folder or on the desktop - then refreshes where it landed. |
| `openShortcutTarget(s, inExplorer)` | 222 | Open what a shortcut points to. A folder opens in `inExplorer` when given (else a new window); a file in the viewer; a Knowledge article in Knowledge, in a new tab - where Knowledge's own permissions apply again. |
| `previewArticle(id)` | 233 | A Knowledge article in a desktop window, with a button to open it in Knowledge itself (new tab). ⚠️ Knowledge stores bodies as raw HTML and does not sanitise them, so the body goes into a SANDBOXED frame - no scripts, no same-origin access - never into the desktop's own DOM. |
| `v(n, d)` | 247 | Reads a theme colour variable from the page, or the fallback when it is not set. |
| `pickArticle(where, folderId)` | 255 | "Shortcut to article…" - pick a Knowledge article, then place a shortcut to it. |
| `shortcutLocation(s)` | 281 | Open the folder holding a shortcut's target, with the target selected. |
| `viewFile(itemId, versionId)` | 287 | Open a file in the viewer (files-viewer.js). Every open is a 'view' in the audit trail. |
| `onProperties(id)` | 292 | Opens Properties for the file shown in the viewer. |
| `download(itemId, versionId)` | 297 | Downloads a file (or one version of it) by clicking a temporary link to download.php. |
| `Explorer(folderId, opts)` | 309 | EXPLORER |
| `onClose()` | 328 | Removes this Explorer from the list that hears about folder changes when its window closes. |
| `onFocus()` | 330 | Puts keyboard focus on the file pane when the window is focused, unless the filter box has it. |
| `arrow(k)` | 525 | Returns the up or down sort arrow for a Details column header when that column is the sort. |
| `min(need)` | 590 | True when something is selected and every selected entry has at least the given access level. |
| `set(act, on)` | 610 | Enables or disables one command-bar button. |
| `toRef(e)` | 663 | Reduces an entry to the type, id and name the clipboard and drag data carry. |
| `finish(commit)` | 711 | Ends an in-place rename once: saves the new name through the folder, shortcut or item API when it changed, otherwise just redraws. |
| `sortBy(k)` | 794 | Returns a menu action that sorts by a column, flipping the direction when it is already the sort. |
| `move(e)` | 894 | Draws the rubber-band rectangle as the pointer moves and selects every entry it touches (after 5px, so a click stays a click). |
| `up()` | 911 | Ends the rubber-band selection: unhooks the handlers and hides the rectangle. |
| `kind(ev)` | 947 | Says what is being dragged: 'entries' from an Explorer or the desktop, 'files' from the computer, or null. |
| `dropEntries(payload, target, copy)` | 988 | Entries dropped on a folder (target; 0 = the top level). Files and folders move or copy through transfer.php; shortcuts - from a folder or from the desktop - through shortcut.php 'place'. The server checks every one. |
| `dropOnDesktop(payload, copy)` | 1012 | Entries dropped on the desktop. Nothing is STORED on the desktop, so a file or folder dropped there becomes a desktop shortcut to it (Windows' "Create shortcuts here"); a shortcut dragged from a folder moves to the desktop - or, with Ctrl, is copied there and stays in the folder too. |
| `move(e)` | 1061 | Resizes the folder tree as the splitter is dragged, between 120px and 480px. |
| `up()` | 1063 | Stops the splitter drag by unhooking its pointer handlers. |
| `Tree(ex)` | 1072 | ── The left-hand folder tree ────────────────────────────────────────── |
| `pick(folderId)` | 1160 | Opens the computer's file picker to upload into a folder. |
| `add(files, folderId)` | 1165 | Queues files for upload into a folder (refusing any over the size limit or with no folder), opens Transfers and starts the queue. |
| `pump()` | 1180 | Starts queued uploads until two are running; each one finishing calls it again for the next. |
| `askConflict(u, name)` | 1196 | Asks whether to replace, keep both or skip when the folder already has a file of that name, and remembers a "do this for all" answer for the rest of the batch. |
| `run(u, onConflict)` | 1220 | Uploads one file: starts it with upload.php (asking about a name clash first), sends the chunks, finishes it and refreshes the folder. |
| `sendChunks(u, chunk)` | 1250 | Sends the file to upload.php in chunks of the size the server asked for, one after another, updating progress as it goes. |
| `next(offset)` | 1255 | Sends the chunk starting at offset, then carries on from what the server says it has received. |
| `onload()` | 1266 | Checks the server's reply to a chunk and moves on, or fails with the server's error or a description of a non-JSON reply. |
| `onerror()` | 1275 | Fails the upload when the connection drops. |
| `cancel(u)` | 1285 | Cancels a waiting or running upload, aborting the request and telling the server to throw away what it has. |
| `openWindow(quiet)` | 1295 | Opens the Transfers window, or brings it forward when it is already open (unless quiet). |
| `onClose()` | 1300 | Forgets the Transfers window when it closes. |
| `statusText(u)` | 1314 | Returns the status line for one upload: waiting, progress, uploaded (with its version), skipped, cancelled or the error. |
| `pct(u)` | 1326 | Returns how far one upload has got, as a whole percentage. |
| `paintOne(u)` | 1329 | Updates one upload's progress bar and status in the Transfers window, and the taskbar tray. |
| `paint()` | 1339 | Redraws the whole Transfers list (with Cancel and "Get help with this" buttons where they apply) and the taskbar tray. |
| `paintTray()` | 1363 | Shows the taskbar upload indicator with the count and overall progress of active uploads, or hides it when none are running. |
| `openPermissions(folderId)` | 1379 | PERMISSIONS |
| `load()` | 1391 | Fetches the folder's permissions, keeps its own (not inherited) entries as the editable list, and draws the window. |
| `render()` | 1407 | Draws the Permissions window - own and inherited entries, inherit tick, watermark, add-a-person search and buttons - read-only without Full control. |
| `who(e)` | 1434 | Returns the escaped name of a permission entry, with a Team badge for a team. |
| `save()` | 1524 | Saves the folder's own permission entries, first warning when the person is lowering their own Full control; closes the window when they can no longer see the folder. |
| `openProperties(type, id)` | 1554 | PROPERTIES |
| `rows(pairs)` | 1565 | Builds the label and value table rows for Properties, leaving out any with no value. |
| `openLocation(folderId, itemKey)` | 1628 | Open the folder a file lives in, with the file selected. |
| `resultsTable(container, rows)` | 1635 | Draws a Details-style list of files and folders for Recent and Search: double-click opens, right-click offers open, location, download, desktop shortcut and Properties. |
| `go()` | 1648 | Opens a result's location: a folder in a new Explorer, a file's folder with the file selected. |
| `openRecent()` | 1668 | Opens the Recent window listing files the person recently used, from recent.php. |
| `openSearch()` | 1679 | Opens the Search window, which searches file and folder names through search.php as the person types (two letters, or a wildcard). |
| `openPersonalise()` | 1703 | Opens Personalise, where the person picks their desktop colour and how the top navigation bar shows; both save to their own preferences. |
| `setColour(c)` | 1724 | Applies a desktop colour straight away, marks its swatch and saves it (debounced). |
| `openHelp(section)` | 1743 | Help, optionally at one section (an id in files/help.php, e.g. 'upload_errors'). |
| `appByKey(k)` | 1771 | Returns the app (Documents, Recent, Search...) with this key. |
| `renderDesktopIcons()` | 1776 | Draws the desktop's app icons (double-click or Enter opens), then the person's desktop shortcuts after them. |
| `loadDesktopShortcuts()` | 1807 | Fetches the person's desktop shortcuts from shortcut.php and redraws the desktop icons. |
| `renderDesktopShortcuts(box)` | 1813 | Adds a desktop icon for each desktop shortcut - open, drag into a folder, rename, delete, Properties - greyed out when its target has gone. |
| `openIt()` | 1822 | Opens what a desktop shortcut points to, or says it has gone. |
| `removeIt()` | 1827 | Asks, then deletes a desktop shortcut (never what it points to) and redraws the desktop. |
| `dragKind(ev)` | 1879 | Says what is being dragged over the page: 'entries' from Files, 'files' from the computer, or null. |
| `closeStart()` | 1930 | Hides the start menu. |
| `openStart()` | 1932 | Builds and shows the start menu: search box, apps, the other FreeITSM modules, recent files and Sign out. |
| `load(id)` | 2020 | Loads a folder into the phone list, falling back to the top level when it cannot be opened. |
| `render()` | 2028 | Draws the phone list of the current folder: a bar with back, upload and new folder, then its folders, files and shortcuts. |

## Loaded by

- [files/index.php](/code/files/files/index.php.md)

## Traps

> [!WARNING]
> **Line 1908:** a file from the computer dropped where nothing accepts it (a

