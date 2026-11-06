# status_planned_services

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `planned_id` | `INT NOT NULL` |  |
| `service_id` | `INT NOT NULL` |  |
| `impact_level_id` | `INT NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md):106 | `statusPlannedSave` | delete |  |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md):113 | `statusPlannedSave` | insert | `planned_id`, `service_id`, `impact_level_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md) | statusPlannedReady L55, statusPlannedList L176, statusPlannedStart L296 |  |

