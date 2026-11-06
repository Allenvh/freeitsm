# ticket_audit

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ticket_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NULL` | 🔴 NULLABLE. A workflow writes history entries that no analyst made, and #1391 made freeitsm.sql and the live migration agree on that while leaving THIS copy behind — so every verification run since has reported the two files disagreeing (GH #123, and Ed saw it on his own install). |
| `field_name` | `VARCHAR(100) NOT NULL` |  |
| `old_value` | `VARCHAR(500) NULL` |  |
| `new_value` | `VARCHAR(500) NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/self-service/close_ticket.php](/code/files/api/self-service/close_ticket.php.md):134 |  | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [api/system/assign_ticket_department.php](/code/files/api/system/assign_ticket_department.php.md):64 |  | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md):378 |  | delete |  |  |
| [api/tickets/empty_trash.php](/code/files/api/tickets/empty_trash.php.md):76 |  | delete |  |  |
| [api/tickets/log_ticket_audit.php](/code/files/api/tickets/log_ticket_audit.php.md):45 |  | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [api/tickets/move_ticket_to_company.php](/code/files/api/tickets/move_ticket_to_company.php.md):68 |  | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md):90 |  | delete |  |  |
| [api/tickets/save_ticket_subject.php](/code/files/api/tickets/save_ticket_subject.php.md):59 |  | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md):261 | `calendarPullAudit` | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md):333 | `catalogueCreateTicketFromSubmission` | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):778 | `messagingTelegramAudit` | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [includes/services/checklists.php](/code/files/includes/services/checklists.php.md):290 | `ChecklistsService::writeClosureNote` | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md):249 | `MandatoryFieldsService::writeNote` | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md):1161 | `TicketsService::auditWrite` | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [includes/ticket_links.php](/code/files/includes/ticket_links.php.md):55 | `ticketLinkAudit` | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):653 | `mergeAudit` | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md):94 | `ticketSensitivityRaise` | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md):322 | `snoozeAudit` | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [includes/ticket_split.php](/code/files/includes/ticket_split.php.md):348 | `splitAudit` | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):2392 | `WorkflowEngine::action_add_ticket_note` | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):2786 | `WorkflowEngine::action_create_ticket` | insert | `ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md) | L339 |  |
| [api/tickets/get_ticket_audit.php](/code/files/api/tickets/get_ticket_audit.php.md) | L36 |  |
| [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) | apiTicketAuditList L403 |  |
| [includes/sla.php](/code/files/includes/sla.php.md) | sla_get_state L118 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L249 |  |

