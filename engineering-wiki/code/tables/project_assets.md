# project_assets

Projects joined to the rest of FreeITSM (3.2.0) - one join table per kind.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `asset_id` | `INT NOT NULL` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) | projectTargetSql L125 |  |

