# lms_course_assignments

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `course_id` | `INT NOT NULL` |  |
| `target_type` | `VARCHAR(20) NOT NULL DEFAULT 'learning_group'` | Which kind of thing group_id names: 'learning_group' (analyst groups, the original and the default so existing rows are unchanged), 'user_group' (the shared people groups), or 'all_users' (every active portal user, group_id ignored). |
| `group_id` | `INT NOT NULL` |  |
| `deadline` | `DATETIME NULL` |  |
| `assigned_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/lms/assignment.php](/code/files/api/lms/assignment.php.md):73 |  | update | `course_id`, `target_type`, `group_id`, `deadline` |  |
| [api/lms/assignment.php](/code/files/api/lms/assignment.php.md):91 |  | delete |  |  |
| [api/lms/assignments.php](/code/files/api/lms/assignments.php.md):115 |  | insert | `course_id`, `target_type`, `group_id`, `deadline`, `assigned_by_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/lms/assignment.php](/code/files/api/lms/assignment.php.md) | L52 |  |
| [api/lms/assignments.php](/code/files/api/lms/assignments.php.md) | L55 |  |
| [includes/lms_access.php](/code/files/includes/lms_access.php.md) | lmsCourseAssignedTo L277, lmsMyCourses L339, lmsAssignedLearnersSql L514, lmsAssignedLearnersSql L524, lmsAssignedLearnersSql L534, lmsAssignedLearnersSql L544 ... |  |
| [self-service/includes/header.php](/code/files/self-service/includes/header.php.md) | L153 |  |

