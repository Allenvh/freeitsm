# department_teams

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `department_id` | `INT NOT NULL` |  |
| `team_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/debug-tools/D001_demo_core_import.php](/code/files/api/system/debug-tools/D001_demo_core_import.php.md):397 |  | insert | `department_id`, `team_id` |  |
| [api/tickets/save_department_teams.php](/code/files/api/tickets/save_department_teams.php.md):38 |  | delete |  |  |
| [api/tickets/save_department_teams.php](/code/files/api/tickets/save_department_teams.php.md):44 |  | insert | `department_id`, `team_id` |  |
| [api/tickets/save_team_departments.php](/code/files/api/tickets/save_team_departments.php.md):44 |  | delete |  |  |
| [api/tickets/save_team_departments.php](/code/files/api/tickets/save_team_departments.php.md):50 |  | insert | `department_id`, `team_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md) | L241, L329, L388, L398, L412, L422 ... |  |
| [api/tickets/get_department_teams.php](/code/files/api/tickets/get_department_teams.php.md) | L28 |  |
| [api/tickets/get_my_departments.php](/code/files/api/tickets/get_my_departments.php.md) | L44 |  |
| [api/tickets/get_team_departments.php](/code/files/api/tickets/get_team_departments.php.md) | L28 |  |
| [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md) | L72 |  |
| [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) | sla_resolve_recipients L257 |  |

