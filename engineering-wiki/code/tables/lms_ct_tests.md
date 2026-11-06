# lms_ct_tests

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `title` | `VARCHAR(200) NOT NULL` |  |
| `role_description` | `TEXT NULL` |  |
| `time_limit_minutes` | `INT NULL` |  |
| `pass_mark` | `INT NULL` |  |
| `is_archived` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/lms/tests.php](/code/files/api/lms/tests.php.md):213 |  | update | `title`, `role_description`, `time_limit_minutes`, `pass_mark`, `updated_datetime` |  |
| [api/lms/tests.php](/code/files/api/lms/tests.php.md):219 |  | insert | `title`, `role_description`, `time_limit_minutes`, `pass_mark`, `created_by_analyst_id`, `created_datetime` |  |
| [api/lms/tests.php](/code/files/api/lms/tests.php.md):252 |  | update | `is_archived`, `updated_datetime` |  |
| [api/lms/tests.php](/code/files/api/lms/tests.php.md):257 |  | delete |  |  |
| [api/lms/tests.php](/code/files/api/lms/tests.php.md):306 |  | update | `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/lms/tests.php](/code/files/api/lms/tests.php.md) | ctLoadSitting L79, L94, L106, L152, L215, L266 ... |  |
| [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md) | lmsCtBuildSnapshot L266 |  |

