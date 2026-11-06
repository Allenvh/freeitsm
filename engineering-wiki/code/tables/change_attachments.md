# change_attachments

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `change_id` | `INT NOT NULL` |  |
| `file_name` | `VARCHAR(255) NOT NULL` |  |
| `file_path` | `VARCHAR(500) NOT NULL` |  |
| `file_size` | `INT NULL` |  |
| `file_type` | `VARCHAR(100) NULL` |  |
| `uploaded_by_id` | `INT NULL` |  |
| `uploaded_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/change-management/delete_attachment.php](/code/files/api/change-management/delete_attachment.php.md):47 |  | delete |  |  |
| [api/change-management/upload_attachment.php](/code/files/api/change-management/upload_attachment.php.md):71 |  | insert | `change_id`, `file_name`, `file_path`, `file_size`, `file_type`, `uploaded_by_id`, `uploaded_datetime` |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):395 | `ChangesService::deleteChange` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/change-management/delete_attachment.php](/code/files/api/change-management/delete_attachment.php.md) | L30 |  |
| [api/change-management/get.php](/code/files/api/change-management/get.php.md) | L68 |  |
| [api/change-management/get_attachment.php](/code/files/api/change-management/get_attachment.php.md) | L28 |  |
| [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) | apiChangesGet L302 |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md) | ChangesService::deleteChange L387 |  |

