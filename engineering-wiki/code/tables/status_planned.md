# status_planned

Planned maintenance (3.2.0) - not an incident until it starts; see includes/service_status_planned.php and database/freeitsm.sql.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `comment` | `TEXT NULL` |  |
| `planned_start_datetime` | `DATETIME NOT NULL` |  |
| `planned_end_datetime` | `DATETIME NULL` |  |
| `state` | `VARCHAR(12) NOT NULL DEFAULT 'scheduled'` |  |
| `incident_id` | `INT NULL` |  |
| `project_id` | `INT NULL` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):284 | `ProjectsService::deleteProject` | update | `project_id` |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md):104 | `statusPlannedSave` | update | `title`, `comment`, `planned_start_datetime`, `planned_end_datetime`, `updated_datetime` |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md):108 | `statusPlannedSave` | insert | `title`, `comment`, `planned_start_datetime`, `planned_end_datetime`, `state`, `project_id`, `created_by_id`, `created_datetime`, `updated_datetime` |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md):134 | `statusPlannedCancel` | update | `state`, `updated_datetime` |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md):228 | `statusPlannedRun` | update | `state`, `updated_datetime` |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md):236 | `statusPlannedRun` | update | `state` |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md):243 | `statusPlannedRun` | update | `state`, `updated_datetime` |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md):250 | `statusPlannedRun` | update | `state` |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md):312 | `statusPlannedStart` | update | `incident_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md) | statusPlannedReady L55, statusPlannedLoad L144, statusPlannedList L165, statusPlannedDue L203, statusPlannedRun L225, statusPlannedRun L241 |  |

