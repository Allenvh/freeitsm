# files_shortcuts

A shortcut lives in a folder (folder_id) OR on one person's desktop (analyst_id). It grants nothing - the target's permissions decide.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `folder_id` | `INT NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `target_type` | `VARCHAR(10) NOT NULL` |  |
| `target_id` | `INT NOT NULL` |  |
| `name` | `VARCHAR(255) NOT NULL` |  |
| `created_by` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/files/shortcut.php](/code/files/api/files/shortcut.php.md):104 |  | insert | `folder_id`, `analyst_id`, `target_type`, `target_id`, `name`, `created_by`, `created_datetime` |  |
| [api/files/shortcut.php](/code/files/api/files/shortcut.php.md):119 |  | insert | `folder_id`, `analyst_id`, `target_type`, `target_id`, `name`, `created_by`, `created_datetime` |  |
| [api/files/shortcut.php](/code/files/api/files/shortcut.php.md):159 |  | insert | `folder_id`, `analyst_id`, `target_type`, `target_id`, `name`, `created_by`, `created_datetime` |  |
| [api/files/shortcut.php](/code/files/api/files/shortcut.php.md):163 |  | update | `folder_id`, `analyst_id` |  |
| [api/files/shortcut.php](/code/files/api/files/shortcut.php.md):175 |  | insert | `folder_id`, `analyst_id`, `target_type`, `target_id`, `name`, `created_by`, `created_datetime` |  |
| [api/files/shortcut.php](/code/files/api/files/shortcut.php.md):179 |  | update | `folder_id`, `analyst_id`, `name` |  |
| [api/files/shortcut.php](/code/files/api/files/shortcut.php.md):194 |  | update | `name` |  |
| [api/files/shortcut.php](/code/files/api/files/shortcut.php.md):200 |  | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/files/list.php](/code/files/api/files/list.php.md) | L89 |  |
| [api/files/shortcut.php](/code/files/api/files/shortcut.php.md) | L68, L128 |  |
| [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md) | filesNameTaken L164 |  |

