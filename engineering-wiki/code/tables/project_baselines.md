# project_baselines

Change control (3.3.0) - includes/projects/control.php.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `number` | `INT NOT NULL` |  |
| `label` | `VARCHAR(150) NULL` |  |
| `reason` | `VARCHAR(12) NOT NULL DEFAULT 'manual'` |  |
| `stage_id` | `INT NULL` |  |
| `change_request_id` | `INT NULL` |  |
| `start_date` | `DATE NULL` |  |
| `target_end_date` | `DATE NULL` |  |
| `budget_planned` | `DECIMAL(14,2) NULL` |  |
| `currency` | `CHAR(3) NULL` |  |
| `task_count` | `INT NOT NULL DEFAULT 0` |  |
| `estimate_hours` | `DECIMAL(9,2) NULL` |  |
| `must_count` | `INT NOT NULL DEFAULT 0` |  |
| `snapshot` | `MEDIUMTEXT NULL` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/demo_data.php](/code/files/includes/demo_data.php.md):45 | `demoAfterImport` | update | `snapshot`, `start_date`, `target_end_date`, `budget_planned`, `task_count`, `estimate_hours`, `must_count` |  |
| [includes/projects/control.php](/code/files/includes/projects/control.php.md):124 | `projectTakeBaseline` | insert | `project_id`, `number`, `label`, `reason`, `stage_id`, `change_request_id`, `start_date`, `target_end_date`, `budget_planned`, `currency` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/demo_data.php](/code/files/includes/demo_data.php.md) | demoAfterImport L40 |  |
| [includes/projects/control.php](/code/files/includes/projects/control.php.md) | projectControlReady L43, projectTakeBaseline L123, projectBaselineAuto L270, projectBaselineAuto L274, projectBaselineAuto L280, projectControlDetail L315 ... |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::takeBaseline L1530 |  |

