# process_groups

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `process_id` | `INT NOT NULL` |  |
| `label` | `VARCHAR(100) NULL DEFAULT ''` |  |
| `color` | `VARCHAR(20) NULL DEFAULT '#e3f2fd'` |  |
| `color2` | `VARCHAR(20) NULL` |  |
| `x` | `INT NOT NULL DEFAULT 0` |  |
| `y` | `INT NOT NULL DEFAULT 0` |  |
| `width` | `INT NOT NULL DEFAULT 240` |  |
| `height` | `INT NOT NULL DEFAULT 160` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/process-mapper/save.php](/code/files/api/process-mapper/save.php.md):52 |  | delete |  |  |
| [api/process-mapper/save.php](/code/files/api/process-mapper/save.php.md):78 |  | insert | `process_id`, `label`, `color`, `color2`, `x`, `y`, `width`, `height` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/process-mapper/get.php](/code/files/api/process-mapper/get.php.md) | L46 |  |
| [includes/feature_bingo/cards/process-mapper.php](/code/files/includes/feature_bingo/cards/process-mapper.php.md) | L38 |  |

