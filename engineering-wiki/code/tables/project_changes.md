# project_changes

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `change_id` | `INT NOT NULL` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md) | projectGateChanges L56 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectUnapprovedChanges L503 |  |

