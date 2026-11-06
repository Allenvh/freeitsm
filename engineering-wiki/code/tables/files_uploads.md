# files_uploads

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `token` | `CHAR(32) NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `folder_id` | `INT NOT NULL` |  |
| `item_id` | `INT NULL` |  |
| `file_name` | `VARCHAR(255) NOT NULL` |  |
| `size_bytes` | `BIGINT NOT NULL DEFAULT 0` |  |
| `received_bytes` | `BIGINT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/files/upload.php](/code/files/api/files/upload.php.md):62 |  | delete |  |  |
| [api/files/upload.php](/code/files/api/files/upload.php.md):71 |  | insert | `token`, `analyst_id`, `folder_id`, `item_id`, `file_name`, `size_bytes`, `created_datetime` |  |
| [api/files/upload.php](/code/files/api/files/upload.php.md):101 |  | update | `received_bytes`, `updated_datetime` |  |
| [api/files/upload.php](/code/files/api/files/upload.php.md):108 |  | delete |  |  |
| [api/files/upload.php](/code/files/api/files/upload.php.md):152 |  | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/files/upload.php](/code/files/api/files/upload.php.md) | L59, L79 |  |

