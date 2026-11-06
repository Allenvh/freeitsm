# files_versions

A copied file SHARES its source's storage_path - a purge must check nothing else points at a .bin before deleting it.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `item_id` | `INT NOT NULL` |  |
| `version_no` | `INT NOT NULL DEFAULT 1` |  |
| `storage_path` | `VARCHAR(255) NOT NULL` |  |
| `size_bytes` | `BIGINT NOT NULL DEFAULT 0` |  |
| `mime_type` | `VARCHAR(150) NULL` |  |
| `sha256` | `CHAR(64) NULL` |  |
| `uploaded_by` | `INT NULL` |  |
| `uploaded_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/files/transfer.php](/code/files/api/files/transfer.php.md):105 | `filesCopyItemRow` | insert | `item_id`, `version_no`, `storage_path`, `size_bytes`, `mime_type`, `sha256`, `uploaded_by`, `uploaded_datetime` |  |
| [api/files/upload.php](/code/files/api/files/upload.php.md):145 |  | insert | `item_id`, `version_no`, `storage_path`, `size_bytes`, `mime_type`, `sha256`, `uploaded_by`, `uploaded_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/files/download.php](/code/files/api/files/download.php.md) | L35 |  |
| [api/files/list.php](/code/files/api/files/list.php.md) | L62 |  |
| [api/files/properties.php](/code/files/api/files/properties.php.md) | L46 |  |
| [api/files/settings.php](/code/files/api/files/settings.php.md) | L29 |  |
| [api/files/transfer.php](/code/files/api/files/transfer.php.md) | filesCopyItemRow L105 |  |
| [api/files/upload.php](/code/files/api/files/upload.php.md) | L143 |  |
| [api/files/view.php](/code/files/api/files/view.php.md) | L96, L143 |  |

