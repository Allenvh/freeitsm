# files_folders

Files: secure file and folder sharing (files/) PESSIMISTIC: nothing is visible until files_permissions grants it. Bytes live on disk as <storage root>/yyyy/mm/dd/<random>.bin. See includes/files/.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `parent_id` | `INT NULL` |  |
| `name` | `VARCHAR(255) NOT NULL` |  |
| `inherit_permissions` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `watermark` | `TINYINT(1) NULL` | Viewer watermark: NULL = as the parent folder, 0 off, 1 on. |
| `created_by` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_by` | `INT NULL` |  |
| `updated_datetime` | `DATETIME NULL` |  |
| `deleted_by` | `INT NULL` |  |
| `deleted_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/files/folder.php](/code/files/api/files/folder.php.md):38 |  | insert | `parent_id`, `name`, `inherit_permissions`, `created_by`, `created_datetime` |  |
| [api/files/folder.php](/code/files/api/files/folder.php.md):64 |  | update | `name`, `updated_by`, `updated_datetime` |  |
| [api/files/folder.php](/code/files/api/files/folder.php.md):74 |  | update | `deleted_by`, `deleted_datetime` |  |
| [api/files/folder.php](/code/files/api/files/folder.php.md):86 |  | update | `watermark`, `updated_by`, `updated_datetime` |  |
| [api/files/folder.php](/code/files/api/files/folder.php.md):120 |  | update | `inherit_permissions`, `updated_by`, `updated_datetime` |  |
| [api/files/transfer.php](/code/files/api/files/transfer.php.md):147 | `filesTransferFolder` | update | `parent_id`, `name`, `updated_by`, `updated_datetime` |  |
| [api/files/transfer.php](/code/files/api/files/transfer.php.md):186 | `filesCopyFolderTree` | insert | `parent_id`, `name`, `inherit_permissions`, `created_by`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/files/list.php](/code/files/api/files/list.php.md) | L38 |  |
| [api/files/properties.php](/code/files/api/files/properties.php.md) | L16 |  |
| [includes/files/acl.php](/code/files/includes/files/acl.php.md) | FilesAcl::tree L82 |  |
| [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md) | filesNameTaken L150 |  |

