# project_task_flow

Task flow (3.3.0) - includes/projects/flow.php.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `project_id` | `INT NOT NULL` |  |
| `day` | `DATE NOT NULL` |  |
| `status_id` | `INT NOT NULL` |  |
| `task_count` | `INT NOT NULL DEFAULT 0` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | ⚠️ No id: the PK (project_id, day, status_id) is in db_verify.php's `$primaryKeys`. |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/projects/flow.php](/code/files/includes/projects/flow.php.md):43 | `projectFlowSnapshot` | delete |  |  |
| [includes/projects/flow.php](/code/files/includes/projects/flow.php.md):44 | `projectFlowSnapshot` | insert | `project_id`, `day`, `status_id`, `task_count` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/flow.php](/code/files/includes/projects/flow.php.md) | projectFlowReady L22, projectFlow L66 |  |

