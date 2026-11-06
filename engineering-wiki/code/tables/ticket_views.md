# ticket_views

Who has looked at a ticket (discussion #62, step 2). ONE ROW PER VIEWER PER DAY - opening a ticket twenty times is one row with view_count 20 - which is what uq_ticket_views_day (db_verify_indexes.php) makes true. Kept apart from ticket_audit because its actor can only be an analyst, and a manager reading in the portal is not one. See includes/ticket_views.php.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ticket_id` | `INT NOT NULL` |  |
| `viewer_type` | `VARCHAR(10) NOT NULL` | 'analyst' or 'user' (portal) |
| `viewer_id` | `INT NOT NULL` |  |
| `view_date` | `DATE NOT NULL` | UTC day |
| `first_viewed_datetime` | `DATETIME NOT NULL` |  |
| `last_viewed_datetime` | `DATETIME NOT NULL` |  |
| `view_count` | `INT NOT NULL DEFAULT 1` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/ticket_views.php](/code/files/includes/ticket_views.php.md):64 | `ticketViewRecord` | insert | `ticket_id`, `viewer_type`, `viewer_id`, `view_date`, `first_viewed_datetime`, `last_viewed_datetime`, `view_count` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/ticket_views.php](/code/files/includes/ticket_views.php.md) | ticketViewsReady L38, ticketViewsAsAuditRows L88, ticketViewsForRequester L136 |  |

