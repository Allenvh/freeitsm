# sla_notification_rules

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `department_id` | `INT NULL` |  |
| `trigger_type` | `ENUM('warning','breach') NOT NULL` |  |
| `target_type` | `ENUM('response','resolution','both') NOT NULL DEFAULT 'both'` |  |
| `notify_assignee` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `notify_department_teams` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `notify_analyst_id` | `INT NULL` |  |
| `notify_emails` | `TEXT NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/delete_sla_notification_rule.php](/code/files/api/tickets/delete_sla_notification_rule.php.md):26 |  | delete |  |  |
| [api/tickets/save_sla_notification_rule.php](/code/files/api/tickets/save_sla_notification_rule.php.md):101 |  | update | `department_id`, `trigger_type`, `target_type`, `notify_assignee`, `notify_department_teams`, `notify_analyst_id`, `notify_emails`, `is_active` |  |
| [api/tickets/save_sla_notification_rule.php](/code/files/api/tickets/save_sla_notification_rule.php.md):113 |  | insert | `department_id`, `trigger_type`, `target_type`, `notify_assignee`, `notify_department_teams`, `notify_analyst_id`, `notify_emails`, `is_active` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/get_sla_notification_rules.php](/code/files/api/tickets/get_sla_notification_rules.php.md) | L24 |  |
| [api/tickets/save_sla_notification_rule.php](/code/files/api/tickets/save_sla_notification_rule.php.md) | L80 |  |
| [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) | sla_find_matching_rule L220 |  |

