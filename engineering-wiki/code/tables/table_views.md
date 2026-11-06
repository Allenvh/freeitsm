# table_views

Saved table views (#96). `config` is the data-table engine's own state as JSON - columns, order, sort, filters. Opaque here on purpose: a column per setting would need a migration every time the engine gained one.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `table_key` | `VARCHAR(32) NOT NULL` |  |
| `name` | `VARCHAR(120) NOT NULL` |  |
| `description` | `VARCHAR(500) NULL` |  |
| `owner_id` | `INT NULL` |  |
| `visibility` | `VARCHAR(10) NOT NULL DEFAULT 'private'` |  |
| `team_id` | `INT NULL` |  |
| `config` | `MEDIUMTEXT NOT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL` |  |
| `last_used_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/table_views.php](/code/files/includes/table_views.php.md):198 | `tableViewSave` | update | `name`, `description`, `visibility`, `team_id`, `config`, `updated_datetime` |  |
| [includes/table_views.php](/code/files/includes/table_views.php.md):207 | `tableViewSave` | insert | `table_key`, `name`, `description`, `owner_id`, `visibility`, `team_id`, `config`, `created_datetime`, `updated_datetime` |  |
| [includes/table_views.php](/code/files/includes/table_views.php.md):223 | `tableViewDelete` | delete |  |  |
| [includes/table_views.php](/code/files/includes/table_views.php.md):243 | `tableViewTouch` | update | `last_used_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/table_views.php](/code/files/includes/table_views.php.md) | tableViewSelect L60, tableViewSave L191 |  |

