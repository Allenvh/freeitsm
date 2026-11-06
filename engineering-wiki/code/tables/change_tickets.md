# change_tickets

Incidents (tickets) linked to a change — twin of problem_tickets.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `change_id` | `INT NOT NULL` |  |
| `ticket_id` | `INT NOT NULL` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):675 | `ChangesService::linkTicket` | insert | `change_id`, `ticket_id`, `created_by_id`, `created_datetime` |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):696 | `ChangesService::unlinkTicket` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/change-management/get.php](/code/files/api/change-management/get.php.md) | L91 |  |
| [api/change-management/list_linkable_tickets.php](/code/files/api/change-management/list_linkable_tickets.php.md) | L41 |  |
| [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md) | L220 |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md) | ChangesService::linkTicket L669 |  |

