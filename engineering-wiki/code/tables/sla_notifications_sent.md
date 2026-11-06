# sla_notifications_sent

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ticket_id` | `INT NOT NULL` |  |
| `target_type` | `ENUM('response','resolution') NOT NULL` |  |
| `trigger_type` | `ENUM('warning','breach') NOT NULL` |  |
| `sent_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `recipients` | `TEXT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md):403 | `sla_mark_notification_sent` | insert | `ticket_id`, `target_type`, `trigger_type`, `recipients` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md) | L345 |  |
| [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) | sla_notification_already_sent L390 |  |

