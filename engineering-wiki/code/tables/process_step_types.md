# process_step_types

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `slug` | `VARCHAR(50) NOT NULL` |  |
| `shape` | `VARCHAR(30) NOT NULL DEFAULT 'rounded'` |  |
| `color` | `VARCHAR(20) NOT NULL DEFAULT '#0078d4'` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `is_builtin` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/process-mapper/delete_step_type.php](/code/files/api/process-mapper/delete_step_type.php.md):36 |  | delete |  |  |
| [api/process-mapper/reorder_step_types.php](/code/files/api/process-mapper/reorder_step_types.php.md):26 |  | update | `display_order` |  |
| [api/process-mapper/save_step_type.php](/code/files/api/process-mapper/save_step_type.php.md):42 |  | update | `name`, `shape`, `color`, `is_active` |  |
| [api/process-mapper/save_step_type.php](/code/files/api/process-mapper/save_step_type.php.md):58 |  | insert | `name`, `slug`, `shape`, `color`, `display_order`, `is_active`, `is_builtin` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3471 |  | insert | `name`, `slug`, `shape`, `color`, `display_order`, `is_active`, `is_builtin` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3933 |  | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/process-mapper/delete_step_type.php](/code/files/api/process-mapper/delete_step_type.php.md) | L30 |  |
| [api/process-mapper/get_step_types.php](/code/files/api/process-mapper/get_step_types.php.md) | L19 |  |
| [api/process-mapper/save_step_type.php](/code/files/api/process-mapper/save_step_type.php.md) | L50, L57 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3469 |  |
| [process-mapper/index.php](/code/files/process-mapper/index.php.md) | L32 |  |

