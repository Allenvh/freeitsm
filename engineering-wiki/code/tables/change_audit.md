# change_audit

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `change_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `action_type` | `VARCHAR(50) NOT NULL` |  |
| `field_name` | `VARCHAR(100) NULL` |  |
| `old_value` | `VARCHAR(1000) NULL` |  |
| `new_value` | `VARCHAR(1000) NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/change-management/move_to_company.php](/code/files/api/change-management/move_to_company.php.md):68 |  | insert | `change_id`, `analyst_id`, `action_type`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):792 | `ChangesService::auditWrite` | insert | `change_id`, `analyst_id`, `action_type`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/change-management/get_audit.php](/code/files/api/change-management/get_audit.php.md) | L33 |  |
| [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) | apiChangeAuditList L426 |  |

