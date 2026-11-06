# process_steps

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `process_id` | `INT NOT NULL` |  |
| `type` | `VARCHAR(50) NOT NULL DEFAULT 'process'` |  |
| `label` | `VARCHAR(255) NOT NULL DEFAULT ''` |  |
| `description` | `TEXT NULL` |  |
| `url` | `VARCHAR(500) NULL` |  |
| `x` | `INT NOT NULL DEFAULT 0` |  |
| `y` | `INT NOT NULL DEFAULT 0` |  |
| `width` | `INT NOT NULL DEFAULT 160` |  |
| `height` | `INT NOT NULL DEFAULT 80` |  |
| `color` | `VARCHAR(20) NULL DEFAULT '#0078d4'` |  |
| `color2` | `VARCHAR(20) NULL` |  |
| `lane_id` | `INT NULL` |  |
| `group_id` | `INT NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/process-mapper/delete.php](/code/files/api/process-mapper/delete.php.md):29 |  | delete |  |  |
| [api/process-mapper/save.php](/code/files/api/process-mapper/save.php.md):51 |  | delete |  |  |
| [api/process-mapper/save.php](/code/files/api/process-mapper/save.php.md):99 |  | insert | `process_id`, `type`, `label`, `description`, `url`, `x`, `y`, `width`, `height`, `color` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/process-mapper/get.php](/code/files/api/process-mapper/get.php.md) | L34 |  |

