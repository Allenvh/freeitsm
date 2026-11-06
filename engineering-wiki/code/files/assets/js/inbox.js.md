# assets/js/inbox.js

> JavaScript - 12418 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Tickets](https://github.com/edmozley/freeitsm/wiki/Tickets)

Inbox JavaScript - Service Desk Ticketing System

## Functions

| Function | Line | What it does |
|---|--:|---|
| `closeMessageEnabled()` | 32 | Resolves true when closing a ticket should offer a one-off closing message (see the note above). |
| `safeEmailHtml(html)` | 76 | Defensive HTML cleaner for email bodies. The real work lives in assets/js/safe-html.js, shared with the self-service portal so the two surfaces that display the same email bodies can never drift apart. |
| `emailBodyHost(rawHtml, cls, bodyType)` | 114 | Returns an empty host <div> for one message body and stashes the cleaned body under its token. |
| `hydrateEmailBodies(root)` | 134 | Fills every pending email-body host under root with its cleaned HTML inside a Shadow DOM. |
| `mcOpened()` | 187 | Which messages this analyst has opened, so a thread they are working through does not re-collapse under them on every refresh. Per browser, deliberately: it is a reading position, not a preference worth a round trip. ⚠️ Wrapped, because localStorage THROWS in a private window rather than returning null. |
| `mcRemember(id, open)` | 193 | Records in localStorage that a collapsed message was opened or closed, keeping the last 400. |
| `mcApply(host, opts)` | 210 | Collapse one rendered message if it is taller than the threshold. |
| `paint(open)` | 243 | Shows the message expanded or collapsed and sets the toggle's label and aria-expanded. |
| `tgGroupOlder(container)` | 268 | --------------------------------------------------------------------- FOLD THE OLDER PART OF A LONG TICKET (idea #4 from discussion #104) |
| `mcSweep(root)` | 320 | Sweep a freshly rendered thread. The LAST message host is the newest — the thread renders oldest-first — and is the one that stays open. |
| `ticketCompanyId(email)` | 362 | The company a ticket's lookup lists should come from. NULL tenant_id = unrouted, which Default owns by convention. |
| `listForTicket(byCompany, fallback, email)` | 369 | Per-company list if we have one for this ticket, else the page-wide list. |
| `logAudit(ticketId, fieldName, oldValue, newValue)` | 414 | Helper function to log audit entries |
| `getDisplayName(type, id)` | 432 | Helper to get display name for IDs |
| `sharedApiBase()` | 452 | Resolve API base for shared endpoints (api/system/...) — works whether the page is at the repo root or inside a module folder. |
| `loadFolderGroupingPreference()` | 457 | Loads the analyst's saved folder grouping (department, analyst or team) and syncs the toggle buttons. |
| `loadViewFilterPreference()` | 487 | Loads the saved view filter (My tickets / Hide closed) from user preferences and repaints its UI. |
| `viewFilterMineActive()` | 501 | "Mine" means nothing while grouped by analyst — every other analyst's folder would read 0. The saved choice is kept and comes back with the other groupings. |
| `analystFolderLabel(an)` | 508 | What an analyst folder is CALLED — "My tickets" for your own (#149). Used for the folder row and the list title; NOT where a real name is needed, such as the toast after dragging a ticket onto someone. |
| `viewFilterParams()` | 513 | Returns the view filter as a query string (mine=1, hide_closed=1) for the counts and list calls. |
| `setViewFilter(key, value)` | 525 | Turns one view filter option on or off, reloads the folder counts and list, and saves the choice. |
| `updateViewFilterUI()` | 541 | Repaints the view filter button, its menu controls and the chips that show which filters are on. |
| `toggleViewFilterMenu(event)` | 569 | Opens or closes the view filter drop-down beside Search. |
| `closeViewFilterMenu()` | 580 | Closes the view filter drop-down if it is open. |
| `loadSearchIncludeClosedPreference()` | 596 | Search's own "Include closed tickets" (#149) — separate from the view filter, and ON by default: a search is usually a hunt for an old ticket. |
| `saveSearchIncludeClosed(checked)` | 606 | Saves the Search "Include closed tickets" tick as a user preference. |
| `searchIncludeClosed()` | 615 | Returns whether Search should include closed tickets (true when the tick box is missing). |
| `setFolderGrouping(mode)` | 623 | Switches the folder pane between department, analyst and team grouping, resets to All Tickets and saves the choice. |
| `initAttachmentHandlers()` | 691 | Initialize attachment drag/drop and file input handlers |
| `handleFiles(files)` | 732 | Handle selected files |
| `renderAttachments()` | 744 | Render attachment list |
| `removeAttachment(index)` | 766 | Remove attachment by index |
| `getFileIcon(filename)` | 772 | Get file icon based on extension |
| `formatFileSize(bytes)` | 790 | Format file size |
| `initTinyMCE()` | 799 | Initialize TinyMCE editor |
| `setup(editor)` | 833 | Keeps a reference to the TinyMCE editor in emailEditor once it is created. |
| `loadDepartments()` | 840 | Load departments (filtered by team membership) |
| `loadTicketTypes()` | 856 | Load ticket types |
| `loadTicketTeams()` | 882 | The teams a ticket can be handed to (#1566). |
| `loadTicketClassification()` | 909 | Load the classification lists and the three switches (#1540) — one request, because a form that had the categories but not the switches could briefly render a field the settings say to hide. |
| `categoriesForType(typeId, email)` | 932 | Categories on offer for one ticket type. A category with no type is offered whatever the type is; one tied to a type is offered only on that type. The tie lives on the ROOT, so `effective_type_id` is what to compare - a sub-category has none of its own. |
| `categoryOptionsFor(typeId, selectedId, email)` | 943 | Options for a category picker, indented so the tree reads as a tree. `selectedId` is always included even when the type filter would drop it - a ticket already carrying a category must never render as blank, or the next save silently clears a value nobody chose to clear. |
| `loadTicketOrigins()` | 958 | Load ticket origins |
| `loadMoveCompanies()` | 976 | Load the companies this analyst can move tickets into (multi-company installs only). |
| `moveTicketCompany(targetId)` | 993 | Move the current ticket to another company. targetId optional (used by the wrong-company banner's quick-move); otherwise read from the Company dropdown. |
| `loadTicketStatuses()` | 1020 | Load ticket statuses (active only) for the reading-pane Status dropdown |
| `loadTicketPriorities()` | 1034 | Load ticket priorities (active only) for the reading-pane Priority dropdown |
| `loadAnalysts()` | 1048 | Load analysts |
| `loadFolderCounts()` | 1062 | Load folder counts |
| `renderFolders()` | 1078 | Render folder structure. Branches on folderGrouping (department vs analyst). |
| `updateActiveFolderClasses()` | 1322 | Update only the .active class on existing folder/subfolder rows — does NOT rebuild the folder list. Used by selection paths so the .subfolder-group expand transition (which requires the element to persist) actually fires. |
| `toggleFolder(folderId, groupId, opts = {})` | 1366 | Toggle folder expansion. Works for both department and analyst folders. opts.kind — 'department' (default) or 'analyst' opts.selectAfter — if false, don't change the active filter/view (used by drag hover) opts.forceExpand — if true, only expand (no toggle), used by drag hover |
| `selectFolder(type, id = null)` | 1434 | Select folder |
| `selectDeptStatus(deptId, status)` | 1458 | Select department + status |
| `selectTeamStatus(teamId, status)` | 1468 | Select team + status (#1566) — the team twin of selectDeptStatus. |
| `selectUnassignedStatus(status)` | 1479 | Select a status within Unassigned. ⚠️ What "unassigned" means follows the active grouping, so this filter has to carry the grouping with it. |
| `selectAllStatus(status)` | 1489 | Select a status across every ticket (GH #73) — the All Tickets equivalent of selectDeptStatus / selectAnalystStatus. |
| `selectAnalystStatus(analystId, status)` | 1498 | Select analyst + status |
| `attachEmailDragHandlers()` | 1521 | Makes every ticket row draggable onto folders, carrying the whole selection when the row is part of it. |
| `attachFolderDropHandlers()` | 1599 | Wires the folder pane: clicks on status sub-folders, and dragover/drop on folders that accept tickets. |
| `cancelDragHover()` | 1679 | Cancels the pending hover-to-expand timer for a folder during a drag. |
| `handleTicketDrop(targetEl, ticketId, ticketNumber)` | 1693 | Applies a ticket dropped onto a folder - department, analyst, team and/or status, or Trash. |
| `teamName(id)` | 1731 | #1566 teams. From the row, which carries assigned_team_id for exactly this. |
| `loadEmails()` | 1879 | Load emails based on current filter |
| `companyChip(email)` | 1960 | Which company a ticket belongs to, shown on the row in the consolidated view. |
| `renderEmailList()` | 1970 | Render email list |
| `loadInboxSlaIndicators()` | 2034 | Populate the SLA dot in each email row via the batch endpoint. |
| `renderInboxSlaIndicator(row)` | 2061 | Build the inline dot + label for one email row. |
| `pickTarget()` | 2063 | Picks the SLA target to show - an outstanding response first, then resolution. |
| `fmt(mins)` | 2084 | Formats a number of minutes as a short duration such as 45m, 2h or 1h30m. |
| `setSelectedEmailRow(emailId)` | 2105 | Move the "selected" highlight to one row without rebuilding the whole list. Rebuilding (renderEmailList) on every click also re-fired the batch SLA fetch, which made the SLA pills flash off and back on — this just toggles a class. |
| `visibleEmailIds()` | 2147 | Email ids in the order they are on screen — range selection follows what you SEE. |
| `ticketIdForEmail(emailId)` | 2153 | The ticket id behind a row (actions work on tickets; rows are keyed by email). |
| `selectedTicketIds()` | 2161 | Returns the ticket ids behind every selected row. |
| `selectionCount()` | 2166 | Returns how many rows are selected. |
| `renderSelectionUi()` | 2171 | Paint the list: `.selected` on every chosen row, `.kb-focus` on the cursor. |
| `clearMultiSelection({ repaint = true } = {})` | 2192 | Shrinks the selection back to the ticket open in the reading pane, optionally repainting the list. |
| `handleEmailRowClick(event, emailId)` | 2204 | The one entry point for a click on a row. Every modifier combination lands here so the rules live in one readable place rather than spread across handlers. |
| `loadMultiSelectPanePreference()` | 2270 | Loads the analyst's choice of what the screen shows when several tickets are selected (summary, keep or bar). |
| `onSelectionChanged({ clickedId = null } = {})` | 2281 | Called whenever the selection changes. Routes to whichever surface is chosen. |
| `updateSelectionSurfaces()` | 2331 | Re-run the surface rendering without recomputing the selection. |
| `selectionActionsHtml(compact)` | 2340 | The shared action buttons, used by both the summary pane and the bar. |
| `renderSelectionSummaryPane()` | 2352 | Replaces the reading pane with a "N tickets selected" summary listing them with the bulk actions. |
| `renderSelectionBar()` | 2378 | Shows the compact selection bar above the list with the count and the bulk actions. |
| `hideSelectionBar()` | 2395 | Hides and empties the selection bar. |
| `renderKeepModeWarning()` | 2404 | The strip is CREATED here rather than living in the page markup, because `selectEmail()` replaces the whole reading pane's innerHTML — a static element would be destroyed the moment the ticket it warns about finished loading. Re-prepending is self-healing and needs no change to the pane's layout. |
| `hideKeepModeWarning()` | 2424 | Removes the "several selected" warning strip from the reading pane. |
| `clearSelectionToNone()` | 2430 | Clear right down to nothing selected (the ✕ / Escape route). |
| `selectAllVisible()` | 2440 | Select every row currently listed (Ctrl+A). |
| `moveSelectionCursor(delta, { extend = false, keepSelection = false })` | 2471 | Moves the keyboard cursor up or down the list. |
| `scrollRowIntoView(emailId)` | 2509 | Scrolls the list's own scroll container so the row is visible, never the page itself. |
| `toggleFocusedRow()` | 2536 | Space — toggle the row under the cursor, the keyboard's Ctrl+click. |
| `applyToSelection(endpoint, payloadFor, label)` | 2614 | Apply one change to every selected ticket. |
| `bulkSetField(fields, label)` | 2664 | Set one field across the selection. |
| `openSelectionMenu(event, kind)` | 2686 | Opens the drop-down of choices (status, priority, department or assignee) for a bulk action button. |
| `closeSelectionMenu()` | 2752 | Removes the bulk action drop-down. |
| `bulkDeleteSelection()` | 2763 | Asks for confirmation, then moves every selected ticket to Trash and clears the selection. |
| `bulkProgressHtml()` | 2780 | The progress line both surfaces show mid-run. |
| `selectEmail(emailId)` | 2789 | Select and display email by email ID |
| `loadTicketById(ticketId)` | 2849 | Load and display ticket by ticket ID (from URL parameter) |
| `displayEmail(email, recordings)` | 2875 | Display email in reading pane |
| `openContextLinkProblem()` | 3263 | Right-click "Link to problem…" — targets whichever ticket was right-clicked, even if a different one is open in the reading pane. |
| `openLinkProblemModal(ticketId, ticketRef, subject)` | 3276 | Opens the Link to problem dialog for a ticket and loads the open problems. |
| `closeLinkProblemModal()` | 3286 | Closes the Link to problem dialog. |
| `linkProblemSearchDebounced()` | 3288 | Reloads the problem list 250ms after the last keystroke in its search box. |
| `loadLinkProblemList()` | 3291 | Lists open problems matching the search, with a "create a new problem" row at the top. |
| `pickProblem(problemId)` | 3309 | Links the ticket to the problem that was clicked. |
| `createProblemFromIncident()` | 3311 | Creates a new problem titled from the ticket's subject and links the ticket to it. |
| `doLinkTicketProblem(target)` | 3323 | Links the dialog's ticket to a problem, closes the dialog and reloads the ticket if it is open. |
| `unlinkTicketFromProblem(problemId)` | 3338 | Removes the link between the open ticket and a problem, then reloads the ticket. |
| `openContextLinkChange()` | 3353 | Right-click "Link to change…" — targets whichever ticket was right-clicked, even if a different one is open in the reading pane. |
| `openLinkChangeModal(ticketId, ticketRef, subject)` | 3366 | Opens the Link to change dialog for a ticket and loads the changes. |
| `closeLinkChangeModal()` | 3376 | Closes the Link to change dialog. |
| `linkChangeSearchDebounced()` | 3378 | Reloads the change list 250ms after the last keystroke in its search box. |
| `loadLinkChangeList()` | 3381 | Lists changes matching the search, with a "create a new change" row at the top. |
| `pickChange(changeId)` | 3401 | Links the ticket to the change that was clicked. |
| `createChangeFromIncident()` | 3403 | Creates a new change titled from the ticket's subject and links the ticket to it. |
| `doLinkTicketChange(changeId)` | 3415 | Links the dialog's ticket to a change, closes the dialog and reloads the ticket if it is open. |
| `unlinkTicketFromChange(changeId)` | 3430 | Removes the link between the open ticket and a change, then reloads the ticket. |
| `buildMergeBanner(email)` | 3460 | The merge banner, in both directions. |
| `undoMerge(mergeId)` | 3544 | Undo a merge — take the messages back and reopen this ticket. |
| `openTicketByNumber(ticketNumber)` | 3574 | Jump to a ticket by its reference — used by the merge banner's button. |
| `rpBadge(type, id)` | 3597 | The ⓘ preview badge (#91), guarded. |
| `buildLinksSection(email)` | 3607 | Builds the HTML for the ticket's Links strip - problem, change, linked-ticket and tracker pills, plus the "Link to..." menu. |
| `tp(item, prefix)` | 3632 | Builds one linked-ticket pill with its relation prefix (Parent, Child, Duplicate...) and an unlink cross. |
| `syncLinksStripEmpty()` | 3706 | Hide the strip's "Not linked to anything yet" note once anything at all has been linked — including the pills that arrive after the strip was drawn. Called by both async loaders; safe to call when the note isn't there. |
| `openStripPicker(placeholder)` | 3732 | Shared picker chrome for the Links strip. |
| `close()` | 3744 | Hides the strip picker and empties it. |
| `toggleLinkAddMenu(e)` | 3751 | "Link to…" popover: pick Problem / Change / Ticket, then open its picker for the open ticket. Closes on the next click anywhere. |
| `closeLinkAddMenu()` | 3759 | Closes the "Link to..." popover menu. |
| `linkAddChoose(kind)` | 3766 | Closes the "Link to..." menu and opens the modal or strip picker for the chosen kind of link on the open ticket. |
| `loadTicketTasks(ticketId)` | 3799 | Fetches the tasks linked to a ticket into tasksForTicket and draws them as pills in the Links strip. |
| `renderTicketTasks(ticketId)` | 3818 | Draws tasksForTicket as task pills (done box, subtask progress, unlink cross) and hides the strip's empty note when there are any. |
| `openLinkTaskPicker(ticketId)` | 3860 | One picker, both verbs. Typing searches existing tasks; if what you typed matches nothing (or nothing quite right) the first row creates a task with that title. That is why linking and creating are not two menu entries: you see the near-matches as you type, so you link the task that already exists instead of quietly making a second one. |
| `render()` | 3872 | Draws the task picker rows - the "create" row first, then existing tasks - and binds a mousedown to each. |
| `pick(r)` | 3922 | Creates a task from the typed title, or links the chosen existing task after asking first when it would move off another ticket. |
| `search(typed)` | 3952 | Searches linkable tasks for the typed text and rebuilds the list with a "create" row at the top, even when the search fails. |
| `createTaskForTicket(ticketId, title)` | 3979 | Creates a new task with the given title, linked to the ticket. |
| `linkTaskToTicket(ticketId, taskId)` | 3985 | Links an existing task to the ticket (moving it off any other ticket it was on). |
| `postTaskLink(ticketId, body, okMessage)` | 3995 | Posts a create-or-link request to save_ticket_task.php, closes the strip picker and shows a toast. |
| `unlinkTicketTask(event, taskId, ticketId)` | 4019 | Asks, then unlinks a task from the ticket (the task itself is kept) and reloads the task pills. |
| `openEscalateTrackerModal(ticketId, ref)` | 4064 | Opens the "escalate to an external tracker" modal for a ticket. |
| `renderEscalateAttachments(files)` | 4131 | The files that will travel with the issue. |
| `renderEscalateConfidential(isConfidential)` | 4159 | A confidential ticket (discussion #62) can still be escalated, but only on purpose: a warning and a box to tick, above the attachments. The server refuses the escalation without it, so this is the explanation, not the guard. |
| `closeEscalateTrackerModal()` | 4178 | Closes the escalate-to-tracker modal and forgets which ticket it was for. |
| `submitEscalateTracker()` | 4189 | Raises the issue in the chosen tracker from the escalate modal, toasts the new issue key and reloads the ticket. |
| `openContextLinkTicket()` | 4236 | Right-click "Link to ticket…" — targets whichever ticket was right-clicked. |
| `openLinkTicketModal(ticketId, ticketRef, subject)` | 4249 | Opens the link-ticket modal for a source ticket with "Related" preselected, and loads the list of tickets to link to. |
| `closeLinkTicketModal()` | 4260 | Closes the link-ticket modal. |
| `linkTicketSearchDebounced()` | 4262 | Reloads the link-ticket list 250ms after the last keystroke in its search box. |
| `loadLinkTicketList()` | 4267 | Fills the link-ticket modal with the tickets that may be linked to the source ticket, filtered by the search box. |
| `pickLinkTicket(targetId)` | 4285 | Links the source ticket to the chosen ticket with the selected relation, then refreshes the open ticket if it is the source. |
| `unlinkTicketLink(linkId)` | 4303 | Removes a ticket-to-ticket link and refreshes the open ticket. |
| `buildRecordingsStrip(recordings)` | 4318 | Builds the HTML strip of screen recordings on a ticket - a video player and file name, size and duration for each - or an empty string when there are none. |
| `formatRecordingDuration(seconds)` | 4344 | Formats a number of seconds as m:ss. |
| `loadCorrespondenceThread(ticketId, isAuto = false)` | 4352 | Load and display all correspondence for a ticket. isAuto=true marks a 15s background refresh (channel tickets) so we don't disturb the analyst's draft. |
| `stopChannelAutoRefresh()` | 4429 | Stops the 15-second thread refresh used for channel (WhatsApp, Telegram...) tickets, if it is running. |
| `Tc(key, params)` | 4441 | Shared translation helper for every channel-composer string (WhatsApp/ Telegram/Slack reply box, its templates, and its AI actions), falling back to the key itself — same convention as the other per-module `t()` wrappers in this file (e.g. the search modal's `T()` above) — so a missing key is visible rather than silently blank. |
| `channelDisplayLabel(channelType)` | 4446 | A human label for a channel, used in the composer header and toasts. |
| `renderChannelComposer(ticketId)` | 4453 | Render (or remove) the inline channel reply composer for WhatsApp-style tickets. Email tickets use the existing email modal and get no composer here. |
| `loadChannelTemplates()` | 4535 | Load the templates matching this channel's provider into the picker. |
| `onChannelTemplatePick(ticketId)` | 4555 | When a template is chosen, render an input per {{n}} variable + a live preview. |
| `updateChannelTemplatePreview()` | 4574 | Live preview of the rendered template (placeholders filled in). |
| `sendChannelTemplate(ticketId)` | 4585 | Send the chosen template. |
| `sendChannelMessage(ticketId)` | 4614 | Send the analyst's reply out over the ticket's channel. |
| `sendChannelAttachment(ticketId)` | 4649 | Send a file (image, document, …) picked via the composer's Attach button. The composer's text box, if anything is typed in it, becomes the caption — Telegram shows a caption under the photo/document, same as a person would type one there directly, so reusing the textarea needs no separate field. |
| `aiSuggestChannelReply(ticketId)` | 4686 | AI: draft a suggested reply into the composer (analyst reviews before sending). |
| `aiSummariseChannel(ticketId)` | 4710 | AI: summarise the conversation and save it as an internal note on the ticket. |
| `assignDepartment()` | 4732 | Assign department |
| `assignTicketType()` | 4774 | Assign ticket type |
| `assignTeam()` | 4818 | Hand the ticket to a team, or take it out of one (#1566). |
| `nameFor(id)` | 4824 | Returns the name of a team id from ticketTeams, or an empty string, for the audit line. |
| `refilterCategoriesForType()` | 4861 | Re-render both category pickers against the ticket's current type, dropping any selection the new type does not allow — matching what the server just did. Says so out loud: a value disappearing with no explanation reads as a bug. |
| `assignCategory()` | 4885 | Assign the "reported as" category. |
| `assignClosureCategory()` | 4890 | Assign the "turned out to be" category — the same tree, asked again at close. |
| `assignResolutionCode()` | 4895 | Assign the resolution code — how it ended, not what it was about. |
| `assignClassificationField(selectId, field, auditLabel)` | 4901 | The three share one path: read the select, send just that field, audit the change with the readable label rather than the id. |
| `labelFor(id)` | 4909 | Returns the readable label for an id of this field - the resolution code name, or the category's full path - for the audit line. |
| `assignStatus()` | 4947 | Assign status |
| `openAttach()` | 4985 | Opens the attach-checklist modal for the ticket, when the Checklists script is loaded. |
| `openChecklist()` | 5034 | Opens the ticket's checklist modal, when the Checklists script is loaded. |
| `listOf(steps)` | 5042 | Formats outstanding mandatory steps as text, grouped under the name of their checklist, for the confirm dialogue. |
| `isClosedStatusName(name)` | 5165 | Is this status name one that closes a ticket? |
| `mandatoryFieldsCheck(ticketIds)` | 5173 | Tickets that would close with mandatory fields empty, or null when the question could not be answered. Asked in chunks for a large selection. |
| `confirmCloseWithMandatoryFields(ticketIds)` | 5200 | Ask, then say. Resolves true when the close may go ahead: nothing is missing, the analyst chose to close anyway, or the question could not be answered. Resolves false when nothing should be sent. |
| `fieldsOf(tk)` | 5207 | Returns a ticket's missing mandatory fields as a comma-separated list of labels. |
| `lines(list)` | 5235 | Several: one line per ticket, capped so a big selection stays readable. |
| `assignPriority()` | 5264 | Assign priority. Sends priority_id (or null for the "no priority" blank option) to assign_ticket.php; the SLA engine recomputes lazily on next read, so we don't need a separate recompute call here. |
| `confidentialIcon()` | 5300 | Normal / Confidential (discussion #62). Confidential keeps a ticket from the requester's managers; the service desk is unaffected. Lowering it back to Normal is only ever done here, by a person - nothing automatic does. |
| `assignSensitivity()` | 5309 | Saves the ticket's sensitivity (Normal or Confidential) from the reading pane, audits the change and refreshes the list. |
| `label(v)` | 5313 | Returns the translated label for a sensitivity value, for the audit line. |
| `assignOrigin()` | 5340 | Assign origin |
| `assignFirstTimeFix()` | 5369 | Assign first time fix |
| `assignItTraining()` | 5398 | Assign IT training provided |
| `assignOwner()` | 5427 | Assign owner (analyst) |
| `requestCsatSurvey()` | 5457 | Delete ticket |
| `deleteTicket()` | 5483 | Asks, then moves the open ticket to the trash, clears the reading pane and refreshes the folder counts and list. |
| `restoreTicketFromTrash(ticketId)` | 5523 | Restore a ticket from the Trash folder. |
| `clearReadingPaneIfTicket(ticketId)` | 5542 | Clear the reading pane if it's showing the given ticket (it just left the trash). |
| `permanentlyDeleteFromTrash(ticketId, ticketNumber)` | 5552 | Permanently delete a ticket from the Trash folder (irreversible). |
| `auditAuthor(entry)` | 5584 | Who made a history entry. The endpoint says WHICH case it is (analyst / system / former) rather than leaving a blank name to be guessed at; this turns that into the words, which is where the translations live. |
| `showAuditHistory()` | 5592 | Show audit history modal |
| `closeAuditModal()` | 5655 | Close audit modal |
| `refreshCurrentView()` | 5663 | Refresh current view |
| `escapeHtml(text)` | 5671 | Utility: Escape HTML |
| `rowNameSuffix(email)` | 5708 | The name with its separator, or nothing at all when the name is off — so "off" leaves "SD-1042" and not "SD-1042 - ", which is what appending an empty name to a hardcoded " - " would have produced. |
| `inboxRowName(email)` | 5716 | Returns the name shown on a ticket's list row - the requester, the last sender, or nothing - per the row display setting. |
| `senderLabel(name, address, withAngles)` | 5733 | Returns an escaped label for a person - the name, or the address when there is no name, or "Unknown sender" when there is neither. |
| `formatDateTime(dateStr)` | 5758 | Formats a UTC date for the ticket list - just the time today, "Yesterday" and the time, otherwise day, month and time. |
| `formatFullDateTime(dateStr)` | 5779 | Utility: Format full date/time (always shows date and time) |
| `formatNaiveFullDateTime(dateStr)` | 5789 | Format a NAIVE wall-clock datetime (a user-entered scheduling value stored WITHOUT a zone, e.g. a ticket's scheduled work-start) exactly as typed — no timezone conversion, so "2pm" reads 2pm for every analyst. Scheduling times are naive; only server-stamped UTC timestamps (received/created/…) convert. |
| `toggleTicketProperties(event)` | 5797 | Toggle ticket properties panel |
| `updatePropertiesSummary()` | 5817 | Update summary values when properties change |
| `loadCmdbObjects(ticketId)` | 5850 | Fetches the CMDB objects linked to a ticket and draws them as pills in the Links strip. |
| `renderCmdbObjects(ticketId)` | 5863 | Draws cmdbObjectsForTicket as CMDB pills, with class and parent in the tooltip and an unlink cross. |
| `openLinkCmdbPicker(ticketId)` | 5882 | Opens the strip picker to search every CMDB object and link the chosen one to the ticket, with arrow-key and Enter support. |
| `renderResults()` | 5891 | Draws the CMDB search results with the highlighted row and binds a mousedown to each. |
| `pick(r)` | 5912 | Links the chosen CMDB object to the ticket, closes the picker and reloads the CMDB pills. |
| `loadTicketDomains(ticketId)` | 5968 | Fetches the domains linked to a ticket and draws them as pills, when the analyst may see Domains. |
| `renderTicketDomains(ticketId)` | 5981 | Draws domainsForTicket as domain pills, with the expiry date in the tooltip and an unlink cross. |
| `openLinkDomainPicker(ticketId)` | 5997 | Opens the strip picker listing the domains that can be linked to the ticket, filtered as the analyst types. |
| `renderResults()` | 6004 | Draws the domain results with the highlighted row and binds a mousedown to each. |
| `pick(r)` | 6022 | Links the chosen domain to the ticket, closes the picker and reloads the domain pills. |
| `search()` | 6037 | Fetches the linkable domains matching the search box. |
| `removeTicketDomain(ev, domainId, ticketId)` | 6059 | Asks, then unlinks a domain from the ticket and reloads the domain pills. |
| `loadTicketProjects(ticketId)` | 6086 | Fetches the projects a ticket is part of and draws them as coloured pills, when the analyst may see Projects. |
| `openLinkProjectPicker(ticketId)` | 6103 | Opens the strip picker listing the projects the ticket can be added to, filtered as the analyst types. |
| `renderResults()` | 6109 | Draws the project results with colour, code and the highlighted row, and binds a mousedown to each. |
| `pick(r)` | 6126 | Links the ticket to the chosen project, closes the picker and reloads the project pills. |
| `search()` | 6140 | Fetches the linkable projects matching the search box. |
| `removeTicketProject(ev, projectId, ticketId)` | 6162 | Asks, then takes the ticket out of a project and reloads the project pills. |
| `removeCmdbObject(ev, linkId, ticketId)` | 6179 | Asks, then unlinks a CMDB object from the ticket and reloads the CMDB pills. |
| `loadTicketAssets(ticketId)` | 6209 | Fetches the equipment linked to a ticket and draws it as pills in the Links strip. |
| `assetDisplayName(a)` | 6222 | The one-line name for an asset: hostname if it has one, else the tag. |
| `renderTicketAssets(ticketId)` | 6229 | Draws assetsForTicket as equipment pills, with type, make, serial and location in the tooltip and an unlink cross. |
| `openLinkAssetPicker(ticketId)` | 6257 | Opens the strip picker for linking equipment to the ticket. |
| `renderResults()` | 6271 | Draws the equipment results under "requester" and "all" headings with the highlighted row, and binds a mousedown to each. |
| `search(q)` | 6307 | Fetches linkable equipment for the text and joins the requester's own and everything else into one keyboard-navigable list. |
| `pick(r)` | 6324 | Links the chosen asset to the ticket, closes the picker and reloads the equipment pills. |
| `removeTicketAsset(ev, linkId, ticketId)` | 6364 | Asks, then unlinks an asset from the ticket and reloads the equipment pills. |
| `loadNotes(ticketId)` | 6384 | Load notes for a ticket |
| `loadTicketAttachments(ticketId)` | 6399 | Load attachments for a ticket |
| `renderAttachmentInfoBar()` | 6414 | Render the attachment info bar |
| `formatDateDMY(dateStr)` | 6446 | A stored UTC timestamp in the analyst's zone and chosen format. |
| `showAttachmentList()` | 6454 | Show attachment list modal |
| `attachmentCardsHtml(trackerLink)` | 6550 | Phone-only body for the attachment list: no table (six columns cannot fit a phone), one full-width card per attachment instead - the preview when the browser can show one, then the filename and every column the table had as label/value lines. Tapping the details downloads, as a table row does. Desktop never calls this (showAttachmentList gates on ticketMenuIsSheet). |
| `line(label, value)` | 6552 | Returns one label/value line for an attachment card, or nothing when the value is empty. |
| `closeAttachmentListModal()` | 6587 | Close attachment list modal |
| `sendAttachmentToTracker(btn)` | 6601 | Send one attachment up to the issue this ticket is linked to. |
| `openAttachment(attachmentId)` | 6650 | Open/download an attachment |
| `renderNotes()` | 6655 | Render notes |
| `safeExternalUrl(url)` | 6749 | A DMS entry's URL is typed by an analyst, so it reaches here as data and must never become an href on trust — `javascript:` in that box would otherwise run on every colleague who opens the ticket. Same allowlist rule as `safeHref()` in documents.js, restated rather than shared because it is one regex and the alternative is a cross-module util contract for two lines. |
| `noteFileSize(bytes)` | 6758 | ⚠️ `formatFileSize()` already exists at the top of this file and is used for email attachments. Reused deliberately — a second one here would mean the same file reported a different size in two places on the same screen. It returns 'NaN undefined' for a null size, so callers guard rather than it being changed under the code that already depends on it. |
| `renderPendingNoteFiles()` | 6765 | Paint the chosen-files list in the modal. Rebuilt wholesale rather than patched: the list is tiny and an index-based remove has to stay in step with the array it indexes into. |
| `openNoteModal()` | 6787 | Open note modal |
| `closeNoteModal()` | 6848 | Close note modal |
| `saveNote()` | 6854 | Save note |
| `correspondentAddress(email)` | 6963 | The address of the PERSON on a message — never our own service desk. |
| `openReplyModal()` | 6977 | Opens the email composer as a reply to the open ticket, addressed to the correspondent with an RE: subject carrying the [SDREF:] reference. |
| `openForwardModal()` | 7029 | Open forward modal |
| `closeEmailModal()` | 7056 | Close email modal |
| `splitPlural(n, baseKey)` | 7096 | Pick the singular or plural translation for a count. |
| `openSplitModal(ticketId, emailId)` | 7110 | Opens the Split ticket dialog for a ticket, reset to empty, then loads its movable messages with the opened-from message pre-ticked. |
| `closeSplitModal()` | 7128 | Closes the Split ticket dialog. |
| `loadSplitMessages()` | 7137 | Fetch every movable message on the ticket and paint the checklist. The whole set comes from the server (markers already excluded) so the dialog offers exactly what the split will accept — the same reason the anchor preview was never counted in JS. |
| `renderSplitList()` | 7163 | Paint the checklist newest-first (matching the reading pane) from splitSelected. |
| `splitToggle(id, on)` | 7185 | Ticks or unticks one message in the split checklist and refreshes the count, subject and warning. |
| `splitSelectNewer(ev)` | 7196 | Tick the opened-from message and everything newer than it — the old contiguous default, now a one-click helper rather than the only option. "Newer" means later in time, which is ABOVE the anchor in the newest-first list. |
| `splitSelectAll(ev)` | 7213 | Ticks every message in the split checklist (the Split button then stays disabled, as moving them all would empty the ticket). |
| `splitClearSel(ev)` | 7221 | Unticks every message in the split checklist. |
| `splitUpdateState()` | 7229 | Recompute the count, subject default, warning and the Split button's state. |
| `undoSplit(splitId)` | 7265 | Undo a split — send the messages back and bin the ticket that was created. |
| `confirmSplit()` | 7299 | Sends the ticked messages and the new subject to split_ticket.php, then reloads the list and opens the newly created ticket. |
| `openSubjectModal()` | 7345 | Opens the Change subject dialog for the right-clicked ticket, prefilled with its current subject from the loaded list. |
| `closeSubjectModal()` | 7363 | Closes the Change subject dialog and forgets which ticket it was for. |
| `saveSubject()` | 7371 | Saves the new subject through save_ticket_subject.php, reloads the list and repaints the reading pane if that ticket is open. |
| `loadMergeSettings()` | 7425 | Returns the install's merge policy (reference mode, originals mode, AI summary), fetched once and cached. |
| `openMergeModal()` | 7443 | Opens the Merge dialog for the selected tickets (two or more): lists them as radio choices and spells out what the configured policy will do. |
| `mergeEffectText(s, count)` | 7497 | Spell out what the configured policy will actually do, in plain words. |
| `closeMergeModal()` | 7510 | Closes the Merge dialog. |
| `confirmMerge()` | 7519 | Merges the other selected tickets into the chosen one through merge_tickets.php and reloads the list. |
| `showMergeSummaryStage(data)` | 7566 | Switches the Merge dialog to its AI briefing stage, showing the merge result line with Save disabled until text arrives. |
| `startMergeSummaryProgress()` | 7601 | Shows a spinner with an elapsed-seconds counter, ticking every second, while the AI briefing is written. |
| `paint()` | 7607 | Redraws the spinner and the seconds elapsed since the request started. |
| `stopMergeSummaryProgress()` | 7617 | Stops the elapsed-seconds timer and hides the AI briefing progress line. |
| `streamMergeSummary(ticketId)` | 7628 | Streams the AI briefing for a merged ticket from ai_merge_summary.php (server-sent events) into the summary textarea. |
| `pump()` | 7648 | Reads the next chunk of the stream, splits it into SSE frames and applies each one, until the stream ends. |
| `saveMergeSummary()` | 7692 | Saves the briefing text on the merged ticket through save_merge_summary.php, then closes the dialog and opens that ticket. |
| `discardMergeSummary()` | 7711 | Closes the Merge dialog without saving the briefing and opens the merged ticket - the merge itself stands. |
| `selectEmailByTicketId(ticketId)` | 7718 | Open whichever list row belongs to a ticket id (after a merge, the survivor). |
| `loadReplyTemplatesForPicker(force = false)` | 7744 | Returns the analyst's reply templates (shared and personal) from get_reply_templates.php, cached unless `force` is true. |
| `toggleReplyTemplateMenu(event)` | 7759 | Opens or closes the reply templates menu in the composer footer, closing the signature menu and re-fetching the templates on every open. |
| `closeReplyTemplateMenu()` | 7780 | Hides the reply templates menu. |
| `renderReplyTemplateMenu()` | 7796 | Builds the reply templates menu: the team's shared templates, the analyst's own (with edit and delete buttons), and a Save draft as template action. |
| `insertReplyTemplate(templateId)` | 7844 | Inserts a reply template at the cursor in the composer, with its merge codes filled in by the server for the open ticket (render_reply_template.php). |
| `openSaveReplyTemplateModal(mode, id)` | 7880 | Opens the Save as template dialog for the current draft, as a new personal template or an update of an existing one. |
| `closeSaveReplyTemplateModal()` | 7902 | Closes the Save as template dialog. |
| `savePersonalReplyTemplate()` | 7909 | Saves the composer's draft as one of the analyst's own reply templates (scope 'mine'), new or updated, then refreshes the cached list. |
| `deleteMyReplyTemplate(id)` | 7944 | Deletes one of the analyst's own reply templates after a confirmation, then refreshes and redraws the menu. |
| `setReplyCleanupVisibility(mode)` | 7984 | Shows the AI Cleanup button only when composing a reply (not a forward) and hides any leftover undo bar. |
| `replyCleanupTextToHtml(text)` | 7991 | Convert plain-text-with-blank-lines (Claude's output) to TinyMCE-friendly HTML |
| `cleanupReplyDraft()` | 8007 | Sends the draft reply to ai_cleanup_reply.php and streams the AI's tidied version back into the editor. |
| `showReplyCleanupUndoBar()` | 8117 | Shows the Undo bar after an AI cleanup with a 30-second countdown, then hides it. |
| `hideReplyCleanupUndoBar()` | 8141 | Hides the AI cleanup Undo bar and stops its countdown timers. |
| `sendEmail()` | 8170 | Send email via Microsoft Graph API |
| `prepareAttachments()` | 8244 | Prepare attachments by converting to base64 |
| `fileToBase64(file)` | 8260 | Convert file to base64 |
| `logout()` | 8274 | Logout |
| `openNewTicketModal()` | 8285 | New Ticket Modal Functions |
| `onNewTicketCompanyChange()` | 8353 | Refill the New Ticket type list once a company is chosen (#1554). |
| `loadNewTicketMailboxes()` | 8372 | Load the mailboxes this ticket can be sent from (scoped to the active company) and populate the New Ticket modal's mailbox picker. Fires async; the modal can open before it returns. |
| `closeNewTicketModal()` | 8405 | Closes the New ticket dialog. |
| `createNewTicket()` | 8414 | Creates a ticket from the New ticket dialog through create_ticket.php, then refreshes the folder counts and list. |
| `reqEls()` | 8529 | Returns the requester picker's elements (search box, results list, chosen chip, new-person fields) in one object. |
| `reqReset()` | 8542 | Back to the empty state: nothing chosen, nothing typed, no new-person form. |
| `reqCloseList()` | 8560 | Hides the requester results list and resets the keyboard cursor. |
| `reqChoose(user)` | 8569 | Commit a chosen requester and swap the input for the chip. |
| `reqShowNew(term)` | 8587 | Reveal the new-person fields, pre-filled from whatever was typed. |
| `reqRenderResults(users, term)` | 8612 | Draws the requester typeahead results, always ending with an "Add someone new" row, and remembers them for keyboard selection. |
| `reqSearch()` | 8648 | Searches people (up to 8) for the requester picker through get_users.php and draws the results. |
| `reqMove(delta)` | 8661 | Move the keyboard cursor. Rows include the trailing "add" row. |
| `reqCommitActive()` | 8681 | Activates the requester row under the keyboard cursor. |
| `reqActivateOption(el)` | 8691 | Acts on a requester row: the add row opens the new-person fields, any other row chooses that person. |
| `initRequesterPicker()` | 8710 | Wires the requester picker once: debounced search as you type, arrow keys, Enter and Escape, mouse picks, clear, cancel and the Browse button. |
| `openReqBrowse()` | 8756 | Opens the Browse people dialog, carrying across the term typed in the picker, and wires its debounced search the first time. |
| `closeReqBrowse()` | 8771 | Closes the Browse people dialog. |
| `reqBrowseLoad()` | 8776 | Loads up to 100 people matching the browse search through get_users.php and lists them as clickable rows. |
| `openSearchModal()` | 8828 | Opens the floating ticket search panel, lined up under the refresh button, makes it draggable and focuses the ticket number box. |
| `closeSearchModal()` | 8858 | Hides the ticket search panel, keeping its fields and results for when it is reopened. |
| `initSearchModalDrag()` | 8883 | Makes the ticket search panel draggable by its header, kept inside the window. |
| `onmousedown(e)` | 8888 | Starts a drag of the search panel from its header (not from the close button). |
| `onmousemove(e)` | 8903 | Moves the search panel with the mouse, clamped to the viewport. |
| `onmouseup()` | 8918 | Ends the search panel drag and removes the move and up handlers. |
| `performSearch()` | 8929 | Runs the ticket search: by ticket number, email address and subject through search_tickets.php, or a content search when the content box is filled (that wins). |
| `renderSearchResults(results)` | 8977 | Draws the ticket search results (reference, subject, sender, status, date) in the search panel. |
| `performContentSearch(query)` | 9007 | Its own function because the results answer a different question and so carry a different shape: a snippet, and which parts of the ticket matched. |
| `renderContentSearchResults(data)` | 9035 | Draws content search results with a snippet and which parts of the ticket matched. |
| `T(k, p)` | 9038 | Translates a tickets.search_modal key, or returns the key when translations are not loaded. |
| `partName(t)` | 9060 | Returns the translated name of a matched ticket part, falling back to the raw part key. |
| `selectSearchResult(emailId)` | 9086 | Opens a search result in the reading pane, leaving the search panel open. |
| `clearSearch()` | 9093 | Empties every search field and the results in the search panel. |
| `setScheduleDuration(minutes)` | 9136 | Put `minutes` in the duration select. |
| `syncScheduleAllDay()` | 9152 | All-day means no start time and no duration, so both fields go away. |
| `openScheduleModal(ticketId, ticketRef, sched)` | 9171 | Open Schedule work. |
| `p(n)` | 9191 | Pads a number to two digits for the date and time inputs. |
| `closeScheduleModal()` | 9224 | Closes the Schedule work dialog. |
| `applyScheduleLocally(ticketId, start, end, allDay)` | 9237 | Reflect a saved schedule in whatever the page is already holding. |
| `saveSchedule()` | 9251 | Saves the scheduled work start, end and all-day flag for the target ticket through schedule_ticket.php and updates the page's copies of it. |
| `clearSchedule()` | 9297 | After a confirmation, clears the target ticket's scheduled work through schedule_ticket.php and wipes it from the page's copies too. |
| `openTicketAiChat()` | 9337 | Opens the Ask AI side panel for the open ticket. |
| `closeTicketAiChat()` | 9362 | Closes the Ask AI side panel and its overlay. |
| `askTicketAi()` | 9368 | Sends the question typed in the Ask AI box and clears the box. |
| `_sendTicketAiMessage(question, isAutoContext)` | 9384 | Posts a question to the knowledge base AI chat (api/knowledge/ai_chat.php) and adds the question and the answer as chat bubbles. |
| `formatTicketAiResponse(text, articlesList)` | 9456 | Turns the AI's answer into HTML: quoted article titles become links to the article, and simple Markdown (bold, italics, code, lists, paragraphs) is rendered. |
| `toggleTicketPopout()` | 9539 | --- Pop-out (full-screen) ticket view --- Toggles a body class that hides the folder list + email list and floats the ticket properties container as a right-hand panel. Pure CSS — no DOM restructuring. Preference persists in localStorage so the analyst's choice sticks across reloads / ticket selections. |
| `selectEmailFullScreen(emailId)` | 9549 | Double-click on an email row: open it AND pop out. Sets the popout pref so the syncPopoutToTicketState call inside displayEmail applies the class once the ticket renders. Goes through the same storage path as the toggle button so the state is consistent (an F5 mid-popout will land you in 3-col view, but as soon as you pick a ticket again you're back in popout). |
| `loadTimeEntries(ticketId)` | 9565 | Loads a ticket's time entries from get_time_entries.php and draws them with the running total. |
| `formatMinutes(mins)` | 9592 | Convert minutes int to a short display string: 45 → "45m", 90 → "1h 30m". |
| `renderTimeEntries(totalMinutes)` | 9603 | Draws the Time entries section: the total, an add form (minutes and notes), and the entries, with a delete button only on the analyst's own. |
| `saveTimeEntry()` | 9655 | Logs time against the open ticket through save_time_entry.php, then reloads the entries. |
| `deleteTimeEntry(id)` | 9690 | After a confirmation, deletes one of the analyst's time entries through delete_time_entry.php and reloads the list. |
| `syncPopoutToTicketState(hasTicket)` | 9716 | Sync body.ticket-popout to the actual reading-pane state. Called by the ticket-render path (hasTicket=true: apply class if pref says so) and by every empty / loading / error state in the reading pane (hasTicket=false: always strip the class). Tying popout to a rendered ticket avoids the trap where an F5 with the pref saved leaves the user with folder + list hidden and the empty "select a ticket" message in the reading pane. |
| `openTicketContextMenu(event, ticketId, ticketRef)` | 9751 | Opens the ticket right-click menu for a row, or the full-screen sheet on a phone. |
| `populateContextStatusSubmenu()` | 9849 | Build the Set-status submenu HTML from the active ticket_statuses lookup. Tick mark on the row matching the currently-open ticket's status (only shows when right-clicking the ticket that's in the reading pane, since context actions can target any ticket regardless of selection). |
| `populateContextPrioritySubmenu()` | 9874 | Build the Set-priority submenu HTML from the active ticket_priorities lookup. Priority is nullable on tickets, so the first row is a "no priority" option that clears the assignment. Same chip + tick pattern as the status submenu — colour swatch from the priority's stored colour. |
| `setPriorityFromContext(priorityId)` | 9904 | Set a ticket's priority from the right-click menu. Empty string clears the priority (priority_id is nullable on tickets). SLA recomputes lazily on the next ticket fetch — no explicit recompute call needed. |
| `populateContextDepartmentSubmenu()` | 9954 | Build the Set-department submenu HTML from the analyst's team departments (same `departments` lookup as the in-panel Department dropdown). department_id is nullable, so the first row is a "(no department)" option that clears it. |
| `setDepartmentFromContext(departmentId)` | 9981 | Set a ticket's department from the right-click menu. Empty string clears it (department_id is nullable). Refreshes folder counts + the list because the inbox can be grouped by department. |
| `populateContextCompanySubmenu()` | 10026 | Build the Move-to-company submenu (multi-company installs only; hidden at N=1). Lists the companies this analyst can access; the current one is ticked when right-clicking the ticket open in the reading pane. |
| `moveToCompanyFromContext(tenantId)` | 10049 | Move a ticket to another company from the right-click menu. The endpoint writes the audit entry server-side, so (unlike the other context actions) there's no client-side logAudit here. |
| `populateContextTypeSubmenu()` | 10079 | Build the Set-type submenu HTML from the active ticket_types lookup (same `ticketTypes` source as the in-panel Type dropdown). ticket_type_id is nullable, so the first row is a "(no type)" option that clears it. |
| `setTypeFromContext(typeId)` | 10105 | Set a ticket's type from the right-click menu. Empty string clears it (ticket_type_id is nullable). |
| `populateContextAssigneeSubmenu()` | 10150 | Build the Assign-to submenu HTML from the loaded analysts list. Picking an analyst sets both assigned_analyst_id and owner_id (mirrors the drag-to-analyst-folder behaviour in assign_ticket.php). The first row is an "Unassigned" option that clears both fields. |
| `populateContextTeamSubmenu()` | 10187 | The Assign-to-team submenu (#1566). |
| `setTeamFromContext(teamId)` | 10228 | Hand a ticket (or the whole selection) to a team. Empty string takes it out of every queue. |
| `setAssigneeFromContext(analystId)` | 10275 | Set a ticket's assignee from the right-click menu. Empty string = unassign. Sends assigned_analyst_id to assign_ticket.php, which sets both assigned_analyst_id and owner_id (same behaviour as drag-to-folder). |
| `setStatusFromContext(statusName)` | 10321 | Set a ticket's status from the right-click menu — works on whichever ticket was right-clicked, even if a different ticket is open in the reading pane. |
| `closeTicketContextMenu()` | 10362 | Closes the ticket context menu (popup or phone sheet) and empties the sheet's second level. |
| `ticketMenuIsSheet()` | 10387 | True when the screen is 768px wide or less, so the ticket menu shows as a full-screen sheet. |
| `openTicketMenuSheet(menu)` | 10397 | Shows the ticket menu as a full-screen phone sheet. |
| `openTicketMenuLevel(menu, label, sub)` | 10439 | Slides a submenu into the phone sheet as its second level, with a Back button and the parent's label. |
| `cancel()` | 10467 | Stops a pending long-press timer. |
| `preventDefault()` | 10485 | No-op stand-in, so the synthetic long-press event can be passed where a real event is expected. |
| `contextMoveToTrash()` | 10506 | Right-click a ticket -> Move to trash (soft-delete the context-menu target). |
| `availableSnoozePresets()` | 10557 | Returns the snooze presets whose name is still true right now ("This evening" drops out after 18:00). |
| `snoozeWakeHour()` | 10562 | The install's snooze wake hour from window.SNOOZE_WAKE_HOUR, or 9 when it is missing or invalid. |
| `atHourLocal(hour, addDays)` | 10569 | A Date at `hour` today plus `addDays`. Mirrors the server's "tonight"/"tomorrow" arithmetic; "tonight" rolls forward a day when 18:00 has already gone. |
| `nextMondayLocal(hour)` | 10578 | Returns the next Monday (1 to 7 days ahead, never today) at the given hour, local time. |
| `formatWakeTime(value)` | 10590 | "Today 15:00" / "Tomorrow 09:00" / "Mon 4 Aug, 09:00" — in the analyst's zone. Deliberately always carries the time: "Thursday" alone leaves them wondering whether the ticket is back first thing or at the end of the day. |
| `inboxRowStyle(field)` | 10634 | Returns the analyst's display style for a row field (priority, status, agent) from INBOX_ROW_DISPLAY. |
| `inboxAnyStyleIs(placement)` | 10641 | Is any field currently asking for this placement? Lets us skip empty containers. |
| `inboxInitials(name)` | 10650 | "Ed Mozley" → "EM". Falls back to the first two characters of a single word, so a mononym or a service account still renders something rather than a blank chip that looks like a bug. |
| `inboxSafeColour(value)` | 10658 | A safe CSS colour, or null. Anything unrecognised is dropped rather than emitted. |
| `inboxRowStripes(email)` | 10664 | The left-edge stripes. Both priority and status may ask for one; they stack. |
| `inboxRowBlocks(email)` | 10683 | The top-right blocks. Same stacking rule as stripes. |
| `inboxRowChips(email)` | 10702 | Pills and dots for the footer row, plus the agent chip. |
| `colourField(field, value, colour)` | 10706 | Adds a pill or dot for one field to the footer chips when that field is set to pill or dot. |
| `snoozeRowPill(snoozedUntil, reason)` | 10747 | The moon pill showing when a snoozed ticket wakes, for its list row. |
| `populateContextSnoozeSubmenu()` | 10761 | Build the Snooze flyout. Rebuilt on every open so the labels carry live times — a menu built at page load would still be offering "Tomorrow · Wed 09:00" on Thursday, and an analyst reads the label, not the key behind it. |
| `ctxTargetIsSnoozed()` | 10779 | Is the context-menu target asleep? Read from the loaded list row, or from the open ticket when the menu was raised on the one in the reading pane. |
| `ctxSnoozeTargets()` | 10787 | Which tickets does a snooze/wake act on? Same rule as every other context action: the whole selection when the menu was raised inside it, else the one row. |
| `snoozeFromContext(preset, untilLocal, reason)` | 10800 | Snoozes the context-menu target (or the whole selection) via snooze_ticket.php. |
| `wakeFromContext()` | 10836 | Wakes the context-menu target, or the whole selection, from the right-click menu. |
| `wakeTickets(ids)` | 10843 | Shared by the context menu and the reading-pane banner button. |
| `openSnoozeModal()` | 10876 | Opens the "snooze until" dialog for the context-menu targets, defaulting to tomorrow at the wake hour. |
| `closeSnoozeModal()` | 10899 | Closes the "snooze until" dialog and forgets its targets. |
| `saveCustomSnooze()` | 10905 | Snoozes the dialog's tickets until the picked date and time, through the same path as the presets. |
| `buildSnoozeBanner(email)` | 10925 | Reading-pane banner on a sleeping ticket. Quiet like the split banners rather than loud like merged-away: nothing is wrong here, the ticket is just resting, and the one thing an analyst wants is the way out of it. |
| `startPresence(ticketId)` | 10965 | Start (or move) the heartbeat to a ticket. Safe to call repeatedly. |
| `stopPresence()` | 10985 | Stop announcing entirely (the reading pane emptied). |
| `beatPresence()` | 11000 | Sends one presence heartbeat for the open ticket and repaints who else is in it. |
| `leavePresence(ticketId)` | 11027 | Fire-and-forget leave. Uses sendBeacon during teardown, when fetch is unreliable. |
| `setPresenceComposing(on)` | 11049 | Tell the server whether our composer is open. |
| `joinNames(names)` | 11062 | Join names the way a person would: "A", "A and B", "A, B and C". The conjunction is translated — `t()` returns the KEY when a string is missing, so it must be a real one rather than a `\|\| 'and'` fallback that would render the literal text "common.and" on screen. |
| `renderPresenceStrip()` | 11074 | Paint the strip. Two states, because they deserve different weight: someone merely having the ticket open is information, someone WRITING is the moment your work becomes duplicated effort. |
| `renderComposerCollisionWarning()` | 11124 | The warning inside our own composer. |
| `openTrashContextMenu(event)` | 11149 | Opens the Trash folder's right-click menu (Empty trash) at the cursor, kept inside the viewport. |
| `closeTrashContextMenu()` | 11162 | Closes the Trash folder's right-click menu. |
| `emptyTrash()` | 11171 | Permanently deletes every ticket in the trash after a confirm, via empty_trash.php. |
| `openContextLinkTask()` | 11237 | Right-click → Link to task (discussion #83). |
| `openLinkTaskModal(ticketId, ticketRef)` | 11258 | Opens the phone "Link to task" dialog for a ticket and loads the task list. |
| `closeLinkTaskModal()` | 11267 | Closes the phone "Link to task" dialog. |
| `linkTaskSearchDebounced()` | 11269 | Reloads the "Link to task" list 250ms after the analyst stops typing. |
| `loadLinkTaskList()` | 11276 | Fills the phone "Link to task" list from search_linkable_tasks.php for the typed search. |
| `pickLinkTask(idx)` | 11314 | Links the picked task to the ticket, or creates a task from the typed text when idx is -1. |
| `openContextLinkCmdb()` | 11344 | Opens the "Link CMDB object" dialog for the right-clicked ticket, with an empty search and log. |
| `closeContextCmdbModal()` | 11357 | Closes the "Link CMDB object" dialog, refreshing the open ticket's CMDB list if it gained a link. |
| `linkContextCmdbObject(objectId, objectName)` | 11405 | Links a CMDB object to the right-clicked ticket and adds it to the dialog's log. |
| `openContextSchedule()` | 11442 | Schedule work on the right-clicked ticket, which may not be the one open in the reading pane. The row in `emails` carries the schedule columns, so the modal prefills what this ticket is already set to rather than the open one's. |
| `openContextRecordTime()` | 11453 | Opens the "Record time" dialog for the right-clicked ticket, set to now in the display zone. |
| `closeContextTimeModal()` | 11470 | Closes the "Record time" dialog. |
| `saveContextTimeEntry()` | 11479 | Saves a time entry on the right-clicked ticket via save_time_entry.php, sending the time as UTC. |
| `loadSlaState(ticketId)` | 11528 | --- SLA panel in the reading pane --------------------------------------- Fetches /api/tickets/get_ticket_sla.php and renders a small status panel showing response + resolution targets, elapsed / remaining / percent, and a green/amber/red colour-coded badge per target. Hides itself silently if SLA is disabled for the ticket (no priority, no target set, ticket pre-dates cutoff, SLA disabled globally) — so the section just doesn't appear and doesn't bother the analyst with "SLA disabled for this ticket" noise. |
| `renderSlaPanel(sla)` | 11543 | Renders the SLA panel in the reading pane - a row with a progress bar for response and for resolution. |
| `fmt(mins)` | 11549 | Formats minutes as "45m", "2h" or "2h 15m" (negative keeps its sign, null shows a dash). |
| `renderRow(label, target)` | 11559 | Builds one SLA target row - green when achieved or under 80%, amber from 80%, red once breached. |
| `ticketIsClosed(email)` | 11619 | Is this ticket in a status the admin has marked as closed? |
| `buildWriteUpButton(email)` | 11626 | The "Write up" toolbar button, only when the Knowledge write-up is turned on and the ticket is closed. |
| `openWriteUpModal()` | 11639 | Opens the "Write this up" dialog for the open ticket (building it on first use) and starts the stream. |
| `closeWriteUpModal()` | 11672 | Hides the "Write this up" dialog. |
| `wuRetryWithAnswers()` | 11678 | Sends the analyst's answers to the assistant's questions back with the ticket and streams a new attempt. |
| `wuStream(payload)` | 11698 | Stream the write-up. The server sends the verdict as its own event before any prose, so nothing is rendered until we know whether this is an article or a refusal — otherwise the words "VERDICT: ARTICLE" land in the analyst's draft. |
| `statusEl()` | 11701 | The write-up dialog's status line. |
| `contentEl()` | 11703 | The write-up dialog's content area. |
| `handle(event, data)` | 11724 | Handles one streamed event (error, verdict, text, done) from writeup_stream.php. |
| `wuFinish(data)` | 11775 | Finishes the write-up dialog once the stream is done. |
| `wuSaveDraft()` | 11816 | Saves the write-up to Knowledge as an unpublished draft via writeup_save.php. |
| `loadMySignatures(force)` | 11866 | Loads the analyst's email signatures, cached unless force is set. |
| `signatureWrap(sig)` | 11883 | Wraps a rendered signature in the data-signature div that lets the picker find and replace it. |
| `applyDefaultSignature()` | 11888 | Drop the analyst's default signature into a freshly opened composer. |
| `setSignatureInEditor(sig)` | 11906 | Put this signature in, replacing whichever one is already there. |
| `toggleSignatureMenu(event)` | 11935 | Opens or closes the composer's signature menu, re-fetching the signatures each time it opens. |
| `closeSignatureMenu()` | 11948 | Closes the composer's signature menu. |
| `renderSignatureMenu()` | 11965 | Builds the signature menu: one row per signature (default marked), Remove, and a link to manage them. |
| `pickSignature(id)` | 12001 | Puts the chosen signature into the editor, or removes the current one when id is 0. |
| `aiSummaryOn()` | 12044 | True when an administrator has turned on the AI ticket summary. |
| `aiReadOn()` | 12046 | True when an administrator has turned on "Read this ticket for me". |
| `buildAiSummarySlot()` | 12049 | The slot. Empty markup when the feature is off, so nothing reserves space. |
| `buildReadForMeButton()` | 12055 | The "Read this ticket for me" toolbar button, or '' when the feature is off. |
| `loadAiSummary(ticketId)` | 12072 | Loads the stored AI summary for a ticket from ai_summary.php and renders it. |
| `renderAiSummary()` | 12107 | Paints the AI summary slot: not configured, none yet, or the summary with its meta line and actions. |
| `toggleAiSummary()` | 12172 | Shows or hides the AI summary text and remembers the choice in localStorage. |
| `refreshAiSummary(auto)` | 12187 | Asks ai_summary.php to write a new summary for the open ticket. |
| `showAiSummaryHistory()` | 12240 | Every version ever written, because nothing here is ever overwritten. |
| `openReadForMe()` | 12268 | Opening the panel READS what is already stored — from the database, never the provider — so a briefing you have seen comes back instantly and free. Only "Read again" spends anything, and only when there is nothing stored at all is one written without being asked for. |
| `runReadForMe()` | 12292 | ⚠️ A REASONING MODEL CAN TAKE A MINUTE, and every call here is PAID. The first version showed a motionless "Reading the ticket…" for the whole of it, which is precisely when somebody clicks again — so a slow answer billed twice and the second call overwrote the first. The guard is the part that matters; the counter only stops it LOOKING broken. |
| `aiReadRender(b, versionCount)` | 12328 | One renderer for a stored briefing and a freshly written one, so a re-read cannot come back looking different from what reopening shows. |
| `aiReadShowError(code)` | 12353 | Shows an AI error code, in words, in the AI panel body. |
| `showAiReadHistory()` | 12359 | Every briefing ever written for this ticket — nothing is overwritten. |
| `showAiPanel(title, html)` | 12379 | Opens the shared AI panel with a title and body HTML, building it on first use. |
| `closeAiPanel()` | 12400 | Closes the shared AI panel. |
| `aiErrorText(code)` | 12406 | Every failure this can have, said in words rather than as a code. |

## API endpoints it calls

- [api/system/get_tenants.php](/code/files/api/system/get_tenants.php.md)
- [api/problem-management/list.php](/code/files/api/problem-management/list.php.md)
- [api/problem-management/save.php](/code/files/api/problem-management/save.php.md)
- [api/problem-management/link_ticket.php](/code/files/api/problem-management/link_ticket.php.md)
- [api/problem-management/unlink_ticket.php](/code/files/api/problem-management/unlink_ticket.php.md)
- [api/change-management/list.php](/code/files/api/change-management/list.php.md)
- [api/change-management/save.php](/code/files/api/change-management/save.php.md)
- [api/change-management/link_ticket.php](/code/files/api/change-management/link_ticket.php.md)
- [api/change-management/unlink_ticket.php](/code/files/api/change-management/unlink_ticket.php.md)
- [api/tickets/get_ticket_tasks.php](/code/files/api/tickets/get_ticket_tasks.php.md)
- [api/tickets/search_linkable_tasks.php](/code/files/api/tickets/search_linkable_tasks.php.md)
- [api/tickets/save_ticket_task.php](/code/files/api/tickets/save_ticket_task.php.md)
- [api/tickets/delete_ticket_task.php](/code/files/api/tickets/delete_ticket_task.php.md)
- [api/integrations/connections_for_ticket.php](/code/files/api/integrations/connections_for_ticket.php.md)
- [api/integrations/escalate_ticket.php](/code/files/api/integrations/escalate_ticket.php.md)
- [api/tickets/list_linkable_tickets.php](/code/files/api/tickets/list_linkable_tickets.php.md)
- [api/tickets/create_ticket_link.php](/code/files/api/tickets/create_ticket_link.php.md)
- [api/tickets/delete_ticket_link.php](/code/files/api/tickets/delete_ticket_link.php.md)
- [api/self-service/get_recording.php](/code/files/api/self-service/get_recording.php.md)
- [api/tickets/get_ticket_cmdb_objects.php](/code/files/api/tickets/get_ticket_cmdb_objects.php.md)
- [api/tickets/save_ticket_cmdb_object.php](/code/files/api/tickets/save_ticket_cmdb_object.php.md)
- [api/cmdb/search_objects.php](/code/files/api/cmdb/search_objects.php.md)
- [api/domains/links.php](/code/files/api/domains/links.php.md)
- [api/projects/links.php](/code/files/api/projects/links.php.md)
- [api/tickets/delete_ticket_cmdb_object.php](/code/files/api/tickets/delete_ticket_cmdb_object.php.md)
- [api/tickets/get_ticket_assets.php](/code/files/api/tickets/get_ticket_assets.php.md)
- [api/tickets/search_linkable_assets.php](/code/files/api/tickets/search_linkable_assets.php.md)
- [api/tickets/save_ticket_asset.php](/code/files/api/tickets/save_ticket_asset.php.md)
- [api/tickets/delete_ticket_asset.php](/code/files/api/tickets/delete_ticket_asset.php.md)
- [api/integrations/send_attachment.php](/code/files/api/integrations/send_attachment.php.md)
- [api/documents/download.php](/code/files/api/documents/download.php.md)
- [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md)
- [api/tickets/save_time_entry.php](/code/files/api/tickets/save_time_entry.php.md)
- [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md)
- [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md)

## Loaded by

- [tickets/index.php](/code/files/tickets/index.php.md)

## Traps

> [!WARNING]
> **Line 9457:** escape the WHOLE answer first, then add markup. The answer is model

> [!WARNING]
> **Line 11241:** on a phone the strip picker opens inside the Links sheet, which

