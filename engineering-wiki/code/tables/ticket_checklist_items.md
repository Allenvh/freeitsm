# ticket_checklist_items

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ticket_checklist_id` | `INT NOT NULL` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `suggested_role` | `VARCHAR(100) DEFAULT NULL` |  |
| `is_mandatory` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `is_completed` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `completed_by_id` | `INT DEFAULT NULL` |  |
| `completed_by_name` | `VARCHAR(150) DEFAULT NULL` |  |
| `completed_datetime` | `DATETIME DEFAULT NULL` |  |
| `requires_input` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `input_placeholder` | `VARCHAR(255) DEFAULT NULL` |  |
| `response_value` | `TEXT DEFAULT NULL` |  |
| `sort_order` | `INT NOT NULL DEFAULT 1` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md):98 |  | delete |  |  |
| [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md):259 |  | insert | `ticket_checklist_id`, `title`, `suggested_role`, `is_mandatory`, `requires_input`, `input_placeholder`, `sort_order` |  |
| [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md):288 |  | update | `is_completed`, `response_value`, `completed_by_id`, `completed_by_name`, `completed_datetime` |  |
| [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md):297 |  | update | `is_completed`, `response_value`, `completed_by_id`, `completed_by_name`, `completed_datetime` |  |
| [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md):337 |  | delete |  |  |
| [includes/services/checklists.php](/code/files/includes/services/checklists.php.md):373 | `ChecklistsService::saveLookup` | update | `suggested_role` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):2217 | `WorkflowEngine::action_attach_checklist` | insert | `ticket_checklist_id`, `title`, `suggested_role`, `is_mandatory`, `requires_input`, `input_placeholder`, `sort_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md) | checklistTicketForItem L52, L117, L309 |  |
| [includes/services/checklists.php](/code/files/includes/services/checklists.php.md) | ChecklistsService::outstandingMandatorySteps L80 |  |

