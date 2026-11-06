# ticket_time_entries

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ticket_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `notes` | `LONGTEXT NULL` |  |
| `time_spent_minutes` | `INT NOT NULL` |  |
| `entry_datetime` | `DATETIME NOT NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md):379 |  | delete |  |  |
| [api/tickets/empty_trash.php](/code/files/api/tickets/empty_trash.php.md):77 |  | delete |  |  |
| [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md):93 |  | delete |  |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md):982 | `TicketsService::createTimeEntry` | insert | `ticket_id`, `analyst_id`, `notes`, `time_spent_minutes`, `entry_datetime` |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md):1015 | `TicketsService::updateTimeEntry` | update | `notes`, `time_spent_minutes`, `entry_datetime` |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md):1051 | `TicketsService::deleteTimeEntry` | update | `is_active` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md) | L340 |  |
| [api/tickets/get_time_entries.php](/code/files/api/tickets/get_time_entries.php.md) | L47 |  |
| [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) | apiTicketTimeEntriesList L462, apiTicketTimeEntriesCreate L491 |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) | gapBulkRichness L395 |  |
| [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md) | writeupTicketBundle L274 |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) | TicketsService::updateTimeEntry L1006, TicketsService::deleteTimeEntry L1031, TicketsService::deleteTimeEntry L1038 |  |

