# assets/js/report-packs/designer.js

> JavaScript - 1684 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Report Packs Internals 1 The Designer](https://github.com/edmozley/freeitsm/wiki/Report-Packs-Internals-1-The-Designer)

Report Packs - the designer (reporting/packs/designer.php).

Office-style: a ribbon (Home, Insert, Layout, Design, Data), a searchable
toolbox with live previews, real pages with rulers, a properties pane, drag
and drop with snapping to a 12-column grid, resize handles, text edited in
place, undo/redo, shortcuts and a right-click menu.

STATE AND RENDERING
  S.design is the single source of truth. Every change goes through `commit()`,
  which snapshots for undo, marks the pack unsaved and re-renders. Rendering
  asks the engine for pages (engine.js) and draws them as SVG (render-svg.js),
  with a layer of HTML frames on top for selection, handles and drop guides.
  Nothing here measures text or decides a page break.

Text from a pack (names, titles, data) is put on the page with textContent;
the only HTML this file writes is its own fixed markup.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 31 | Translates a key under reporting.packs, or returns the key itself when the translation helper is not loaded. |
| `canEdit()` | 47 | True when the signed-in analyst owns the pack or has edit rights on it. |
| `h(tag, attrs, ...kids)` | 50 | ── Small helpers ──────────────────────────────────────────────────── |
| `clone(o)` | 65 | Deep-copies a plain object through JSON. |
| `uid()` | 67 | Makes a short random block id starting with "b". |
| `byId(id)` | 69 | Finds a block in the current design by its id. |
| `idx(id)` | 71 | Returns a block's position in the design's block list, or -1. |
| `api(path, body, method)` | 80 | Calls a Report Packs API endpoint - a GET with no body, otherwise a JSON POST - and returns the parsed reply. |
| `icon(name, size)` | 143 | Builds an inline SVG icon from a path in ICON, 18px unless a size is given. |
| `commit(fn, opts)` | 159 | Every change to the design goes through here. opts.data the change affects what data is fetched (options, criteria) opts.merge coalesce with the previous step if it had the same key (typing in a field) |
| `undo()` | 188 | Steps back one change: closes any open text editor, pushes the current design on the redo stack and restores the previous snapshot and selection. |
| `redo()` | 198 | Re-applies the last undone change and pushes the current design back on the undo stack. |
| `setDirty(d)` | 211 | Records whether the pack has unsaved changes and updates the Saving / Unsaved / Saved label in the title bar. |
| `loadData()` | 222 | Fetches the data for every data block again and redraws when it arrives. |
| `boot()` | 227 | ── Boot ───────────────────────────────────────────────────────────── |
| `build()` | 255 | Builds the whole designer screen - title bar, ribbon, toolbox, canvas with ruler, properties pane and status bar - then binds the canvas and keyboard. |
| `iconBtn(name, title, fn, id)` | 295 | A small icon-only button with the title as its tooltip and accessible name. |
| `ribbonTabs()` | 300 | ── The ribbon ─────────────────────────────────────────────────────── |
| `buildRibbon()` | 315 | Redraws the ribbon: the tab strip and the groups of the active tab, then refreshes pressed and disabled states. |
| `rb(iconName, label, fn, opts)` | 330 | A ribbon button. big = icon over label (Office's large buttons). |
| `sel(options, value, onChange, opts)` | 349 | A ribbon drop-down from {value, label} options that calls onChange with the chosen value. |
| `colourPick(value, onChange, label, opts)` | 361 | A colour picker with a swatch that calls onChange when the colour is chosen (and while dragging when opts.live is set). |
| `textCmd(name, value)` | 377 | Returns a click handler that runs an editor command on the text being edited and refreshes the ribbon. |
| `homeGroups()` | 380 | The Home tab: clipboard, font, paragraph and paragraph style groups. |
| `ed()` | 382 | True while something is being edited in place. |
| `insertGroups()` | 432 | The Insert tab: cover and contents pages, page break, text blocks, header and footer, inline fields and the logo, and a button that jumps to the toolbox search. |
| `marginName(m)` | 465 | Names the margin preset (normal, narrow, wide) that matches the page margins exactly, or "custom". |
| `layoutGroups()` | 475 | The Layout tab: page size, margins and orientation, and the width, move and new-row controls for the selected block. |
| `widthGlyph(span)` | 503 | A little bar showing a width as a share of the 12-column grid. |
| `designGroups()` | 513 | The Design tab: theme font and body size, heading and table colours, chart palette and table stripes. |
| `set(k)` | 519 | Returns a setter for one theme property; quick repeated changes to the same property merge into one undo step. |
| `dataGroups()` | 553 | The Data tab: reporting period (with custom dates), company when there is more than one, and Refresh. |
| `setCr(fn)` | 560 | Applies a change to the criteria and refetches the data, as a viewer-only change when the analyst cannot edit. |
| `afterCriteria()` | 587 | Reloads the header context (dates, company) after a criteria change, then redraws the page and the ribbon. |
| `refreshRibbonState()` | 592 | Pressed/disabled state of the ribbon, from the selection and the cursor. |
| `TOOL_ICON(t)` | 616 | Picks the toolbox icon for a tool from its block type, or for a data block from its handler's kind (chart, table, KPI, uptime). |
| `buildToolbox()` | 621 | Builds the toolbox pane: the search box (Down arrow moves into the list), a hint and the tool list. |
| `renderTools()` | 642 | Redraws the tool list grouped by area, filtered by the search text. |
| `match(t)` | 645 | True when the search text appears in the tool's title, description, keywords or area name. |
| `blockFromTool(key)` | 682 | A block made from a toolbox item. |
| `insertTool(key, at)` | 694 | Double-click / Enter / ribbon: put the block after the selection, or at the end. |
| `schedulePreview(t, item)` | 709 | Shows a tool's live preview after a short pause, so moving across the list does not flash previews. |
| `hidePreview()` | 714 | Cancels a pending preview and removes the one showing. |
| `showPreview(t, item)` | 724 | Shows a popover beside a toolbox item with the block laid out alone on a page as wide as this pack's, using real data for data blocks. |
| `scheduleRender()` | 757 | Queues one render for the next animation frame, however many times it is called before then. |
| `pxmm()` | 764 | Screen pixels per millimetre at the current zoom. |
| `render()` | 773 | Lays the pack out and redraws every page as SVG with its overlay of margin guide, header/footer/cover hit areas and block frames, plus the rulers, status bar and properties pane. |
| `regionHit(kind, x, y, w, hgt)` | 822 | An invisible hit area over the header, footer or cover that opens it for editing on double-click. |
| `frame(it, k)` | 834 | The HTML frame over a laid-out block, used for selection; when selected and editable it gets a label tag and width or height handles. |
| `frameLabel(b)` | 862 | The name shown for a block: a data block's title (or its tool's), a heading's text, or the tool name for the type. |
| `hRuler(G, k)` | 870 | The horizontal ruler: centimetres, the margins, and the column grid of the selection's row. |
| `vRuler(G, k)` | 899 | Draws the vertical ruler beside a page in millimetres, with the top and bottom margins shaded. |
| `renderStatus()` | 919 | Redraws the status bar: page and block counts, the period, a warning when something cannot be exported, and the zoom controls. |
| `setZoom(z)` | 939 | Sets the zoom (40% to 200%, in steps of 10%), remembers it in the browser and redraws. |
| `fitWidth()` | 945 | Zooms so the page fits the width of the canvas. |
| `select(id, scrollTo)` | 952 | ── Selection and the properties pane ──────────────────────────────── |
| `renderProps()` | 967 | Redraws the properties pane - the pack's settings (description, pages, header, footer, margins) when nothing is selected, otherwise the selected block's settings. |
| `field(label, control, hint)` | 972 | A labelled control with an optional hint underneath. |
| `check(label, value, onChange)` | 974 | A labelled checkbox that calls onChange with its new state. |
| `upd(fn, opts)` | 976 | Commits a change to the selected block. |
| `selectCtl(options, value, onChange, ro)` | 1047 | A drop-down for the properties pane that calls onChange with the chosen value. |
| `optionControl(b, k, def, ro, upd)` | 1057 | The control for one of a data block's options, shaped by the handler's option definition (select, number, tick box or multi-choice list). |
| `set(val)` | 1060 | Saves the option's new value on the block and refetches its data. |
| `setSpan(id, span)` | 1078 | ── Block operations ───────────────────────────────────────────────── |
| `moveBlock(id, dir)` | 1083 | Moves a block one place up (-1) or down (1) in the design and keeps it selected. |
| `deleteBlock(id)` | 1097 | Deletes a block, selects the one now in its place (or the last) and announces it for screen readers. |
| `clip()` | 1113 | The copied block - from memory, or from localStorage so it can be pasted into another pack - or null. |
| `copyBlock()` | 1118 | Copies the selected block to the designer's clipboard and to localStorage when allowed. |
| `cutBlock()` | 1127 | Copies the selected block, then deletes it. |
| `pasteBlock()` | 1129 | Pastes the copied block, with a new id, after the selection or at the end. |
| `duplicateBlock()` | 1137 | Inserts a copy of the selected block straight after it and selects the copy. |
| `announce(msg)` | 1147 | Says a message to screen readers through a polite live region. |
| `bindCanvas()` | 1153 | ── Canvas interaction: select, drag, resize, edit ─────────────────── |
| `dropTarget(cx, cy, movingId)` | 1189 | Where a drop at (clientX, clientY) would land: {index, side, indicator} |
| `sideable(it)` | 1206 | True for the block types that can share a row (data, text, spacer). |
| `startDrag(e, what)` | 1242 | Starts dragging a toolbox item or an existing block; nothing happens until the pointer has moved 5px. |
| `move(ev)` | 1251 | Pointer move: starts the drag once past the threshold, then follows the pointer and updates the drop target. |
| `up()` | 1270 | Pointer up: tidies the drag away and applies the drop, if there is a target. |
| `showGuide(guide, t)` | 1289 | Places the drop guide on the target page - a vertical bar for a side drop, a horizontal line otherwise - or removes it when there is no target. |
| `autoScroll(cy)` | 1301 | Scrolls the canvas while the pointer is within 50px of its top or bottom edge. |
| `stopAutoScroll()` | 1308 | Stops the drag auto-scroll. |
| `applyDrop(what, t)` | 1311 | Put the dragged block where the guide was. Side drops share the row. |
| `rowOf(blocks, id)` | 1349 | The blocks sharing a row with `id`, as the engine flows them. |
| `startResize(e, id, edge)` | 1370 | Resizes a block from a handle: east or west changes its width in grid columns, south changes a chart's or spacer's height in millimetres. |
| `move(ev)` | 1387 | Pointer move: works out the new height or column span and resizes the frame to preview it. |
| `up()` | 1403 | Pointer up: removes the guides and commits the new height or span. |
| `editBlock(id)` | 1416 | ── Editing text in place ──────────────────────────────────────────── |
| `editHeading(b)` | 1433 | Edits a heading in place with a text box over its frame, sized to match the heading. |
| `done(keep)` | 1444 | Finishes editing the heading, saving the text when keep is true. |
| `editRegion(kind)` | 1460 | Opens the header, footer or cover for editing on the first page that has it, turning it on first if it is off. |
| `openEditor(what, pageEl, x, y, w, hgt, doc, onSave, label)` | 1486 | Opens the rich-text editor over a part of a page, at the page's drawn scale, and switches the ribbon to Home if another tab is showing. |
| `outside(e)` | 1511 | Closes the editor (keeping the text) on a click outside it, the ribbon and the title bar. |
| `contextMenu(x, y, id)` | 1526 | Shows the right-click menu for a block - edit, clipboard, move, width, new row and delete - kept on screen and focused on its first item. |
| `item(label, fn, opts)` | 1533 | A context menu item that closes the menu and runs its action, with an optional shortcut shown beside it. |
| `menuOutside(e)` | 1558 | Closes the context menu on a click outside it. |
| `closeMenu()` | 1560 | Removes the context menu and stops listening for clicks outside it. |
| `bindKeys()` | 1563 | ── Keyboard ───────────────────────────────────────────────────────── |
| `save(force)` | 1599 | ── Save, copy, export ─────────────────────────────────────────────── |
| `conflictDialog(msg)` | 1627 | Asks what to do when someone else saved the pack first: reload their version, or save this one over it. |
| `copyPack()` | 1640 | Makes the analyst's own copy of the pack and opens it in the designer. |
| `exportPdf()` | 1651 | Exports the pack to PDF behind a busy overlay, closing any open editor first and warning when some content could not be exported. |
| `toast(msg, warn)` | 1669 | Shows a message at the bottom of the screen for five seconds, styled as a warning when warn is true. |

## Loaded by

- [reporting/packs/designer.php](/code/files/reporting/packs/designer.php.md)

