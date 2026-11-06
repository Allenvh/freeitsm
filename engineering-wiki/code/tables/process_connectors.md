# process_connectors

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `process_id` | `INT NOT NULL` |  |
| `from_step_id` | `INT NOT NULL` |  |
| `to_step_id` | `INT NOT NULL` |  |
| `label` | `VARCHAR(255) NULL DEFAULT ''` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/process-mapper/delete.php](/code/files/api/process-mapper/delete.php.md):28 |  | delete |  |  |
| [api/process-mapper/save.php](/code/files/api/process-mapper/save.php.md):50 |  | delete |  |  |
| [api/process-mapper/save.php](/code/files/api/process-mapper/save.php.md):130 |  | insert | `process_id`, `from_step_id`, `to_step_id`, `label` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/process-mapper/get.php](/code/files/api/process-mapper/get.php.md) | L38 |  |

