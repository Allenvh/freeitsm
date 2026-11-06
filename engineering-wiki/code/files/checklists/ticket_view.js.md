# checklists/ticket_view.js

> JavaScript - 899 lines

Ticket Checklists / SOP Module for FreeITSM

🔴 API PATHS ARE RELATIVE TO THE HOST PAGE, NEVER ROOT-ABSOLUTE.

These calls used to be fetch('/api/tickets/ticket_checklists.php'), which
resolves only when FreeITSM is installed AT the document root. On an install
in a subdirectory every one of them 404s and the whole SOP panel silently
does nothing — measured: /api/... returned 404 and /freeitsm-app/api/...
returned 200 on the same machine.

tickets/index.php already publishes window.API_BASE ('../api/tickets/'), which
is derived from BASE_URL and therefore correct in both layouts. The fallback
matches inbox.js's own, for a page that forgets to set it.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `chkT(key, fallback)` | 32 | Translate, the way assets/js/calendar.js does it. |
| `closureLockIcon(effectiveMode, px)` | 44 | The padlock marking a checklist that blocks closure until it is complete. |
| `formatStepDatetime(dtStr)` | 59 | Format exact server datetime as dd-mmm-yy HH:MM |
| `loadTicketChecklists(ticketId)` | 82 | 1. Fetch & Render Checklists for a Ticket |
| `findChecklistItem(itemId)` | 103 | Finds a step by id across the ticket's attached checklists and returns {checklist, item}, or null. |
| `getIncompleteMandatorySteps()` | 117 | Lists the mandatory steps not yet completed, with each checklist's closure mode - read by inbox.js before a ticket is closed. |
| `renderChecklistToolbarButton(ticketId)` | 136 | 2. Render Action Toolbar Button |
| `renderTicketChecklistsInline(ticketId)` | 167 | 3. Render Inline SOP Card |
| `openChecklistModal(ticketId)` | 267 | 4. Full Pop-out Checklist Modal |
| `closeChecklistModal()` | 371 | Hides the ticket checklist pop-out. |
| `handleStepCheckboxChange(ticketId, itemId, checkboxElem, reopenPopout)` | 377 | 5. Handle Checkbox Click with Data Capture Prompt |
| `promptDataCapture(ticketId, item, checklistTitle, reopenPopout)` | 397 | Prompt for note or data input (Rejects blank entries!) |
| `doSave()` | 441 | Refuses a blank value; otherwise closes the prompt and completes the step with the value recorded. |
| `closeDataCaptureModal()` | 462 | Hides the step data-capture prompt. |
| `executeToggleItem(ticketId, itemId, completed, responseValue, checklistTitle, stepTitle, reopenPopout)` | 468 | 6. Execute Toggle Item in Backend |
| `logStepNote(ticketId, checklistTitle, stepTitle, completed, responseValue)` | 498 | Adds an internal ticket note saying a step was completed (with any recorded value) or reopened, then reloads the notes. |
| `removeTicketChecklist(ticketId, checklistId)` | 528 | 7. Remove Checklist from Ticket |
| `openAttachChecklistModal(ticketId)` | 573 | 8. Attach checklist Modal |
| `categoryPillClass(name)` | 625 | A stable colour per category, derived from its name. |
| `filterTemplatesList(ticketId)` | 633 | Lists the templates matching the attach dialogue's search, best match first, each with an Attach button. |
| `attachSopToTicket(ticketId, templateId)` | 684 | Attaches a checklist template to the ticket through ticket_checklists.php, then reloads and opens the ticket's checklists. |
| `closeAttachModal()` | 709 | Hides the attach-checklist dialogue. |
| `escapeHtml(str)` | 715 | Escapes text for safe use in HTML and attributes. |
| `getAttachedChecklistsCount()` | 725 | Returns how many checklists are attached to the open ticket - read by inbox.js at close. |
| `getTicketChecklistEmptyClosureMode()` | 733 | Returns what closing a ticket with no checklist does ('off' unless the page set window.ticketChecklistEmptyClosureMode). |
| `styleSopResponsesInNotes()` | 750 | SOP Response Styling in Notes Timeline (Italics & Distinct Color) |
| `checkAndShowSopSuggestion(ticketId)` | 788 | Asks ticket_checklists.php for templates that suit this ticket and shows them, with confidence and Attach buttons, in the empty-checklist box (unless dismissed). |
| `dismissSopSuggestion(ticketId)` | 882 | Hides the suggestions for this ticket for the rest of the page visit and puts back the plain "none attached" box. |

## API endpoints it calls

- [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md)

## Loaded by

- [tickets/index.php](/code/files/tickets/index.php.md)

