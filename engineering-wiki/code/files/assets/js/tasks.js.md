# assets/js/tasks.js

> JavaScript - 3714 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Task Board and Subtasks Developer Guide](https://github.com/edmozley/freeitsm/wiki/Task-Board-and-Subtasks-Developer-Guide)

FreeITSM Tasks Module — Board, List, Detail Panel, Drag & Drop

## Functions

| Function | Line | What it does |
|---|--:|---|
| `loadCardSettings()` | 119 | Loads the Tasks settings and takes the card fields, tag settings and time scope from them, keeping the built-in defaults for anything missing. |
| `timeAllowedFor(task)` | 140 | Where the time features are offered: 'both', 'tasks', 'subtasks' or 'off' (Settings → Time). A top-level task and a subtask are the same record told apart by parent_task_id, so this is the only thing that distinguishes them. |
| `formatMinutes(mins)` | 148 | Minutes as people say them: 90 -> "1h 30m". Matches the ticket time panel. |
| `loadDropdowns()` | 159 | Fetches the analysts and teams and fills the sidebar's analyst and team filter dropdowns with them. |
| `loadTasks()` | 184 | Fetches the tasks for the current filter (my, all, a team or an analyst), builds each one's search text, refreshes the Project and Contractor filters and redraws the board or list. |
| `setFilter(filter)` | 210 | Switches to one of the filter buttons (my, all, ...), clears the team and analyst dropdowns and reloads the tasks. |
| `setTeamFilter(teamId)` | 232 | Shows one team's tasks, or falls back to "my" when the team dropdown is cleared while it was the active filter. |
| `setAnalystFilter(analystId)` | 245 | Shows one analyst's tasks, or falls back to "my" when the analyst dropdown is cleared while it was the active filter. |
| `buildSearchText(t)` | 258 | Lowercased haystack of a task's title + plain-text description, pre-computed once per load so as-you-type filtering stays cheap |
| `taskMatchesSearch(t)` | 271 | True when every search term appears in the task's pre-built search text (always true with no search). |
| `setSearch(value)` | 278 | Filters the board/list as you type — no server round-trip |
| `clearSearch()` | 287 | Empties the search box and redraws without a search. |
| `taskMatchesTag(t)` | 295 | True when the task carries the tag chosen in the sidebar Tag filter (always true with no tag chosen). |
| `setTagFilter(tagId)` | 301 | Sets the sidebar Tag filter and redraws the board or list. |
| `taskMatchesProject(t)` | 313 | True when the task matches the sidebar Project filter - any project, "none" (no project), or one project id. |
| `setProjectFilter(v)` | 320 | Sets the sidebar Project filter and redraws the board or list. |
| `refreshProjectFilter()` | 332 | The Project filter lists the projects the loaded tasks belong to - nothing to fetch, and never a project with no tasks here to show. Hidden when no task on the board belongs to a project, so an install that does not use Projects sees no change at all. |
| `taskMatchesContractor(t)` | 355 | True when the task matches the sidebar Contractor filter - all, "any" (has a contractor), "none", or one supplier id. |
| `setContractorFilter(v)` | 363 | Sets the sidebar Contractor filter and redraws the board or list. |
| `refreshContractorFilter()` | 373 | Rebuilds the Contractor filter from the suppliers on the loaded tasks, keeping the current choice, and hides the section when no task has a contractor. |
| `applyTagSettings()` | 391 | Populate the sidebar tag filter and show/hide it per the surface setting |
| `saveTaskPreference(key, value)` | 415 | Remember a board preference for this analyst (GH #131). |
| `switchView(view)` | 428 | Switches between the board and the list, shows the board-grouping toggle only on the board, redraws and saves the choice as the analyst's preference. |
| `buildBoardColumns()` | 451 | Build one board column per status — run once after statuses load |
| `startColumnDrag(e, column)` | 487 | Starts dragging a status column by its header to reorder the board. Ignores right clicks and the + button, and only counts as a drag once the mouse has moved 5px. |
| `onMove(e2)` | 498 | Once the mouse has moved 5px, marks the column as dragging and slots it before the first column whose middle the cursor is left of. |
| `onUp()` | 519 | Removes the drag listeners and, if the column was actually dragged, saves the new column order. |
| `persistColumnOrder()` | 537 | Saves the board's column order as the task status order, after re-sorting the local status list to match so menus and dropdowns follow. |
| `switchBoardGroup(group)` | 572 | Switches the board between columns by status and columns by analyst, saves the choice as the analyst's preference and redraws. |
| `renderBoardByAnalyst()` | 587 | Draws the board as one column per analyst with a task in view - the viewer's own column first and always present, then by name, with Unassigned last. |
| `statusRank(name)` | 615 | Within a column, in status order (the board's own column order), then the board position each task already has. |
| `dropOnAnalystColumn(taskId, targetColumn)` | 645 | Drop a card on another analyst's column: reassign it. Returns true if handled. |
| `renderBoard()` | 663 | Draws the board: hands over to `renderBoardByAnalyst()` when grouped by analyst, otherwise fills each status column with its filtered cards and count. |
| `renderCard(t)` | 689 | Returns the HTML for one board card - status, project and contractor chips, title, optional description excerpt, the meta row chosen in Settings -> Card, tags and subtasks. |
| `boardSubtasksHtml(t)` | 791 | The subtasks listed, indented, under their card. Rides on the same Settings -> Card "subtasks" switch as the 2/5 bar: somebody who turned subtasks off the card does not want them back as a list. The personal preference in System -> Preferences tidies the list away once every one is complete. |
| `formatShortDate(dateStr)` | 807 | Short date for the start badge, e.g. "12 Jun" |
| `descExcerpt(html)` | 814 | Plain-text excerpt of a (HTML) description, capped at 250 characters. DOMParser keeps it inert — no scripts run and no resources load. |
| `formatDueBadge(dateStr)` | 823 | Returns a due-date badge: "Overdue" or "Due today" with a matching class, otherwise the day and month. |
| `showQuickAdd(col)` | 840 | Opens a column's quick-add box and focuses it, hiding it again on blur if nothing was typed. |
| `handleQuickAdd(event, status, col)` | 859 | Quick-add key handler: Escape closes the box, Enter creates a task with that title in the column's status, assigned to the current analyst, and reloads the board. |
| `startDrag(e, card)` | 900 | Mouse-down on a board card. Starts a card drag only once the mouse has moved more than 5px, so a plain click still opens the task. |
| `onMove(e2)` | 908 | Starts the drag on the first move beyond 5px, then moves the ghost card with the mouse. |
| `onUp(e2)` | 919 | Removes the drag listeners and finishes the drop if a drag actually started. |
| `initDrag(card, e)` | 934 | Marks the card as dragging and creates the floating ghost copy, recording where in the card it was grabbed and the column it came from. |
| `moveDrag(e)` | 960 | Moves the ghost card with the mouse and shows the drop indicator in the column under the cursor, before the first card whose middle is below it. |
| `endDrag(e)` | 1004 | Earlier version of the card drop handler. |
| `endDrag(e)` | 1089 | Fix: simplified endDrag reorder call |
| `renderList()` | 1179 | Draws the list view: the filtered tasks sorted by the chosen column, one table row each, and marks the sorted column header. |
| `sortList(field)` | 1229 | Sorts the list by a column - the same column again flips the direction, a new one starts ascending. |
| `statusColour(name)` | 1238 | Configured colour for a status name (falls back to a neutral grey) |
| `lookupOptions(list, current)` | 1245 | <option> markup for a status/priority dropdown — always keeps the task's current value, even if it is no longer in the active list |
| `taskViewIsModal()` | 1257 | Does this analyst open tasks in the large window? Set per analyst in System → Preferences and published by tasks/index.php, so the choice is known before anything opens rather than fetched on the way. |
| `applyModalLayout(body)` | 1267 | The same content, laid out in two columns: the things that want width on the left, the properties down the right. Called only in the large-window view. |
| `modalLayoutIsTabs()` | 1312 | True when this analyst has chosen tabs rather than columns for the large task window. |
| `applyModalTabs(body)` | 1338 | Rearranges the task window's existing sections into tabs, filing each block by TASK_MODAL_TABS, dropping empty tabs, adding counts, and opening the first tab that remains. |
| `setModalTabCount(btn, n)` | 1410 | Sets the count badge on a task-window tab, removing the badge when the count is zero. |
| `showModalTab(key)` | 1422 | Shows one tab of the task window and hides the others, keeping aria-selected in step. |
| `toggleModalLayout()` | 1434 | Columns ⇄ tabs, remembered per analyst. Mirrors `toggleTaskView()`. |
| `paintViewToggle()` | 1459 | The header button that swaps between the side panel and the large window. Drawn per state: an expand icon while you are in the drawer, a shrink icon while you are in the window, so the icon shows what pressing it will DO. |
| `paintLayoutToggle()` | 1479 | The columns/tabs button, which only exists in the large window. |
| `toggleTaskView()` | 1501 | Swap the view, remember it, and redraw the task that is already open. |
| `openDetailPanel(taskId)` | 1531 | Fetches a task and opens it in the side panel or the large window, along with its checklists and the people involved. |
| `closeDetailPanel()` | 1573 | Closes the task panel, destroys the description editor and reloads the board. |
| `renderDetailPanel(task)` | 1587 | Builds the whole task panel for one task - fields, company, project, contractor, involved, dates, time, checklists, tags, description, repeats, links, subtasks, comments and documents. |
| `loadInvolved(taskId)` | 1900 | Fetch and draw the Involved section for the open task. |
| `renderInvolved()` | 1925 | Draws the Involved chips (with a done tick when that setting is on) and the add picker, built from the server's candidate list. |
| `postInvolved(action, analystId)` | 1969 | Sends an Involved change (add, remove, done, undone) for the open task, then reloads the section and the board; shows the server's error on a refusal. |
| `addInvolved(analystId)` | 1992 | Adds an analyst to the open task's Involved list. |
| `removeInvolved(analystId)` | 1994 | Removes an analyst from the open task's Involved list. |
| `setInvolvedDone(analystId, done)` | 1996 | Ticks or unticks an involved analyst's part as done. |
| `postTaskChange(payload, failedKey)` | 2013 | Post a change and SAY SO IF IT DID NOT HAPPEN. Returns true on success. |
| `loadMoveCompanies()` | 2050 | Fetches the companies this analyst can reach for the panel's Company picker, and works out the Default company's id for tasks with no company. |
| `moveToCompanyFromPanel(sel)` | 2074 | Move the open task to the company just chosen in the panel. |
| `saveField(field, value)` | 2106 | Saves one field of the open task. A status change first asks about outstanding Involved ticks and checklist steps, and redraws the panel if declined. |
| `loadTaskChecklists(taskId)` | 2131 | Fetches the open task's checklists and templates, hiding the section when checklists are switched off. |
| `renderTaskChecklists()` | 2146 | Draws the task's checklists - steps with ticks, values and who did them, progress bars - and the template picker for attaching another. |
| `T(k, p)` | 2151 | Shorthand for a tasks.detail translation. |
| `chkDragStart(e)` | 2220 | Starts dragging a checklist or step, but only when the drag began on its own handle and not on a step inside it. |
| `chkDragOver(e)` | 2231 | Moves the dragged checklist or step to its new place as it passes over, only within its own container. |
| `chkDragEnd(e)` | 2246 | Ends a checklist or step drag and saves the new order of the lists or of the steps in that checklist. |
| `postTaskChecklist(payload)` | 2266 | Sends a checklist action to the server and redraws from the checklists it returns; on a refusal shows the error and redraws to put the boxes back. |
| `attachTaskChecklist()` | 2288 | Attaches the checklist template chosen in the picker to the open task. |
| `toggleTaskChecklistItem(itemId, box)` | 2295 | Ticks or unticks a checklist step, refusing to tick a step that needs a value while its box is empty. |
| `removeTaskChecklist(checklistId)` | 2310 | Asks for confirmation, then removes a checklist from the open task. |
| `confirmChecklistCompletion(taskId, newStatusName, currentStatusName)` | 2331 | Before completing a task from ANY route in this page (the status box, a drag into a closed column, a subtask tick): ask the server what completing it now would run into. A Critical checklist with steps outstanding is refused here with the list; a Standard one asks first. The server enforces the Critical case regardless - this exists so nobody meets a bare refusal, and so a Standard override is a decision rather than an accident. |
| `isClosed(name)` | 2333 | True when the named status is a closed one. |
| `confirmChecklistCompletionById(taskId)` | 2344 | Asks the server what completing the task would run into: blocks with an alert for Critical steps outstanding, asks to continue for Standard ones. |
| `fmt(groups)` | 2352 | Formats checklist groups as bullet lines - the checklist name, then its outstanding steps. |
| `confirmCloseWithInvolved(newStatusName)` | 2375 | Closing a task while people on it have not ticked their part off. |
| `workValue(stored, allDay)` | 2394 | ── Scheduled work (GH #112) ─────────────────────────────────────── |
| `workPayload(value)` | 2402 | An all-day slot is a whole day, so a date-only box sends midnight to midnight rather than a time nobody chose. |
| `saveWorkStart(value)` | 2411 | Saves the scheduled work start as typed, and reopens the panel when it was cleared because that clears the whole slot. |
| `saveWorkEnd(value)` | 2423 | Saves the scheduled work end as typed, and reopens the panel if the server refuses it so the stored value shows again. |
| `openTaskAndFocusTime(taskId)` | 2437 | "Log time" from the right-click menu. Opens the task and puts the cursor in the minutes box rather than inventing a second little dialog for it — one place where time is recorded, and you land looking at what is already there. |
| `deleteTaskById(taskId, task)` | 2448 | Delete from the right-click menu, which may be a card that is not the one open in the panel — so it takes the id rather than using selectedTaskId. |
| `loadTaskTime(taskId)` | 2478 | Fetches the task's time entries and totals and draws the Time section. |
| `renderTaskTime(taskId, d)` | 2493 | Draws the Time section - the total (plus the total including subtasks when they have time), the add form, and each entry with delete on the viewer's own. |
| `addTaskTime(taskId)` | 2557 | Logs minutes and optional notes against the task and redraws the Time section. |
| `deleteTaskTime(entryId)` | 2582 | Asks for confirmation, deletes a time entry and redraws the open task's Time section. |
| `tagChipHtml(tag, removable)` | 2609 | One tag chip; pass removable=true for the editable chips in the panel |
| `renderTagSection()` | 2620 | Draws the open task's tag chips and the tag picker box. |
| `closeTagPicker()` | 2635 | Closes the tag picker's results list. |
| `filterTagPicker()` | 2644 | Lists the tags matching what is typed that the task does not already have, plus a create option for a new name when tag creation is allowed. |
| `tagPickerKey(e)` | 2668 | Enter picks the first option (an existing match, or the create row) |
| `addDetailTag(tagId)` | 2676 | Adds an existing tag to the open task and saves the tag list. |
| `removeDetailTag(tagId)` | 2688 | Removes a tag from the open task and saves the tag list. |
| `createAndAddTag()` | 2698 | Creates a new tag from the typed name with the next palette colour, adds it to the open task and refreshes the sidebar Tag filter. |
| `saveDetailTags()` | 2726 | Saves the open task's tag ids. |
| `toggleSubtask(id, completing)` | 2736 | Ticks or unticks a subtask; completing one first runs the checklist check. Reopens the panel afterwards and shows any refusal. |
| `renderRecurrenceSection(task)` | 2786 | Returns the Repeats section for a top-level task - the rule summary, its place in the series, a link back to the first task and the hidden editor. |
| `describeRecurrence(r)` | 2825 | One sentence describing a rule, built from the same parts the editor sets. |
| `T(k, p)` | 2829 | Shorthand for a tasks.recur translation. |
| `toggleRecurrenceEditor()` | 2867 | Shows or hides the Repeats editor, applying the frequency rows on opening. |
| `renderRecurrenceEditor(r)` | 2879 | The editor. Only the rows that apply to the chosen frequency are shown. |
| `T(k)` | 2881 | Shorthand for an escaped tasks.recur translation. |
| `sel(a, b)` | 2890 | Returns "selected" when the two values match. |
| `chk(x)` | 2892 | Returns "checked" when the value is truthy as a number. |
| `wdName(i)` | 2895 | Short weekday name for 1 (Monday) to 7 (Sunday). |
| `recurEditorRefresh()` | 2982 | Show only the rows the chosen frequency and ending actually use. |
| `show(sel, on)` | 2989 | Shows or hides every editor element matching a selector. |
| `recurrenceFormPayload()` | 3007 | The editor's current settings, as the API wants them. |
| `val(id)` | 3009 | The value of an editor field by id. |
| `on(id)` | 3011 | Whether an editor checkbox is ticked, by id. |
| `previewRecurrence()` | 3037 | Show the dates the current settings would produce, before committing to them (Ed's idea). "Every second Tuesday of the month, 5 times" is not something most people can turn into dates in their head, and without this the only way to find out what a repeat does is to save it and wait. |
| `showRecurrencePreview(d)` | 3053 | Opens the preview dialog listing the dates the repeat would produce, or explains that a repeat-on-completion has no dates to list. |
| `T(k, p)` | 3055 | Shorthand for a tasks.recur translation. |
| `recurPreviewEsc(e)` | 3110 | Closes the repeat preview on Escape. |
| `closeRecurrencePreview()` | 3113 | Removes the repeat preview dialog and its Escape listener. |
| `saveRecurrence()` | 3120 | Saves the Repeats editor's settings for the open task and reopens the panel. |
| `stopRecurrence()` | 3135 | Asks for confirmation, then switches the open task's repeat off and reopens the panel. |
| `subtaskRowHtml(s)` | 3178 | One subtask row in the task window: drag handle, tick box, priority, title, who has it, then Start and Due as columns that line up down the list. |
| `dateInput(field, value, label)` | 3188 | Returns a date box for a subtask's start or due date that saves on change without opening the subtask. |
| `subtaskHeadHtml()` | 3215 | The column headings over the two date columns. Same grid as a row, so the headings sit exactly over their dates. |
| `setSubtaskDate(subtaskId, field, input)` | 3224 | Saves a subtask's start or due date from its row and refreshes the board in the background. |
| `subtaskDragStart(e)` | 3238 | Starts dragging a subtask row, but only when the drag began on its handle. |
| `subtaskDragOver(e)` | 3248 | Moves the dragged subtask row to its new place as it passes over the list. |
| `subtaskDragEnd(e)` | 3265 | Ends a subtask drag and saves the new subtask order; on a refusal reopens the panel to show the stored order. |
| `appendSubtaskRow(sub)` | 3302 | Put one newly-created subtask into the list already on screen, without a refetch (which is the thing `addSubtask()` exists to avoid). |
| `addSubtask()` | 3331 | Add a subtask to the task the panel is showing. |
| `addComment()` | 3390 | Posts a comment on the open task and appends it to the comment list. |
| `searchLink(query, type)` | 3426 | Searches tickets or changes to link, 300ms after typing stops and from 2 characters, and lists the results to pick from. |
| `linkItem(field, id)` | 3459 | Link a ticket or a change to the open task. |
| `removeLink(field)` | 3488 | Clears the open task's linked ticket or change and reopens the panel. |
| `deleteCurrentTask()` | 3496 | Asks for confirmation, deletes the open task and closes the panel. |
| `loadProjectChoices()` | 3529 | The live projects the task window can file a task under. Only fetched for analysts who can open Projects (TASK_CAN_PROJECTS) - the endpoint refuses everyone else, and they see the project as plain text instead. |
| `projectFieldHtml(task)` | 3542 | Top-level tasks only: a subtask belongs wherever its parent does. Offers the live projects (plus the task's own, if that one has since finished), and links to the project's page. The server refuses a project from another company. |
| `loadContractorChoices()` | 3569 | Fetches the suppliers and their contacts once per page; false when this analyst may not choose a contractor. |
| `contractorFieldHtml(task)` | 3582 | A supplier doing the work, and optionally the person there. Choosing one needs Contracts (where suppliers live); without it the field only names the contractor. The assignee above stays the person here who chases it. |
| `setTaskContractor(taskId, supplierId, contactId)` | 3604 | Saves a task's contractor and contact, then reloads the board and reopens the task. |
| `setTaskProject(taskId, sel)` | 3624 | Files a task under a project (or none), then reloads the board and reopens the task; on a refusal puts the dropdown back. |
| `loadLookups()` | 3643 | Fetches the task statuses, priorities and tags, keeping only the active statuses and priorities. |
| `escAttr(value)` | 3657 | Escape a value for safe use inside an HTML attribute |
| `taskPreviewBadge(type, id)` | 3681 | The ⓘ preview badge (#91). Guarded, so a page that somehow loaded without record-preview.js loses the preview rather than the whole detail pane. |
| `linkedRecord(url, label)` | 3686 | Returns a link to another record when the server gave a URL, or plain escaped text when it did not. |
| `esc(text)` | 3693 | Escapes text for safe use in HTML. |
| `formatDateTime(dt)` | 3704 | Formats a stored UTC datetime in the analyst's display timezone, returning the raw value if it cannot be parsed. |

## API endpoints it calls

- [api/system/set_user_preference.php](/code/files/api/system/set_user_preference.php.md)
- [api/system/get_tenants.php](/code/files/api/system/get_tenants.php.md)
- [api/projects/list.php](/code/files/api/projects/list.php.md)
- [api/projects/task_assign.php](/code/files/api/projects/task_assign.php.md)

## Loaded by

- [tasks/index.php](/code/files/tasks/index.php.md)

