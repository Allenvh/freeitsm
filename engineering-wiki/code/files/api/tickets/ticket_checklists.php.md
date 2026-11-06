# api/tickets/ticket_checklists.php

> API endpoint - 358 lines

API: Ticket Checklists / SOP Adheres to FreeITSM standard naming (created_datetime, completed_datetime, completed_by_id, completed_by_name).

## The endpoint

| | |
|---|---|
| URL | `api/tickets/ticket_checklists.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET action`, `POST action`, `GET ticket_id`, `POST ticket_id`, `POST template_id`, `POST item_id`, `POST completed`, `POST is_completed`, `POST response_value`, `POST checklist_id` |
| Called from | [checklists/ticket_view.js](/code/files/checklists/ticket_view.js.md) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [checklistTicketGate()](/code/functions/checklistTicketGate.md) | 38 | 🔒 Refuse unless this analyst can reach the ticket. Found while building the task twin of this endpoint (discussion #138): NOTHING here checked company - or even Tickets module access - so any signed-in analyst could read, attach to, tick and remove checklists on another company's ticket by number. The step and checklist actions are addressed by a CHILD id, so they resolve it to its ticket first and gate on that; a child id with no gate of its own is the classic way scoped data leaks. "Not found", never "not yours". |
| [checklistTicketForItem()](/code/functions/checklistTicketForItem.md) | 50 | Returns the ticket a checklist item belongs to, or 0 when the item does not exist - so the caller can run the ticket access gate. |
| [checklistTicketForChecklist()](/code/functions/checklistTicketForChecklist.md) | 62 | Returns the ticket a checklist belongs to, or 0 when it does not exist - so the caller can run the ticket access gate. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 | `checklistTicketForItem` | read [ticket_checklist_items](/code/tables/ticket_checklist_items.md), read [ticket_checklists](/code/tables/ticket_checklists.md) |  |
| 64 | `checklistTicketForChecklist` | read [ticket_checklists](/code/tables/ticket_checklists.md) |  |
| 100 |  | read [ticket_checklists](/code/tables/ticket_checklists.md) |  |
| 117 |  | read [ticket_checklist_items](/code/tables/ticket_checklist_items.md) |  |
| 150 |  | read [tickets](/code/tables/tickets.md) |  |
| 155 |  | read [ticket_notes](/code/tables/ticket_notes.md) |  |
| 161 |  | read [checklist_templates](/code/tables/checklist_templates.md) |  |
| 226 |  | read [checklist_templates](/code/tables/checklist_templates.md) |  |
| 245 |  | read [checklist_templates](/code/tables/checklist_templates.md) |  |
| 251 |  | **insert** [ticket_checklists](/code/tables/ticket_checklists.md) (`ticket_id`, `template_id`, `title`, `closure_mode`, `created_by_id`, `created_datetime`) |  |
| 255 |  | read [checklist_template_items](/code/tables/checklist_template_items.md) |  |
| 259 |  | **insert** [ticket_checklist_items](/code/tables/ticket_checklist_items.md) (`ticket_checklist_id`, `title`, `suggested_role`, `is_mandatory`, `requires_input`, `input_placeholder`, `sort_order`) |  |
| 288 |  | **update** [ticket_checklist_items](/code/tables/ticket_checklist_items.md) (`is_completed`, `response_value`, `completed_by_id`, `completed_by_name`, `completed_datetime`) |  |
| 297 |  | **update** [ticket_checklist_items](/code/tables/ticket_checklist_items.md) (`is_completed`, `response_value`, `completed_by_id`, `completed_by_name`, `completed_datetime`) |  |
| 309 |  | read [ticket_checklist_items](/code/tables/ticket_checklist_items.md) |  |
| 337 |  | **delete** [ticket_checklist_items](/code/tables/ticket_checklist_items.md) |  |
| 339 |  | **delete** [ticket_checklists](/code/tables/ticket_checklists.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), [includes/services/checklists.php](/code/files/includes/services/checklists.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketChecklistEmptyClosureMode()](/code/functions/ticketChecklistEmptyClosureMode.md), [ticketTenantId()](/code/functions/ticketTenantId.md)

