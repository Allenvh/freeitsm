# assets/js/problem-management.js

> JavaScript - 712 lines

Problem Management — list / detail / editor SPA.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `pmPreviewBadge(type, id)` | 19 | The ⓘ preview badge (#91). Guarded, so that a page which somehow loaded without record-preview.js loses the preview rather than the whole table it would have been drawn into. |
| `pmEsc(s)` | 24 | Escapes a value for safe use in HTML. |
| `pmToast(msg, type)` | 28 | Shows a short message through the shared toaster, or a minimal fallback toast when toast.js is not loaded. |
| `pmLoadLookups()` | 65 | Loads the problem statuses, priorities and analysts used by the filters and the editor's drop-downs. |
| `pmLoadList()` | 79 | Loads the problem list for the current status filter and redraws the filters, the list and the count. |
| `pmRenderFilters(counts)` | 93 | Draws the status filter list in the sidebar, with a count per status and an All total. |
| `pmT(key, english, params)` | 108 | Translate, or the English written at the call site. |
| `pmRenderList(problems)` | 113 | Draws the problem cards - number, title, status, priority, assignee, incident count and the known-error flag. |
| `pmFilter(statusId)` | 137 | Sidebar actions must return to the list view — otherwise, when a problem is open (e.g. deep-linked via ?id=), they'd update the list while it's hidden behind the detail and appear to do nothing. |
| `pmOpenSearchModal()` | 147 | Opens the search panel just under the Search button and focuses the number field. |
| `pmCloseSearchModal()` | 165 | Closes the search panel. |
| `pmInitSearchDrag()` | 177 | Lets the search panel be dragged by its header. |
| `onmousedown(e)` | 181 | Starts dragging the search panel, ignoring presses on its buttons. |
| `mm(e)` | 188 | Moves the search panel with the mouse while dragging. |
| `mu()` | 190 | Ends the drag by removing the move and release listeners. |
| `pmPerformSearch()` | 197 | Searches problems by number or title (list.php?q= matches either) and shows the results. |
| `pmRenderSearchResults(results)` | 215 | Draws the search results in the search panel, each one opening that problem when clicked. |
| `pmSelectSearchResult(id)` | 236 | Closes the search panel and opens the chosen problem. |
| `pmClearSearch()` | 239 | Empties the search fields and the results. |
| `pmShowListView()` | 246 | Hides the detail view and shows the list, forgetting which problem was open. |
| `pmSetListUrl()` | 253 | Drop the ?problem_id= param when returning to the list (adds a history entry so Back/Forward step between the list and the problem you were viewing). |
| `pmBackToList()` | 257 | Returns to the list, reloads it and drops the problem from the URL. |
| `pmOpenDetail(id, fromHistory)` | 264 | Loads one problem and shows its detail view, recording the visit in the recent trail and pushing ?problem_id= unless restoring from history. |
| `pmRenderDetail(data)` | 282 | Draws the whole detail view - details, linked incidents and changes, projects, notes, history and documents. |
| `pmFillSelect(sel, items, selected, blank)` | 405 | Fills a drop-down with id/name items, selecting one and optionally adding a blank first option. |
| `pmOpenEditor(problem)` | 411 | Opens the editor modal, filled from the given problem or empty for a new one. |
| `pmEditCurrent()` | 430 | Opens the editor on the problem currently shown. |
| `pmCloseEditor()` | 432 | Closes the editor modal. |
| `pmSave()` | 435 | Saves the editor's problem through save.php and opens the saved problem (the new id for a new one). |
| `pmDelete()` | 462 | Asks, then deletes the open problem; linked incidents are kept and only lose the link. |
| `pmLinkIncident()` | 484 | Opens the link-incident modal with an empty search and loads the incidents that can be linked. |
| `pmLinkSearchDebounced()` | 492 | Reloads the linkable incidents 250ms after the last keystroke in the search box. |
| `pmLoadLinkable()` | 497 | Loads the open incidents that can be linked to this problem, filtered by the search text, as tick-box rows. |
| `pmToggleAllLinkable(checked)` | 517 | Ticks or unticks every incident in the link list. |
| `pmLinkSelected()` | 521 | Links each ticked incident to the open problem one request at a time, reports how many worked and failed, then reloads the problem. |
| `pmUnlinkIncident(ticketId)` | 546 | Asks, then removes an incident's link to the open problem; the incident itself is kept. |
| `pmLinkChange()` | 561 | Opens the link-change modal with an empty search and loads the changes that can be linked. |
| `pmLinkChangeSearchDebounced()` | 569 | Reloads the linkable changes 250ms after the last keystroke in the search box. |
| `pmLoadLinkableChanges()` | 574 | Loads the changes that can be linked to this problem, filtered by the search text, as tick-box rows. |
| `pmToggleAllLinkableChanges(checked)` | 594 | Ticks or unticks every change in the link list. |
| `pmLinkChangeSelected()` | 598 | Links each ticked change to the open problem one request at a time, reports how many worked and failed, then reloads the problem. |
| `pmUnlinkChange(changeId)` | 623 | Asks, then removes a change's link to the open problem; the change itself is kept. |
| `pmAddNote()` | 638 | Adds the typed note to the open problem and reloads it. |
| `pmAiRootCause()` | 652 | Asks the AI for a root cause and workaround drafted from the linked incidents and shows it with an "Open in editor" button. |
| `pmSuggest()` | 666 | Asks the AI to scan recent open incidents for recurring patterns and lists each suggested problem with a create button. |
| `pmCreateFromSuggestion(i)` | 687 | Creates a problem from an AI suggestion, links the incidents it named and opens it. |
| `pmApplyAiDraft()` | 703 | Opens the editor on the current problem with the AI's root cause and workaround filled in, for review before saving. |

## API endpoints it calls

- [api/tickets/get_analysts.php](/code/files/api/tickets/get_analysts.php.md)
- [api/problem-management/get_problem_statuses.php](/code/files/api/problem-management/get_problem_statuses.php.md)
- [api/problem-management/get_problem_priorities.php](/code/files/api/problem-management/get_problem_priorities.php.md)
- [api/problem-management/list.php](/code/files/api/problem-management/list.php.md)
- [api/problem-management/get.php](/code/files/api/problem-management/get.php.md)
- [api/problem-management/save.php](/code/files/api/problem-management/save.php.md)
- [api/problem-management/delete.php](/code/files/api/problem-management/delete.php.md)
- [api/problem-management/list_linkable_tickets.php](/code/files/api/problem-management/list_linkable_tickets.php.md)
- [api/problem-management/link_ticket.php](/code/files/api/problem-management/link_ticket.php.md)
- [api/problem-management/unlink_ticket.php](/code/files/api/problem-management/unlink_ticket.php.md)
- [api/problem-management/list_linkable_changes.php](/code/files/api/problem-management/list_linkable_changes.php.md)
- [api/problem-management/link_change.php](/code/files/api/problem-management/link_change.php.md)
- [api/problem-management/unlink_change.php](/code/files/api/problem-management/unlink_change.php.md)
- [api/problem-management/add_note.php](/code/files/api/problem-management/add_note.php.md)
- [api/problem-management/ai_root_cause.php](/code/files/api/problem-management/ai_root_cause.php.md)
- [api/problem-management/ai_suggest_problem.php](/code/files/api/problem-management/ai_suggest_problem.php.md)

