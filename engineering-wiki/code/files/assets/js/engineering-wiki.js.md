# assets/js/engineering-wiki.js

> JavaScript - 681 lines

Engineering wiki - the small browser round the pages (System -> Engineering wiki).

Model:
  panes[]   one, or two side by side ("Open beside")
    tabs[]  each tab has its own history (a list of "page#anchor") and position
  the ACTIVE pane is the one the toolbar (back / forward / address) acts on.

Links in a page carry data-ew-page. Click = open here; Ctrl/Cmd+click or middle-
click = new tab; right-click = Open / Open in new tab / Open beside / Copy link.
The open tabs are remembered in this browser (localStorage) - a convenience only;
?page= in the address always wins.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `narrow()` | 31 | True on a phone-width screen (900px or less), where there is one pane and the sidebar slides in. |
| `esc(s)` | 46 | Escapes a value for use in HTML text or an attribute. |
| `splitTarget(t)` | 48 | Splits a "page#anchor" target into [page, anchor]; the anchor is '' when there is none. |
| `pageUrl(target)` | 50 | Returns the full address of a wiki page (with ?page=), for Copy link. |
| `fetchPage(page)` | 59 | Fetches one rendered page from the API, from the cache when it has been loaded before. |
| `newTabObj(target)` | 70 | Creates a new tab object with its own history, starting at the given target. |
| `cur(tab)` | 72 | Returns the target a tab is showing now - its current history entry. |
| `openIn(paneIdx, target, background)` | 79 | Opens a target in a new tab in a pane, after the active tab; a pane index past the end adds a new pane. |
| `navigate(paneIdx, target)` | 99 | Goes to a target in a pane's active tab, adding it to the tab's history like a browser does. |
| `go(delta)` | 114 | Moves the active tab back (-1) or forward (1) through its history. |
| `closeTab(paneIdx, tabIdx)` | 128 | Closes a tab and picks the tab to show next. |
| `openBeside(target, fromPane)` | 143 | Opens a target in the other pane, making a second pane if needed; on a narrow screen, a new tab. |
| `paneView(i)` | 152 | Returns the scrolling page area of a pane, or null when the pane is not drawn. |
| `renderAll()` | 159 | Rebuilds every pane, its tab strip and the divider between two panes, with empty page areas. |
| `loadTitle(tab)` | 186 | Fetches a background tab's page so its tab can show the real title. |
| `renderTabStrips()` | 192 | Updates the titles in every tab strip without rebuilding the panes. |
| `crumbs(page)` | 203 | Returns the breadcrumb HTML for a page path, naming the top folder Handbook or Code map. |
| `showTab(pi)` | 219 | Draws a pane's active tab: fetches its page and renders the header, badges, outline and body. |
| `updateToolbar()` | 270 | Syncs back/forward, the address box, the window title and ?page= in the URL with the active tab. |
| `highlightActive()` | 281 | Marks which pane is the active one. |
| `save()` | 288 | Remembers the open panes and tabs in localStorage, keeping the last 30 history entries per tab. |
| `restore()` | 302 | Puts back the panes and tabs saved in localStorage - one pane on a narrow screen. |
| `treeRow(item, isDir)` | 317 | Builds one sidebar tree item - a folder that expands, or a link to a page. |
| `loadDir(dir, into)` | 336 | Fetches one folder's pages and subfolders and adds them to the sidebar tree under an element. |
| `buildTree()` | 351 | Rebuilds the sidebar with its two top sections, Handbook and Code map. |
| `markCurrent()` | 365 | Highlights the sidebar entry for the page the active tab is showing. |
| `runSearch(q)` | 377 | Searches the wiki and shows the results in the sidebar; under two characters it shows the tree again. |
| `closeMenu()` | 398 | Removes the right-click menu, if one is open. |
| `showMenu(x, y, items)` | 402 | Shows a right-click menu of items (icon, label, run) at a point, kept inside the window; '-' is a separator. |
| `linkMenu(x, y, target, paneIdx)` | 420 | Shows the right-click menu for a page link: Open, Open in new tab, Open beside and Copy link. |
| `copy(text)` | 433 | Copies text to the clipboard, falling back to a hidden textarea outside a secure context, then shows a toast. |
| `done()` | 435 | Shows the "link copied" toast. |
| `fallbackCopy(text)` | 440 | Copies text with a hidden textarea and execCommand, for browsers without the async clipboard. |
| `toast(msg)` | 449 | Shows a short message at the bottom of the page for 1.6 seconds. |
| `bindDivider(div)` | 460 | Makes the divider between two panes draggable, sharing the width between them (20% to 80% each). |
| `move(ev)` | 466 | Resizes the two panes to follow the mouse while the divider is dragged. |
| `up()` | 473 | Ends a divider drag and removes its mouse listeners. |
| `paneOf(el)` | 482 | Returns the index of the pane an element is in, or the active pane when it is in none. |
| `closeOutlines()` | 552 | Closes any open page outline. |
| `toggleOutline(btn, pi)` | 554 | Opens or closes the outline (the page's headings) under a pane's Outline button. |
| `rescanStep()` | 643 | Runs the Code map scanner one step at a time through the API, showing progress, until it reports done. |

## Traps

> [!WARNING]
> **Line 86:** `renderAll()` rebuilds EVERY pane's DOM, so every pane is redrawn (from

> [!WARNING]
> **Line 250:** not "target" - a var here is hoisted over the whole callback and

