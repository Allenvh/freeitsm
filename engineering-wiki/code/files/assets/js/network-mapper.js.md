# assets/js/network-mapper.js

> JavaScript - 2942 lines

Network Mapper — editor module (chunks A + B + C + D part 1).

Chunk D Part 1 adds connectors:
  - SVG layer behind nodes hosts connector paths + arrowhead defs
  - 4 edge handles per node (top/right/bottom/left), invisible until the
    node is hovered or selected. mousedown on a handle starts a connect
    drag — a dashed temp line tracks the cursor until mouseup. If mouseup
    lands on another node, a connector is created between source and
    target (free-form by default; cmdb_relationship_id stays NULL until
    part 2's relationships pull-in populates it from CMDB)
  - connectors store from_node_id/to_node_id as either the real node id
    (positive int from the server) or the tempId (negative int for nodes
    placed but not yet saved). save_diagram.php resolves both through
    its nodeIdMap, so connecting two brand-new nodes and hitting save
    works in a single round-trip
  - click a connector to select (cyan + thicker stroke); Delete removes
    it. Connector and node selections are mutually exclusive
  - double-click on a connector prompts for a label which renders mid-line
  - `renderConnectors()` is called after every node move/place/delete so
    lines track their endpoints; arrowheads are SVG markers (the same
    pattern Process Mapper uses)

Convention: every exported entry point goes on window.NM so the inline
HTML can call NM.save(), NM.toggleAutosave() etc. without scope concerns.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `init(id)` | 138 | Initialisation |
| `loadBrandingDefaults()` | 236 | Fetches the organisation-wide header and footer branding and redraws the overlay if the diagram has already rendered; failures are ignored. |
| `bindCanvasEvents()` | 257 | Wires the canvas drop target, click-on-empty-space deselection and the document keys (Escape leaves Present mode, Delete or Backspace removes the selected node or connector). |
| `ensureSvgLayer()` | 312 | SVG layer (connectors live here) |
| `loadDiagram()` | 339 | Diagram + palette loading |
| `loadClasses()` | 364 | Loads the active CMDB classes into the class lookup and renders the palette, or shows an error in the palette. |
| `renderHeader()` | 382 | Fills in the page title, diagram title, version pill (current or read-only) and the author, created and updated meta row. |
| `renderPalette()` | 404 | Renders one draggable palette tile per CMDB class with its icon and object count. |
| `onTileDragStart(e)` | 431 | Puts the dragged palette tile's class id on the drag payload for the canvas drop handler. |
| `applyReadOnlyState()` | 440 | Read-only mode (historical version) |
| `snap(v)` | 466 | Drop → CMDB object picker → place node |
| `canvasModelCoords(e)` | 474 | Translate a mouse/drag event's client coordinates into model (1×, pre-zoom) coordinates inside the canvas. elCanvas itself is NOT transformed (it provides the scroll viewport + dot-grid background); elCanvasInner is the scaled wrapper containing nodes/SVG/brand strips. We measure relative to elCanvas (scroll-aware) and divide by zoom to undo the visual scale, landing in the coordinate space that node.x/node.y are stored in. |
| `onCanvasDrop(e)` | 486 | Handles a palette tile dropped on the canvas - works out a grid-snapped position centred on the cursor and opens the object picker for that class. |
| `openObjectPicker(cls, x, y)` | 507 | Opens the object picker for a class, remembering the drop position, and loads the class's full object list. |
| `closeObjectPicker()` | 523 | Closes the object picker modal and clears the picked class. |
| `fetchPickerResults(q)` | 532 | Fetches the CMDB objects of the picker's class, filtered by the search text when there is any, and renders them. |
| `renderPickerResults()` | 552 | Renders the picker results, leaving out objects already on the canvas, with planned and parent badges and click-to-place. |
| `onPickerSearchInput(value)` | 588 | Debounces the picker search box by 200ms before refetching results. |
| `onPickerKeyDown(e)` | 597 | Keyboard handling in the object picker - arrow keys move the highlight, Enter places the highlighted object, Escape closes. |
| `commitPickerSelection(obj)` | 621 | Places the picked CMDB object as a new unsaved node at the drop position, selects it and marks the diagram dirty. |
| `nodeKey(n)` | 647 | Node rendering + selection + drag + delete |
| `findNodeByKey(key)` | 655 | Returns the node whose selection key matches, or null. |
| `renderNodes()` | 661 | Rebuilds every node element on the canvas, toggles the empty state and redraws the connectors. |
| `buildNodeEl(n)` | 683 | Builds the DOM element for one node - icon, planned pill, label and, on the current version, the four edge handles that start a connector drag. |
| `nodeRef(n)` | 735 | Returns the identifier connectors use to reference a node - its real id, or its negative temp id when unsaved. |
| `findNodeByRef(ref)` | 744 | Returns the node matching a connector reference (real id or temp id), or null. |
| `nodeIconBBox(n)` | 753 | Returns the bounding box and centre of a node's icon in model coordinates, used to anchor connectors and hit-test drops. |
| `selectNode(key)` | 775 | Selects a node by key (null clears), clears any connector selection, and opens or closes the detail panel to match. |
| `onNodeMouseDown(e)` | 797 | Selects the clicked node and, on the current version, starts a node drag. |
| `onNodeMouseMove(e)` | 820 | Moves the dragged node to the grid-snapped cursor position and redraws the connectors. |
| `onNodeMouseUp()` | 842 | Ends a node drag and marks the diagram dirty if the node actually moved. |
| `deleteSelectedNode()` | 855 | Removes the selected node and every connector touching it, then clears the selection and marks the diagram dirty. |
| `connectorKey(c)` | 882 | Connectors — render, select, delete, label, draw-new |
| `findConnectorByKey(key)` | 890 | Returns the connector whose selection key matches, or null. |
| `selectConnector(key)` | 896 | Selects a connector by key (null clears), deselecting any node, and redraws the connectors. |
| `renderConnectors()` | 907 | Redraws every connector in the SVG layer as an arrowed line with a wide click target and optional label; double-click edits the label. |
| `calcConnectorPoints(from, to)` | 991 | Returns the start and end points of a connector, on the facing sides of the two icon boxes along whichever axis has the larger gap. |
| `deleteSelectedConnector()` | 1019 | Removes the selected connector and marks the diagram dirty. |
| `promptConnectorLabel(c)` | 1031 | Shows an inline text box at the connector midpoint to edit its label - Enter or blur saves, Escape cancels. |
| `finish()` | 1061 | Removes the label input and, unless cancelled or unchanged, stores the trimmed label (empty becomes null) and marks the diagram dirty. |
| `startConnectDrag(e, fromNode, side)` | 1088 | Starts drawing a new connector from the given edge handle of a node. |
| `onConnectDragMove(e)` | 1108 | Draws the temporary line from the edge handle to the cursor while a connector is being dragged. |
| `onConnectDragUp(e)` | 1128 | Ends a connector drag and adds the connector if it was dropped on a different node and no identical connector exists. |
| `commitConnectorDrag(fromRef, toRef)` | 1152 | Adds a new unsaved free-form connector between two nodes, selects it and marks the diagram dirty. |
| `findNodeAt(x, y)` | 1169 | Returns the node whose icon box contains the given model coordinates, or null. |
| `openDetailForNode(n)` | 1182 | Node detail panel — opens beside the canvas when a node is selected |
| `loadNodeProperties(cmdbObjectId)` | 1230 | Fetches a CMDB object's properties for the detail panel, ignoring the response if the selection has changed meanwhile. |
| `renderNodeProperties(props)` | 1249 | Renders the property rows in the detail panel, skipping properties with no value (per-type rules). |
| `renderPropertyValue(p)` | 1285 | Returns the HTML for one property value by type - yes/no, naive date, formatted number, coloured dropdown pill, linked CMDB object, or text (linkified when it is a URL). |
| `pillTextColour(hex)` | 1354 | Returns dark or white text colour for a hex pill background, based on its luminance. |
| `closeDetail()` | 1367 | Closes the node detail panel and cancels any pending properties render. |
| `openRelatedModal()` | 1379 | Add-related-objects modal |
| `closeRelatedModal()` | 1404 | Closes the add-related-objects modal and clears its state. |
| `fetchRelatedRows(cmdbObjectId)` | 1412 | Fetches the CMDB objects related to the source object and renders them in the modal. |
| `renderRelatedRows()` | 1428 | Renders the related objects grouped as outgoing, incoming and property references, with objects already on the canvas disabled. |
| `updateRelatedAddBtn()` | 1494 | Enables the related-objects Add button and shows the ticked count on it. |
| `commitRelatedSelections()` | 1508 | Places the ticked related objects in a ring around the source node and draws a connector for each relationship path. |
| `cssEscape(s)` | 1620 | Escapes quotes and backslashes in a node key for use in an attribute selector. |
| `markDirty()` | 1634 | Single entry-point for "something changed" — wraps dirty/status/debounce so the call sites (place node, drag, delete, …) don't have to think about any of it. No-op on read-only versions so even programmatic edits can't dirty a historical diagram. |
| `scheduleAutosave()` | 1642 | Restarts the autosave debounce timer; when it fires it saves, or reschedules if a node drag is in progress. |
| `setStatus(state)` | 1658 | Status states: 'idle' \| 'unsaved' \| 'saving' \| 'saved' \| 'failed' \| 'off' |
| `loadAutosavePreference()` | 1678 | Loads the user's autosave preference (off when it cannot be read) and applies it. |
| `applyAutosaveState(on, persist)` | 1692 | Sets autosave on or off, updates the toggle and the save status, and optionally stores the choice as a user preference. |
| `toggleAutosave(on)` | 1717 | Handles the autosave toggle - stores the new setting and schedules a save when there are unsaved changes. |
| `openIconPicker()` | 1730 | Per-node icon picker Opens from the detail panel's Change button; lets the user override the class's default icon for this specific node. icon_override is a free-text VARCHAR on network_diagram_nodes so any key from NM_ICONS works (not constrained to the cmdb_icons table). |
| `closeIconPicker()` | 1745 | Closes the icon picker modal and clears its state. |
| `onIconSearchInput(value)` | 1752 | Filters the icon picker by the search text. |
| `renderIconPicker()` | 1761 | Renders the icon picker grid by category, filtered by the search text, with the node's current icon highlighted. |
| `commitIconPick(key)` | 1809 | Sets the picked icon as the node's override (null when it matches the class default), refreshes the node and detail panel and marks the diagram dirty. |
| `resetIconOverride()` | 1825 | Clears the selected node's icon override so it uses the class icon again. |
| `toggleVersionsDropdown(e)` | 1841 | Versions dropdown — anchored to the toolbar Versions button. Lazy-fetches the chain each open so save-as-new-version mutations show up next time. Click a version to navigate. |
| `closeVersionsDropdown()` | 1854 | Hides the versions dropdown. |
| `fetchVersionList()` | 1859 | Fetches the diagram's version chain and renders it in the versions dropdown. |
| `renderVersionList(versions)` | 1871 | Renders the version chain newest first as links, each with a viewing, current or read-only pill. |
| `togglePageDropdown(e)` | 1912 | Page-size overlay — toolbar dropdown picks paper size + orientation, a dashed outline renders inside the SVG layer at the canvas origin. Persisted per-diagram (paper_size + paper_orientation cols) so the outline survives reload. Sets up the bounds for PNG/PDF export. |
| `closePageDropdown()` | 1924 | Hides the page-size dropdown. |
| `renderPageDropdown()` | 1932 | Renders the page-size dropdown - Off plus each paper size in landscape and portrait - and wires the rows to apply the choice. |
| `applyPageSetting(size, orient)` | 1992 | Sets the diagram's paper size and orientation, refreshes the button label, page outline and branding, and marks the diagram dirty. |
| `updatePageButtonLabel()` | 2010 | Shows the chosen paper size and orientation (or Off) on the page toolbar button. |
| `pageDimensionsPx(size, orient)` | 2025 | Returns the pixel width and height of a paper size in the given orientation, or null for an unknown size. |
| `renderPageOutline()` | 2034 | Draws the shaded, dashed page outline and its size label in the SVG layer behind the connectors. |
| `resolveBrandingSlot(key)` | 2103 | Resolve the effective text for a slot — per-diagram override wins, then org-wide default, then empty string. The null-check is deliberate: empty string is an explicit override ("blank this slot"), null is "inherit". `diagram[key] != null` (loose !=) matches both null and undefined which is what we want when the field hasn't been set on a diagram created before this column existed. |
| `brandingTokenContext()` | 2110 | Returns the values for the branding tokens - title, author, version, modified date and logo URL. |
| `renderSlotHtml(text, ctx)` | 2128 | Token resolver — walks the slot text once with a single regex, HTML-escaping non-token segments and the substituted values; logo renders as an <img>. Tokens that don't have a value in ctx come back as empty strings so the slot collapses gracefully. |
| `renderBrandHeaderFooter()` | 2157 | Rebuilds the branded header and footer strips on the page outline; shown only when a paper size is set and a strip has any text. |
| `openBrandingModal()` | 2220 | Opens the branding modal with the diagram's overrides as values and the organisation defaults as placeholders. |
| `closeBrandingModal()` | 2244 | Closes the branding modal. |
| `commitBrandingOverrides()` | 2253 | Save commits the modal inputs as per-diagram overrides. An empty input means "inherit the org-wide default" (stored as null) — the modal's placeholder already previews that default, so a blank field naturally reads as "use that". Non-empty input is an explicit override. |
| `resetBrandingOverrides()` | 2276 | Reset clears all six overrides (sets them to null on the diagram object) so the slots inherit the org-wide defaults again. Marks the diagram dirty so the change persists on next save. |
| `save(isAutoSave)` | 2293 | Save |
| `openNewVersionModal()` | 2395 | Save as new version |
| `closeNewVersionModal()` | 2418 | Closes the new version modal. |
| `suggestNextVersionLabel(current)` | 2423 | Suggests the next version label by bumping a trailing number, or appending " (new)" when there is none. |
| `createNewVersion()` | 2432 | Creates a new version of the diagram from the modal fields and opens it. |
| `applyZoom()` | 2470 | Zoom + Present mode Zoom is purely visual: transform: scale(zoom) on elCanvasInner. Node coordinates stay in 1× model space; `canvasModelCoords()` divides client deltas by zoom so drag/drop math keeps working at any level. Present mode adds an .is-presenting class to .nm-editor which CSS uses to hide the toolbar/palette/detail-panel and reveal the floating Exit pill. |
| `setZoom(z)` | 2489 | Sets the zoom to the nearest preset level and applies it. |
| `zoomIn()` | 2504 | Zooms in one preset level. |
| `zoomOut()` | 2506 | Zooms out one preset level. |
| `zoomReset()` | 2508 | Resets the zoom to 100%. |
| `zoomNext(dir)` | 2511 | Returns the preset zoom level one step in the given direction from the current zoom. |
| `zoomFit(opts)` | 2532 | Zooms to fit the page outline, or the placed nodes when no paper size is set, into the canvas and scrolls to the top left. |
| `enterPresent()` | 2598 | Enters Present mode - hides the editor chrome and fits the diagram to the screen, remembering the zoom and scroll to restore later. |
| `exitPresent()` | 2624 | Leaves Present mode and restores the zoom and scroll position from before. |
| `isPresenting()` | 2645 | Returns true while Present mode is on. |
| `centreOnPage()` | 2658 | Auto-centre the diagram on the selected paper size Translates all node coordinates so the diagram's bounding box sits centred inside the page rectangle. Persistent (mutates n.x/n.y) so the centring survives save/reload, not a transient visual transform. Connectors auto-follow since `renderConnectors()` reads live node positions. Bails with a toast if the diagram is bigger than the selected page in either dimension. |
| `exportFilename(ext)` | 2719 | PNG / PDF export Snapshot the canvas via html2canvas, optionally wrap into a paper- sized PDF via jsPDF. Driven entirely from the live DOM: temporarily reset zoom to 1, hide selection/edge-handle chrome via .is-exporting, render the clipped rect, restore everything. The page outline drives the clip rect when a paper size is set (WYSIWYG export); otherwise we crop to the bounding box of placed nodes + 40px padding. |
| `computeExportRect()` | 2733 | Returns the area to export - the page outline when a paper size is set, otherwise the nodes' bounding box with padding. |
| `captureCanvas()` | 2761 | Captures the export area with html2canvas at 100% zoom with selection and handles hidden, then restores the view. |
| `exportPng()` | 2831 | Exports the diagram as a PNG download. |
| `exportPdf()` | 2845 | Exports the diagram as a PDF sized to its paper choice (A4 portrait when none is set). |
| `formatDate(s)` | 2876 | Helpers |
| `escapeHtml(s)` | 2885 | Escapes a value for safe use in HTML. |
| `escapeAttr(s)` | 2889 | Escapes a value for use in an HTML attribute. |

## API endpoints it calls

- [api/system/get_branding.php](/code/files/api/system/get_branding.php.md)
- [api/network-mapper/get_diagram.php](/code/files/api/network-mapper/get_diagram.php.md)
- [api/network-mapper/get_related_objects.php](/code/files/api/network-mapper/get_related_objects.php.md)
- [api/network-mapper/list_versions.php](/code/files/api/network-mapper/list_versions.php.md)
- [api/network-mapper/save_diagram.php](/code/files/api/network-mapper/save_diagram.php.md)
- [api/network-mapper/create_version.php](/code/files/api/network-mapper/create_version.php.md)
- [api/cmdb/get_classes.php](/code/files/api/cmdb/get_classes.php.md)
- [api/cmdb/get_objects.php](/code/files/api/cmdb/get_objects.php.md)
- [api/cmdb/get_object.php](/code/files/api/cmdb/get_object.php.md)
- [api/system/get_user_preference.php](/code/files/api/system/get_user_preference.php.md)
- [api/system/set_user_preference.php](/code/files/api/system/set_user_preference.php.md)

## Loaded by

- [network-mapper/diagram.php](/code/files/network-mapper/diagram.php.md)

