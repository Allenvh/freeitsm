# process_lanes

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `process_id` | `INT NOT NULL` |  |
| `label` | `VARCHAR(100) NULL DEFAULT ''` |  |
| `color` | `VARCHAR(20) NULL DEFAULT '#f5f7fa'` |  |
| `color2` | `VARCHAR(20) NULL` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `height` | `INT NOT NULL DEFAULT 180` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/process-mapper/save.php](/code/files/api/process-mapper/save.php.md):53 |  | delete |  |  |
| [api/process-mapper/save.php](/code/files/api/process-mapper/save.php.md):60 |  | insert | `process_id`, `label`, `color`, `color2`, `display_order`, `height` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/process-mapper/get.php](/code/files/api/process-mapper/get.php.md) | L53 |  |
| [includes/feature_bingo/cards/process-mapper.php](/code/files/includes/feature_bingo/cards/process-mapper.php.md) | L26 |  |

