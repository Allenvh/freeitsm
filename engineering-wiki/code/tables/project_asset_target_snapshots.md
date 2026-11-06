# project_asset_target_snapshots

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `target_id` | `INT NOT NULL` |  |
| `snap_date` | `DATE NOT NULL` |  |
| `done` | `INT NOT NULL DEFAULT 0` |  |
| `total` | `INT NOT NULL DEFAULT 0` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/projects/targets.php](/code/files/includes/projects/targets.php.md):245 | `projectTargetsDetail` | insert | `target_id`, `snap_date`, `done`, `total` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):899 | `ProjectToolsService::saveTarget` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) | projectTargetsReady L35, projectTargetsDetail L247 |  |

