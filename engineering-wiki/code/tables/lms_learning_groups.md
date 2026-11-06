# lms_learning_groups

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(500) NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/lms/group.php](/code/files/api/lms/group.php.md):35 |  | update | `name`, `description`, `updated_datetime` |  |
| [api/lms/group.php](/code/files/api/lms/group.php.md):57 |  | update | `is_active`, `updated_datetime` |  |
| [api/lms/groups.php](/code/files/api/lms/groups.php.md):54 |  | insert | `name`, `description`, `created_by_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/lms/assignment_targets.php](/code/files/api/lms/assignment_targets.php.md) | L81 |  |
| [api/lms/assignments.php](/code/files/api/lms/assignments.php.md) | L46 |  |
| [api/lms/groups.php](/code/files/api/lms/groups.php.md) | L19 |  |
| [api/lms/progress.php](/code/files/api/lms/progress.php.md) | L111 |  |
| [includes/lms_access.php](/code/files/includes/lms_access.php.md) | lmsAssignmentReachSql L235, lmsAssignedLearnersSql L514, lmsAssignmentTargetProblem L589 |  |

