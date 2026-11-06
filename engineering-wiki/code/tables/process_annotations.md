# process_annotations

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `process_id` | `INT NOT NULL` |  |
| `text` | `TEXT NULL` |  |
| `x` | `INT NOT NULL DEFAULT 0` |  |
| `y` | `INT NOT NULL DEFAULT 0` |  |
| `width` | `INT NOT NULL DEFAULT 180` |  |
| `height` | `INT NOT NULL DEFAULT 100` |  |
| `color` | `VARCHAR(20) NULL DEFAULT '#fff59d'` |  |
| `color2` | `VARCHAR(20) NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/process-mapper/save.php](/code/files/api/process-mapper/save.php.md):56 |  | delete |  |  |
| [api/process-mapper/save.php](/code/files/api/process-mapper/save.php.md):144 |  | insert | `process_id`, `text`, `x`, `y`, `width`, `height`, `color`, `color2` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/process-mapper/get.php](/code/files/api/process-mapper/get.php.md) | L62 |  |
| [includes/feature_bingo/cards/process-mapper.php](/code/files/includes/feature_bingo/cards/process-mapper.php.md) | L74 |  |

