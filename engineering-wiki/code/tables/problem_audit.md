# problem_audit

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `problem_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `action_type` | `VARCHAR(20) NOT NULL` |  |
| `field_name` | `VARCHAR(100) NULL` |  |
| `old_value` | `VARCHAR(1000) NULL` |  |
| `new_value` | `VARCHAR(1000) NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/problems.php](/code/files/includes/services/problems.php.md):488 | `ProblemsService::auditWrite` | insert | `problem_id`, `analyst_id`, `action_type`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/problem-management/get.php](/code/files/api/problem-management/get.php.md) | L64 |  |
| [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) | apiProblemAuditList L352 |  |

