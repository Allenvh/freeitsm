# ticket_checklists

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ticket_id` | `INT NOT NULL` |  |
| `template_id` | `INT DEFAULT NULL` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `closure_mode` | `ENUM('warn','block') NOT NULL DEFAULT 'warn'` |  |
| `created_by_id` | `INT DEFAULT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md):101 |  | delete |  |  |
| [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md):251 |  | insert | `ticket_id`, `template_id`, `title`, `closure_mode`, `created_by_id`, `created_datetime` |  |
| [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md):339 |  | delete |  |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):2208 | `WorkflowEngine::action_attach_checklist` | insert | `ticket_id`, `template_id`, `title`, `closure_mode`, `created_by_id`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md) | L98 |  |
| [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md) | checklistTicketForItem L52, checklistTicketForChecklist L64, L100 |  |
| [includes/services/checklists.php](/code/files/includes/services/checklists.php.md) | ChecklistsService::hasAttachedChecklists L59, ChecklistsService::outstandingMandatorySteps L80 |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) | WorkflowEngine::action_attach_checklist L2194 |  |

