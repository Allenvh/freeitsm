# files_permissions

Grants only, no Deny. level 1 View .. 5 Full control (FilesAcl::LEVEL_*).

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `folder_id` | `INT NOT NULL` |  |
| `principal_type` | `VARCHAR(10) NOT NULL` |  |
| `principal_id` | `INT NOT NULL` |  |
| `level` | `TINYINT NOT NULL DEFAULT 1` |  |
| `granted_by` | `INT NULL` |  |
| `granted_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/files/folder.php](/code/files/api/files/folder.php.md):42 |  | insert | `folder_id`, `principal_type`, `principal_id`, `level`, `granted_by`, `granted_datetime` |  |
| [api/files/folder.php](/code/files/api/files/folder.php.md):111 |  | insert | `folder_id`, `principal_type`, `principal_id`, `level`, `granted_by`, `granted_datetime` |  |
| [api/files/permissions.php](/code/files/api/files/permissions.php.md):46 |  | insert | `folder_id`, `principal_type`, `principal_id`, `level`, `granted_by`, `granted_datetime` |  |
| [api/files/permissions.php](/code/files/api/files/permissions.php.md):81 |  | delete |  |  |
| [api/files/permissions.php](/code/files/api/files/permissions.php.md):82 |  | insert | `folder_id`, `principal_type`, `principal_id`, `level`, `granted_by`, `granted_datetime` |  |
| [api/files/transfer.php](/code/files/api/files/transfer.php.md):217 | `filesGrantCreatorFull` | insert | `folder_id`, `principal_type`, `principal_id`, `level`, `granted_by`, `granted_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/files/permissions.php](/code/files/api/files/permissions.php.md) | L74 |  |
| [api/files/settings.php](/code/files/api/files/settings.php.md) | L65 |  |
| [includes/files/acl.php](/code/files/includes/files/acl.php.md) | FilesAcl::levels L136, FilesAcl::entries L216 |  |

