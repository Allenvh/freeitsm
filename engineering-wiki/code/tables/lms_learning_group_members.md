# lms_learning_group_members

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `group_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/lms/group.php](/code/files/api/lms/group.php.md):39 |  | delete |  |  |
| [api/lms/group.php](/code/files/api/lms/group.php.md):41 |  | insert | `group_id`, `analyst_id` |  |
| [api/lms/groups.php](/code/files/api/lms/groups.php.md):59 |  | insert | `group_id`, `analyst_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/lms/assignment_targets.php](/code/files/api/lms/assignment_targets.php.md) | L81 |  |
| [api/lms/groups.php](/code/files/api/lms/groups.php.md) | L19, L29 |  |
| [includes/lms_access.php](/code/files/includes/lms_access.php.md) | lmsAssignmentReachSql L235, lmsAssignedLearnersSql L514 |  |

