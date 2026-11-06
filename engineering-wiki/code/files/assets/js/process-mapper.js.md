# assets/js/process-mapper.js

> JavaScript - 3517 lines

Process Mapper – flowchart editor

Features:
  - Dot-grid canvas with snap-to-grid (20px)
  - User-definable step types (from process_step_types) — each carries a
    name, shape and default colour; managed in Process Mapper → Settings
  - Drag to move steps; snap on drop
  - Ctrl+click or rubber-band to multi-select; arrow keys to nudge
  - Edge handles for drawing connectors; connectors have optional text
  - Slide-in detail panel on step click
  - Full CRUD via JSON API

## Functions

| Function | Line | What it does |
|---|--:|---|
| `PM(()` | 14 | Process Mapper – flowchart editor |
| `snap(v)` | 17 | Rounds a coordinate to the nearest 20px grid line. |
| `getType(slug)` | 26 | The configured step type for a slug, or null when no such type exists any more. |
| `shapeForSlug(slug)` | 30 | The shape for a step type slug, falling back to rounded when the type has gone. |
| `init()` | 101 | Initialisation |
| `loadSidebarModePreference()` | 127 | Loads the analyst's left-panel preference ('always' or 'hover') and applies it. |
| `applySidebarMode(mode)` | 138 | Switches the layout between a pinned sidebar and the hover-to-expand one. |
| `syncUrl(id)` | 156 | Write the URL to match the currently-open process. Uses pushState when the URL actually changes (so each user-driven open is a separate history entry); same-URL calls are a no-op (so popstate handlers don't accidentally double-push). |
| `openInitialFromUrl()` | 167 | Opens the process named by ?id= in the address bar, if it is in the list, without pushing another history entry. |
| `onPopState()` | 179 | Back/forward fires popstate with a URL the user navigated to. Reload whichever process that URL points at — and clear the canvas if the URL no longer carries an ?id. |
| `markDirty()` | 199 | Single entry-point for "something changed" — replaces the dozen scattered `dirty = true;` writes so we can hook status updates and the debounce timer in one place. |
| `scheduleAutosave()` | 207 | Restarts the 2-second autosave timer; when it fires mid-drag it waits for the next tick rather than reloading the canvas under the drag. |
| `setStatus(state)` | 224 | Status states: 'idle' \| 'unsaved' \| 'saving' \| 'saved' \| 'failed' \| 'off' |
| `loadAutosavePreference()` | 247 | Loads the analyst's autosave on/off preference and applies it without saving it back. |
| `applyAutosaveState(on, persist)` | 259 | Sets autosave on or off, ticks the toggle, updates the status line and, when persist is true, saves the preference. |
| `toggleAutosave(on)` | 283 | The autosave toggle's handler - applies and saves the choice, and saves pending edits straight away when turned on. |
| `addArrowheadDef()` | 291 | Adds the SVG arrowhead markers (normal and selected) used by connector lines. |
| `loadProcesses()` | 305 | Process list (sidebar) |
| `renderProcessList(filter = '')` | 317 | Draws the sidebar's process list, optionally filtered by title, with each row's date and delete button. |
| `filterProcesses(val)` | 336 | Filters the sidebar list by the typed text. |
| `createProcess()` | 350 | New process. Opens the page's own dialog rather than window.prompt(). |
| `closeNewProcess()` | 366 | Closes the new-process dialog. |
| `submitNewProcess()` | 372 | Creates an empty process with the typed title, refreshes the list and opens it; the button is disabled meanwhile so a double submit cannot create two. |
| `deleteProcess(id)` | 406 | Asks, then deletes a process; clears the canvas and the ?id= when it was the open one. |
| `openProcess(id, preserveDetail = false, fromHistory = false)` | 436 | Open / load a process `preserveDetail` — pass true when calling from `save()` so the detail panel stays open across the reload that picks up fresh real IDs. Caller is responsible for re-resolving the previous selection against the new data. `fromHistory` — pass true when called from popstate / initial-from-URL so we don't push the same URL onto the history stack again. |
| `renderAll()` | 520 | Render |
| `createStepEl(step)` | 547 | Builds a step's element - shape, position, colour, label, the four connector edge handles, the link badge when it has a URL, and its mouse handlers. |
| `renderConnectors()` | 598 | Redraws every connector as a straight SVG line with a wider invisible hit area and an optional label that can be double-clicked to edit. |
| `calcConnectorPoints(from, to)` | 672 | The start and end points of a connector - the facing edges of the two steps, chosen by whether they sit more apart horizontally or vertically. |
| `onStepMouseDown(e, step)` | 711 | Step interaction |
| `onStepDblClick(e, step)` | 772 | Renames a step in place - lays a text box over it; Enter or leaving the box keeps the new label, Escape puts the old one back. |
| `finish()` | 786 | Writes the typed label back to the step and the detail panel, removes the text box and marks the diagram changed. |
| `bindCanvasEvents()` | 813 | Canvas events (drag, rubber-band, keys) |
| `onCanvasMouseDown(e)` | 822 | Starts a rubber-band selection on bare canvas or a lane body; cancels click-to-connect instead when it is armed. |
| `onDocMouseMove(e)` | 862 | Mouse-move for every drag on the canvas - hands annotation, lane and group drags to their own handlers, and moves steps (snapped), draws the connector being dragged or sizes the rubber band. |
| `onDocMouseUp(e)` | 941 | Finishes whatever drag is in progress - re-assigns lanes and groups after a step move, opens the detail panel for a plain click, adds the dragged connector or selects the steps inside the rubber band. |
| `onCanvasClick(e)` | 1020 | A click on bare canvas clears every selection and closes the detail panel. |
| `onCanvasKeyDown(e)` | 1035 | Canvas keys - Delete removes the selection, arrows nudge selected steps one grid square, Ctrl+S saves and Ctrl+A selects every step. |
| `startConnectDrag(e, step, side)` | 1083 | Connector dragging from edge handles |
| `findStepAt(x, y)` | 1102 | The step whose box contains the given canvas point, if any. |
| `addStep(type)` | 1112 | Add / remove steps and connectors |
| `stepDims(slug)` | 1164 | Box dimensions + default colour for each step type. Data-driven from window.STEP_TYPES_BY_SLUG (configured in Process Mapper → Settings): the type's shape decides the default size via window.SHAPE_SIZES, and the type's colour seeds the new step's colour (the per-step colour picker can still override it). Single source of truth shared by `addStep()` and `createConnectedStep()`. |
| `rectsOverlap(ax, ay, aw, ah, bx, by, bw, bh)` | 1176 | True when two rectangles overlap. |
| `onStepContextMenu(e, step)` | 1181 | Right-click on a step - makes it the only selection, remembers the click position for Paste and opens the context menu. |
| `showContextMenu(x, y)` | 1203 | Opens the step context menu at the cursor, showing Apply formatting and Paste only when there is something to apply, enabling the connector items only when the step has connectors, and keeping the menu on screen. |
| `rebuildReverseSubmenu(step, conns)` | 1243 | Rebuild the Reverse-connection submenu so each row lists one of the current step's connectors, with an arrow + the other end's label. Picking one flips just that connector's direction. |
| `escapeForAttr(s)` | 1273 | Escapes text for safe use in HTML. |
| `hideContextMenu()` | 1280 | Closes the step context menu and forgets its target step. |
| `bindContextMenu()` | 1287 | Wires the step context menu - Create new, Change to and the flat actions - plus closing it on an outside click, Escape (which also cancels click-to-connect and an uncommitted cut), scroll or window blur. |
| `changeStepType(step, newSlug)` | 1372 | Swap a step's type to a new slug. Resizes to the new type's default dimensions so a circle doesn't stay rectangle-shaped, and repaints with the new type's default colour (gradient cleared) so a step identifies visually with its new type. Use the colour picker in the detail panel afterwards if you want a custom colour back. |
| `copyFormat(step)` | 1406 | Copy/Apply formatting: stash colour + gradient from one step, paste them into another via the right-click menu. Position, label, type and size are deliberately not copied — only the "paint job". |
| `applyFormat(step)` | 1413 | Paints the copied colour and gradient onto a step and updates the detail panel if it shows that step. |
| `startClickConnect(fromStep)` | 1438 | Click-to-connect: arm a one-shot waiting state. The next mousedown on a step pairs them up; mousedown on empty canvas or Escape cancels. While armed we add a class to the canvas for cursor + visual cue, and surface a persistent toast prompt so the user knows what's expected. |
| `cancelClickConnect()` | 1448 | Leaves click-to-connect mode and says so. |
| `completeClickConnect(targetStep)` | 1457 | Called from onStepMouseDown when click-to-connect is armed: completes (or rejects) the pairing and exits the mode. |
| `ctxEditLabel(step)` | 1478 | Edit label: alias for double-click inline rename. Reuses the same flow so there's only one rename code path to maintain. |
| `promptForUrl(step)` | 1485 | Open a `prompt()` to set/edit/clear the step's URL. Empty input clears it. Light validation: must start with http:// or https:// (or be empty). |
| `reverseConnector(c)` | 1521 | Reverse the direction of a single connector. |
| `deleteAllConnections(step)` | 1531 | Remove every connector incident to `step`. |
| `cloneStepData(step)` | 1550 | Capture the cloneable shape of a step — everything that ought to be duplicated by Copy / Cut / Duplicate. Position is NOT copied here; the caller picks where the new step goes. |
| `placeClonedStep(data, atX, atY)` | 1567 | Drop a new step at (atX, atY) from a clipboard payload, nudged off-grid until it doesn't overlap any existing step. Returns the new step. |
| `duplicateStep(step)` | 1610 | Duplicate: place a copy 40px down-right of the original. |
| `clearStepClipboardVisual()` | 1619 | Clipboard helpers — clear the previous cut's visual cue before stashing a fresh entry, otherwise a cut-then-copy-elsewhere would leave the original cut step looking faded forever. |
| `cutStep(step)` | 1628 | Puts a step on the step clipboard as a cut and fades it; the original is removed only when pasted. |
| `copyStepForPaste(step)` | 1636 | Puts a copy of a step on the step clipboard, ready to paste any number of times. |
| `pasteStep(screenPos)` | 1645 | Drop a copy of the clipboard at the screen-coords stashed when the user right-clicked. If the clipboard was a Cut, also delete the source and clear the clipboard so the cut+paste pair behaves like a move. |
| `ctxDeleteStep(step)` | 1666 | Delete with confirm — the right-click "Delete…" item. |
| `getAnnotation(id)` | 1679 | The sticky note with this saved or temporary id. |
| `annotationRef(a)` | 1684 | A sticky note's saved id, or its temporary id when not yet saved. |
| `addAnnotation(nearStep)` | 1689 | Drop a new sticky note near `nearStep` (or the canvas centre if no step). |
| `createAnnotationEl(a)` | 1723 | Builds a sticky note's element - body text, resize corner, drag and double-click-to-edit handlers. |
| `applyAnnotationStyle(el, a)` | 1750 | Applies a sticky note's position, size, colour and text to its element. |
| `onAnnotationMouseDown(e, a)` | 1761 | Starts moving or resizing a sticky note (resize from the corner handle) and selects it; cancels click-to-connect instead when it is armed. |
| `onAnnotationDocMouseMove(e)` | 1774 | Moves or resizes the dragged sticky note on the grid (at least 80x60) and keeps the detail panel's numbers in step. |
| `onAnnotationDocMouseUp()` | 1798 | Ends a sticky-note drag, marking the diagram changed if it actually moved. |
| `selectAnnotation(a)` | 1805 | Makes a sticky note the only selection and opens its detail panel. |
| `showDetailForAnnotation(a)` | 1817 | Opens the detail panel's sticky-note section filled from the note. |
| `updateAnnotationFromDetail()` | 1837 | Copies the detail panel's text, colour, position and size back onto the selected sticky note. |
| `createConnectedStep(source, type)` | 1855 | Create a new step of `type` placed neatly to the right of `source`, pre-connected source -> new, with the detail panel open and focused on the label box so the user can name it straight away. |
| `scrollStepIntoView(step)` | 1915 | Scroll the canvas so `step` sits comfortably within the viewport. |
| `addGroup()` | 1938 | Adds a new 240x160 group in the middle of the visible canvas and selects it. |
| `createGroupEl(group)` | 1960 | Builds a group's element - label, resize corner, drag handler, and double-click to rename in the detail panel. |
| `applyGroupStyle(el, group)` | 1989 | Applies a group's position, size, fill and label to its element, with a border a shade darker than the fill. |
| `fillStyle(color, color2)` | 2003 | Returns the right CSS background string: gradient when color2 is set, otherwise solid. |
| `shade(hex, amt)` | 2011 | Light/darken a #rrggbb colour by `amt` units per channel (negative = darker). |
| `getGroup(id)` | 2023 | The group with this saved or temporary id. |
| `groupRef(group)` | 2028 | A group's saved id, or its temporary id when not yet saved. |
| `groupAtPoint(x, y)` | 2035 | Find the smallest group whose rectangle contains the point (x, y). Smallest wins when groups overlap, on the theory that nested-looking groups should claim contents in favour of the surrounding one. |
| `autoAssignAllStepGroups()` | 2048 | Tag every step with the group_id derived from where its centre currently sits. Run at the start of a group drag and after step drags so the step→group membership stays in sync with visual position. |
| `reassignStepGroups(stepIds)` | 2057 | Sets group_id on the given steps from the group their centre now sits in. |
| `selectGroup(g)` | 2068 | Makes a group the only selection and opens its detail panel. |
| `onGroupMouseDown(e, group)` | 2079 | Starts moving or resizing a group (resize from the corner handle), first tagging every step inside it so a move carries them along. |
| `onGroupDocMouseMove(e)` | 2100 | Moves the dragged group with its steps, or resizes it (at least 80x60), keeping the detail panel's numbers in step. |
| `onGroupDocMouseUp()` | 2143 | Ends a group drag - re-assigns lanes for steps carried by a move, or every step's group after a resize. |
| `showDetailForGroup(g)` | 2165 | Opens the detail panel's group section filled from the group. |
| `updateGroupFromDetail()` | 2187 | Copies the detail panel's label, colours, position and size back onto the selected group. |
| `getLane(id)` | 2213 | The lane with this saved or temporary id. |
| `laneRef(lane)` | 2218 | A lane's saved id, or its temporary id when not yet saved. |
| `lanesOrdered()` | 2223 | The lanes sorted by display order, top to bottom. |
| `recomputeLaneBandTops()` | 2229 | Cache each lane's bandTop on the lane object after sorting. Run before any hit-test or render pass that needs lane Y coordinates. |
| `laneAtY(y)` | 2238 | The lane whose band contains this canvas y position, if any. |
| `renderLanes()` | 2247 | Redraws every lane band behind everything else on the canvas, in order. |
| `createLaneEl(lane)` | 2259 | Builds a lane band's element with its header (drag to reorder, click to select) and bottom divider (drag to resize). |
| `applyLaneStyle(el, lane)` | 2283 | Applies a lane's band position, height, fill and header label to its element. |
| `updateLaneSelectionVisuals()` | 2294 | Highlights the selected lane band. |
| `addLane()` | 2301 | Adds a new 180px lane at the bottom of the lane order and selects it. |
| `selectLane(lane)` | 2326 | Makes a lane the only selection and opens its detail panel. |
| `showDetailForLane(lane)` | 2338 | Opens the detail panel's lane section filled from the lane. |
| `updateLaneFromDetail()` | 2360 | Copies the detail panel's label, colours, height and order back onto the selected lane, resizing or reordering it (contents move with it) and redrawing. |
| `renderAllSteps()` | 2388 | Re-render only steps (cheap re-render that preserves selection visuals). |
| `resizeLaneTo(lane, newHeight)` | 2403 | Resize lane to `newHeight`. Everything visually below the lane's bottom edge (steps and groups) shifts by the delta so the bottom half of the canvas physically moves down/up with the divider. Visual position is used as the shift criterion (matching how groups already worked) so untagged or stale-tagged steps come along too. Existing lane_id values are left untouched — the next lane drag's auto-tag pass will reconcile. |
| `reorderLaneTo(lane, targetOrder)` | 2427 | Reorder lane to display_order = targetOrder. Steps and groups in every affected lane are shifted so they stay anchored to their lane band. Groups have no stored lane_id — their lane membership is derived on the fly from their vertical centre at snapshot time. |
| `onLaneHeaderMouseDown(e, lane)` | 2478 | Drag-to-reorder via the left-edge header. While dragging the header we visually translate the lane element with the cursor; on mouseup we work out which slot the cursor landed in and call `reorderLaneTo()`. |
| `onLaneDividerMouseDown(e, lane)` | 2499 | Starts resizing a lane from its bottom divider, first tagging every step with the lane it sits in. |
| `onLaneDocMouseMove(e)` | 2516 | Resizes the dragged lane in grid steps, or reorders it by counting the lanes whose midline is above the cursor, redrawing as it goes. |
| `onLaneDocMouseUp()` | 2569 | Ends a lane drag, marking the diagram changed if it actually moved. |
| `reassignStepLanes(movedIds)` | 2579 | Called after a step drag completes — auto-assign lane_id based on where each moved step ended up. |
| `autoAssignAllStepLanes()` | 2597 | Scan every step on the canvas and assign lane_id based on where it visually sits. Run at the start of any lane drag so steps that *look* like they belong to a lane (but never got tagged — e.g. steps that pre-existed the lanes, or were dragged in before lanes existed) come along for the ride during reorder and resize. Cheap and idempotent. |
| `deleteSelectedLane()` | 2608 | Removes the selected lane - its steps keep their place but lose the lane, and the contents of the lanes below move up to close the gap. |
| `addConnector(fromId, toId)` | 2648 | Adds a connector between two steps unless one already joins them in either direction. |
| `deleteSelected()` | 2663 | Deletes whatever is selected - a sticky note, lane, group (its steps stay), connector, or the selected steps with their connectors. |
| `updateSelectionVisuals()` | 2717 | Selection |
| `selectConnector(c)` | 2735 | Makes a connector the only selection and closes the detail panel. |
| `showDetailForStep(step)` | 2746 | Detail panel |
| `closeDetail()` | 2785 | Closes the detail panel and resets it to the step section. |
| `updateStepFromDetail()` | 2800 | Copies the detail panel's label, type, colours, description, link and position back onto the selected step and redraws it. |
| `updateDetailPosition()` | 2831 | Keeps the detail panel's X and Y boxes in step with the selected step while it moves. |
| `updateConnectorLabel(cid, value)` | 2841 | Sets a connector's label from the detail panel. |
| `removeConnector(cid)` | 2851 | Deletes a connector from the detail panel's list and refreshes the list. |
| `editConnectorLabel(c, mx, my)` | 2864 | Edits a connector's label in place with a small text box at its midpoint; Enter or leaving the box keeps it, Escape cancels. |
| `finish()` | 2875 | Writes the typed label back to the connector, removes the text box and redraws. |
| `bindToolbar()` | 2896 | Toolbar |
| `save(isAutosave = false)` | 2911 | Save `isAutosave` controls toast suppression and re-scheduling on retry. |
| `getStep(id)` | 3100 | Helpers |
| `clearCanvas()` | 3105 | Removes every step, connector, group, lane and sticky note from the canvas and the in-memory diagram, clears all selections and a pending cut, and resets the changed flag. |
| `exportToMermaid()` | 3136 | Export — Mermaid Generates Mermaid flowchart markup from the current process state. Lanes become `subgraph` blocks (LR direction so lanes stack vertically like horizontal swimlanes). Step types map to classic Mermaid shapes for maximum renderer compatibility. Groups are *not* exported — Mermaid only has subgraphs and we're already using those for lanes; nesting would look messy. Hand-placed positions, colours, and gradients don't survive — Mermaid auto-layouts. |
| `escLabel(text)` | 3138 | Escape a label for use inside a Mermaid quoted string. |
| `nodeId(step)` | 3146 | A step's Mermaid node id - s plus its saved id, or st plus its temporary id. |
| `nodeDef(step)` | 3151 | Map each Process Mapper shape to its closest Mermaid v10 flowchart syntax. Mermaid doesn't have a 1:1 for every shape — cloud and document collapse onto nearby classic shapes (circle, parallelogram). |
| `openExportModal()` | 3221 | Open the export modal in its default state: the three-tile format chooser is visible; the Mermaid markup is collapsed until the user picks "Mermaid". |
| `closeExportModal()` | 3229 | Closes the export dialog and resets its Copy button. |
| `showMermaid()` | 3240 | Reveal the Mermaid markup view inside the modal — generates and pre-selects the text so the user can Ctrl-C straight away. |
| `copyExport()` | 3253 | Copies the Mermaid markup to the clipboard, showing "Copied" for two seconds; if the copy is refused it selects the text for Ctrl+C instead. |
| `exportFilename(ext)` | 3280 | Slugify the current process title for the download filename. |
| `computeExportRect()` | 3293 | Bounding box of everything worth capturing — steps, groups, and the visible footprint of lanes if any are defined. Padded by 40px so the export doesn't feel cramped at the edges. |
| `captureCanvas()` | 3337 | Snapshot the canvas via html2canvas. Stashes scroll state + canvas dimensions and restores them afterwards, even if the capture throws. |
| `exportToPng()` | 3410 | Captures the diagram at 2x and downloads it as a PNG named after the process. |
| `exportToPdf()` | 3425 | Captures the diagram and downloads it as an A4 PDF, landscape or portrait to match its shape, scaled to fit inside a 24pt margin. |
| `esc(str)` | 3462 | Escapes text for safe use in HTML. |
| `toggleProcessDocs()` | 3481 | Documents attached to the PROCESS (discussion #76). |
| `closeProcessDocs()` | 3496 | Closes the process documents panel. |

## API endpoints it calls

- [api/system/get_user_preference.php](/code/files/api/system/get_user_preference.php.md)
- [api/system/set_user_preference.php](/code/files/api/system/set_user_preference.php.md)

## Loaded by

- [process-mapper/index.php](/code/files/process-mapper/index.php.md)

