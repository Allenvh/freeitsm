# ticket_cmdb_objects

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ticket_id` | `INT NOT NULL` |  |
| `cmdb_object_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `created_by_analyst_id` | `INT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/delete_ticket_cmdb_object.php](/code/files/api/tickets/delete_ticket_cmdb_object.php.md):36 |  | delete |  |  |
| [api/tickets/delete_ticket_cmdb_object.php](/code/files/api/tickets/delete_ticket_cmdb_object.php.md):42 |  | delete |  |  |
| [api/tickets/save_ticket_cmdb_object.php](/code/files/api/tickets/save_ticket_cmdb_object.php.md):70 |  | insert | `ticket_id`, `cmdb_object_id`, `created_datetime`, `created_by_analyst_id` |  |
| [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md):557 | `apiCmdbObjectTicketsLink` | insert | `ticket_id`, `cmdb_object_id`, `created_datetime`, `created_by_analyst_id` |  |
| [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md):579 | `apiCmdbObjectTicketsUnlink` | delete |  |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md):312 | `CmdbService::deleteObject` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/cmdb/generate_object_summary.php](/code/files/api/cmdb/generate_object_summary.php.md) | L183, L196 |  |
| [api/cmdb/get_object_tickets.php](/code/files/api/cmdb/get_object_tickets.php.md) | L45, L71 |  |
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md) | L343 |  |
| [api/tickets/delete_ticket_cmdb_object.php](/code/files/api/tickets/delete_ticket_cmdb_object.php.md) | L30 |  |
| [api/tickets/get_ticket_cmdb_objects.php](/code/files/api/tickets/get_ticket_cmdb_objects.php.md) | L39 |  |
| [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) | apiCmdbObjectTicketsList L518 |  |

