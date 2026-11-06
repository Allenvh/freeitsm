# assets/js/change-management.js

> JavaScript - 2568 lines

Change Management Module - Client-side logic

## Functions

| Function | Line | What it does |
|---|--:|---|
| `loadFormLayout()` | 104 | Loads the change form layout (sections, fields and where each is placed) set in Change Management settings. |
| `isFieldVisible(fieldKey)` | 128 | Whether a change field is shown, by the form layout. A field the layout does not know about is shown. |
| `refreshFormLayout()` | 149 | Rebuilds the editor form to match the current formLayout: - reorders .cm-form-section blocks by section.display_order - renames each section heading from section.name - moves .cm-field-wrap blocks between sections based on field.section_id - shows/hides each wrap based on field.is_visible - parks the rich-text widget under whichever section contains the first visible rich-text field (v1 limitation: the six rich-text tabs are NOT split across sections) - hides sections that end up with no visible content |
| `loadStatuses()` | 330 | Pull active statuses from the change_statuses table and use them to drive both the sidebar filter list and the editor's Status dropdown. So when statuses get added / renamed / deactivated under Change Management → Settings → Statuses, both UIs reflect the change on next page load. |
| `sortLookup(rows)` | 355 | Context-menu lookups. Each endpoint returns {success, <key>:[{id,name,colour, is_active,display_order,...}]}. We keep active rows only, sorted the same way as the sidebar (display_order then name) so the submenus match Settings. |
| `loadChangePriorities()` | 363 | Loads the active change priorities, in order. |
| `loadChangeTypes()` | 374 | Loads the active change types, in order. |
| `loadChangeImpacts()` | 385 | Loads the active change impact levels, in order. |
| `loadMoveCompanies()` | 398 | Companies this analyst can move a change into (multi-company installs only). Same source as the tickets "Move to company" submenu; the "Move to company" row stays hidden unless there's more than one accessible company. |
| `renderStatusFilters()` | 416 | Draws one filter row per change status (with its colour and a count) under the always-present All row. |
| `populateStatusDropdown()` | 437 | Fills the editor's status dropdown, keeping the current choice when it still exists. |
| `loadAnalysts()` | 450 | Loads the analysts and fills every analyst dropdown. |
| `populateAnalystDropdowns()` | 464 | Fills the requester, assigned-to, approver and CAB member dropdowns with the analysts. |
| `loadChanges()` | 481 | Loads the change list for the current status filter and search, and updates the counts. |
| `updateCounts(counts)` | 512 | Writes the total and per-status counts into the filter rows, and keeps them so they can be reapplied when the status list arrives later. |
| `renderChangeList()` | 532 | Draws the change cards - reference, title, assignee, work start and risk, type, priority and status badges. |
| `viewChange(id)` | 578 | Loads one change, records it in the recent trail and shows its detail view. |
| `renderChangeDetail()` | 604 | Draws the detail view of the current change - header, badges and the fields the form layout shows, CAB review, rich-text sections, risk matrix, review results, attachments, linked incidents and activity. |
| `cmPreviewBadge(type, id)` | 787 | The ⓘ preview badge (#91). Guarded, so a page that somehow loaded without record-preview.js loses the preview rather than the table it belongs to. |
| `renderLinkedIncidents(c)` | 795 | The linked incidents table for a change - each with a link, a preview badge and Unlink - and the button to link another. |
| `openLinkIncidentModal()` | 824 | Opens the dialog for linking an incident to the current change. |
| `closeLinkIncidentModal()` | 832 | Closes the link-an-incident dialog. |
| `linkIncidentSearchDebounced()` | 836 | Re-runs the incident search 250 ms after the last keystroke. |
| `loadLinkableIncidents()` | 841 | Lists the tickets that can be linked to the current change, matching the search box. |
| `linkChangeIncident(ticketId)` | 861 | Links a ticket to the current change and reloads it. |
| `unlinkChangeIncident(ticketId)` | 873 | Asks for confirmation, then unlinks a ticket from the current change and reloads it. |
| `renderDetailSection(title, content)` | 892 | One titled rich-text section of the detail view, or a "not provided" placeholder when it is empty. The content is the editor's HTML, inserted as it is. |
| `renderRiskMatrix(likelihood, impact, score, level)` | 912 | The 5 by 5 likelihood-by-impact risk matrix with the change's cell highlighted. |
| `renderPirDetail(c)` | 979 | The post-implementation review block - whether it succeeded, actual start and end, lessons learned and follow-up. |
| `loadActivityTimeline(changeId)` | 1009 | Loads a change's audit trail and comments together and shows them as one timeline, newest first. |
| `renderActivityTimeline(timeline, container)` | 1056 | Draws the activity timeline - comments, status changes, CAB votes, incident links and field edits. |
| `postComment()` | 1124 | Adds the typed comment to the current change and reloads the timeline. |
| `filterByStatus(status)` | 1157 | Filters the change list to one status (or all) and highlights its filter row. |
| `openSearchModal()` | 1171 | Opens the search panel next to the search button and focuses the change number box. |
| `closeSearchModal()` | 1193 | Closes the search panel. |
| `initSearchModalDrag()` | 1208 | Lets the search panel be dragged by its header. |
| `onmousedown(e)` | 1213 | Starts dragging the panel from where the header was pressed (not on a button). |
| `onMouseMove(e)` | 1221 | Moves the panel with the mouse. |
| `onMouseUp()` | 1227 | Ends the drag. |
| `performSearch()` | 1237 | Searches changes by number (CHG-0001 or plain digits) or title and shows the results. |
| `renderSearchResults(results)` | 1283 | Draws the search results with their count. |
| `selectSearchResult(changeId)` | 1314 | Closes the search panel and opens the chosen change. |
| `clearSearch()` | 1320 | Empties the search boxes and results. |
| `openCreateChange()` | 1329 | Opens a blank change editor with the default status and empty risk, review and CAB fields. |
| `editCurrentChange()` | 1387 | Opens the editor filled with the current change - fields, attachments, risk, review, CAB members and the rich-text sections. |
| `cancelEdit()` | 1453 | Leaves the editor without saving, back to the change (or the list), and tidies the address if it was the new-change route. |
| `restoreUrlFromNewRoute()` | 1468 | URL-tidying helper for the /change-management/new/ pretty-URL route. Called from `cancelEdit()` and from the save flow once the editor closes. |
| `saveChange()` | 1481 | Saves the change from the editor, then its CAB members when CAB approval is on, and opens the saved change. |
| `deleteCurrentChange()` | 1567 | Shows the confirmation dialog for deleting the current change. |
| `closeDeleteModal()` | 1586 | Closes the delete confirmation dialog. |
| `confirmDelete(id)` | 1591 | Deletes the change and goes back to the list. |
| `setupFileUpload()` | 1617 | Wires the attachment area - click to choose files, or drag and drop them. |
| `uploadFiles(files)` | 1652 | Uploads files to the change being edited one at a time, refreshing its attachment list after each. A new change has to be saved first. |
| `renderEditorAttachments(attachments)` | 1690 | Lists the change's attachments in the editor with download and delete buttons. |
| `downloadAttachment(id)` | 1713 | Opens an attachment in a new tab. |
| `deleteAttachment(id)` | 1718 | Asks for confirmation, deletes an attachment and refreshes the list. |
| `initEditors(callback)` | 1755 | Starts a TinyMCE editor for each rich-text field, matching the light or dark theme, and calls callback once they are all ready. |
| `setup(editor)` | 1784 | Counts each editor as it finishes starting, and runs the callback when the last one is ready. |
| `destroyEditors()` | 1798 | Removes every TinyMCE editor. |
| `getEditorContent(id)` | 1807 | The HTML in one rich-text editor - empty when it is not running. |
| `setEditorContent(id, content)` | 1813 | Puts HTML into one rich-text editor. |
| `switchTab(tab)` | 1823 | Shows one rich-text tab and its panel. |
| `updateRiskScore()` | 1836 | Works out likelihood times impact and shows the score with its level (Low to Critical). |
| `updatePirVisibility()` | 1858 | Shows the post-implementation review fields only when the status is Completed or Failed. |
| `toggleCabConfig()` | 1869 | Shows or hides the CAB settings with the CAB required tick. |
| `addCabMember()` | 1875 | Adds the analyst chosen in the dropdown to the CAB members, as required. |
| `removeCabMember(analystId)` | 1893 | Removes an analyst from the CAB members. |
| `toggleCabMemberRequired(analystId)` | 1899 | Switches a CAB member between required and optional. |
| `renderCabMemberChips()` | 1908 | Draws the CAB members as chips with their required switch and a remove button. |
| `renderCabReviewPanel(c)` | 1935 | The CAB review panel on the detail view - each member's vote and, for a member who has not voted on a change pending approval, the voting form. Empty when CAB is not required. |
| `submitCabVote(vote)` | 1999 | Records the signed-in analyst's CAB vote with an optional comment and reloads the change. |
| `showView(view)` | 2034 | Shows the list, detail or editor view and hides the others. |
| `backToList()` | 2050 | Goes back to the change list and reloads it. |
| `escapeHtml(text)` | 2059 | Escapes text for safe use inside HTML. |
| `formatDate(dateStr)` | 2068 | NAIVE wall-clock scheduling date (only caller is work_start on the list card) — shown exactly as typed, no zone conversion (parseNaiveDate from tz.js). |
| `formatDateTime(dateStr)` | 2076 | Server-stamped UTC timestamp (created / modified / approved / CAB vote / history "when") → analyst display zone (parseUTCDate / tzOpts from tz.js). |
| `formatNaiveDateTime(dateStr)` | 2085 | NAIVE wall-clock scheduling datetime (work / outage / PIR windows) — shown exactly as typed for every analyst, no zone conversion. Same output shape as formatDateTime so the two are drop-in interchangeable at each call site. |
| `toDatetimeLocal(dateStr)` | 2095 | A stored date-time in the YYYY-MM-DDTHH:MM form a datetime-local input expects - blank when it cannot be read. |
| `pad(n)` | 2100 | Format as YYYY-MM-DDTHH:MM for datetime-local input |
| `formatFileSize(bytes)` | 2106 | A byte count as B, KB or MB. |
| `toggleShareDropdown()` | 2117 | Opens or closes the Share menu. |
| `closeShareDropdown()` | 2123 | Closes the Share menu. |
| `getChangeRef()` | 2137 | The current change's reference, such as CHG-0042. |
| `getChangeUrl()` | 2143 | A link straight to the current change. |
| `shareChangeLink()` | 2148 | Copies the link to the current change to the clipboard. |
| `shareChangePdf()` | 2162 | Downloads the current change as a PDF. |
| `shareChangeBoth()` | 2181 | Opens the email dialog for sharing the current change as a link, a PDF or both. |
| `closeShareEmailModal()` | 2196 | Closes the share-by-email dialog. |
| `sendShareEmail()` | 2204 | Emails the current change to the address given, with the link, the PDF or both, and an optional message. |
| `blobToBase64(blob)` | 2276 | Reads a Blob as base64, without the data: prefix. |
| `buildPdfContent()` | 2292 | Builds the HTML the change's PDF is made from - header, details and each rich-text section that has content. |
| `openChangeContextMenu(event, changeId)` | 2392 | Opens the right-click menu for a change card at the cursor, rebuilding its submenus and keeping it on screen. |
| `closeChangeContextMenu()` | 2425 | Closes the change right-click menu. |
| `ctxTargetChange()` | 2431 | The change record backing the current context target (for the ✓ tick). |
| `renderCtxLookupSubmenu(sub, rows, currentName, emptyKey, onclickFn)` | 2437 | Shared submenu HTML builder. rows = active lookup rows; currentName = the change's current value for this field (name string) so we can tick it. |
| `populateContextChangeStatusSubmenu()` | 2456 | Fills the right-click Status submenu, ticking the change's current status. |
| `populateContextChangePrioritySubmenu()` | 2462 | Fills the right-click Priority submenu, ticking the change's current priority. |
| `populateContextChangeTypeSubmenu()` | 2468 | Fills the right-click Type submenu, ticking the change's current type. |
| `populateContextChangeImpactSubmenu()` | 2474 | Fills the right-click Impact submenu, ticking the change's current impact. |
| `populateContextChangeCompanySubmenu()` | 2480 | Build the Move-to-company submenu (multi-company installs only; hidden at N=1). |
| `saveChangeFieldFromContext(field, value, toastKey)` | 2501 | Field setters. All post to save.php with {id, <field>: <name>}; the change service resolves the lookup by name and applies status side-effects. |
| `setChangeStatusFromContext(name)` | 2524 | Sets the right-clicked change's status. |
| `setChangePriorityFromContext(name)` | 2526 | Sets the right-clicked change's priority. |
| `setChangeTypeFromContext(name)` | 2528 | Sets the right-clicked change's type. |
| `setChangeImpactFromContext(name)` | 2530 | Sets the right-clicked change's impact. |
| `moveChangeToCompanyFromContext(tenantId)` | 2534 | Move a change to another company. The endpoint enforces access + writes the audit entry server-side, then we reload the list and toast its message. |

## API endpoints it calls

- [api/system/get_tenants.php](/code/files/api/system/get_tenants.php.md)

