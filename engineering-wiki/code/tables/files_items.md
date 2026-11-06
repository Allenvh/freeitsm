# files_items

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `folder_id` | `INT NOT NULL` |  |
| `name` | `VARCHAR(255) NOT NULL` |  |
| `current_version_id` | `INT NULL` |  |
| `size_bytes` | `BIGINT NOT NULL DEFAULT 0` |  |
| `mime_type` | `VARCHAR(150) NULL` |  |
| `created_by` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_by` | `INT NULL` |  |
| `updated_datetime` | `DATETIME NULL` |  |
| `deleted_by` | `INT NULL` |  |
| `deleted_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/files/item.php](/code/files/api/files/item.php.md):23 |  | update | `name`, `updated_by`, `updated_datetime` |  |
| [api/files/item.php](/code/files/api/files/item.php.md):32 |  | update | `deleted_by`, `deleted_datetime` |  |
| [api/files/transfer.php](/code/files/api/files/transfer.php.md):79 | `filesTransferItem` | update | `folder_id`, `name`, `updated_by`, `updated_datetime` |  |
| [api/files/transfer.php](/code/files/api/files/transfer.php.md):100 | `filesCopyItemRow` | insert | `folder_id`, `name`, `size_bytes`, `mime_type`, `created_by`, `created_datetime` |  |
| [api/files/transfer.php](/code/files/api/files/transfer.php.md):108 | `filesCopyItemRow` | update | `current_version_id` |  |
| [api/files/upload.php](/code/files/api/files/upload.php.md):137 |  | insert | `folder_id`, `name`, `size_bytes`, `mime_type`, `created_by`, `created_datetime` |  |
| [api/files/upload.php](/code/files/api/files/upload.php.md):149 |  | update | `current_version_id`, `size_bytes`, `mime_type` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/files/list.php](/code/files/api/files/list.php.md) | L62 |  |
| [api/files/properties.php](/code/files/api/files/properties.php.md) | L25 |  |
| [api/files/search.php](/code/files/api/files/search.php.md) | L36 |  |
| [api/files/settings.php](/code/files/api/files/settings.php.md) | L66 |  |
| [api/files/transfer.php](/code/files/api/files/transfer.php.md) | filesCopyFolderTree L194 |  |
| [api/files/upload.php](/code/files/api/files/upload.php.md) | L41 |  |
| [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md) | filesItem L108, filesNameTaken L158 |  |

