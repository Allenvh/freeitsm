# processes

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `description` | `TEXT NULL` |  |
| `created_by` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/process-mapper/delete.php](/code/files/api/process-mapper/delete.php.md):30 |  | delete |  |  |
| [api/process-mapper/save.php](/code/files/api/process-mapper/save.php.md):39 |  | update | `title`, `updated_datetime` |  |
| [api/process-mapper/save.php](/code/files/api/process-mapper/save.php.md):43 |  | insert | `title`, `created_by`, `created_datetime`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/process-mapper/get.php](/code/files/api/process-mapper/get.php.md) | L25 |  |
| [api/process-mapper/list.php](/code/files/api/process-mapper/list.php.md) | L18 |  |
| [includes/feature_bingo/cards/process-mapper.php](/code/files/includes/feature_bingo/cards/process-mapper.php.md) | L26, L38, L74 |  |

