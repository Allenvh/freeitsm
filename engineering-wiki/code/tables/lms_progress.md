# lms_progress

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NULL` | ⚠️ LEGACY. The learner is (learner_type, learner_id) — see freeitsm.sql. Kept in step for analyst rows, NULL for portal learners, dropped at the next MAJOR. The NOT NULL -> NULL relaxation is done by the repair pass in db_verify.php, since this array only ADDS missing columns. |
| `learner_type` | `VARCHAR(10) NOT NULL DEFAULT 'analyst'` |  |
| `learner_id` | `INT NOT NULL DEFAULT 0` |  |
| `course_id` | `INT NOT NULL` |  |
| `status` | `VARCHAR(20) NOT NULL DEFAULT 'not_started'` |  |
| `score_raw` | `DECIMAL(10,2) NULL` |  |
| `score_min` | `DECIMAL(10,2) NULL` |  |
| `score_max` | `DECIMAL(10,2) NULL` |  |
| `total_time` | `VARCHAR(50) NULL` |  |
| `bookmark` | `VARCHAR(500) NULL` |  |
| `suspend_data` | `LONGTEXT NULL` |  |
| `completion_datetime` | `DATETIME NULL` |  |
| `first_access` | `DATETIME NULL` |  |
| `last_access` | `DATETIME NULL` |  |
| `attempt_count` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md):84 |  | update | `attempt_count`, `last_access`, `updated_datetime` |  |
| [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md):114 |  | update | `bookmark`, `status`, `total_time`, `last_access`, `updated_datetime` |  |
| [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md):207 |  | update | `status`, `score_raw`, `score_min`, `score_max`, `total_time`, `completion_datetime`, `last_access`, `updated_datetime` |  |
| [api/lms/scorm_data.php](/code/files/api/lms/scorm_data.php.md):55 |  | update | `attempt_count`, `last_access`, `updated_datetime` |  |
| [api/lms/scorm_data.php](/code/files/api/lms/scorm_data.php.md):62 |  | update | `attempt_count`, `last_access`, `updated_datetime` |  |
| [api/lms/scorm_data.php](/code/files/api/lms/scorm_data.php.md):173 |  | update |  |  |
| [api/lms/scorm_data.php](/code/files/api/lms/scorm_data.php.md):175 |  | update | `last_access`, `updated_datetime` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3998 |  | update | `learner_id`, `learner_type` |  |
| [includes/lms_access.php](/code/files/includes/lms_access.php.md):471 | `lmsProgressRowFor` | insert | `analyst_id`, `learner_type`, `learner_id`, `course_id`, `status`, `first_access`, `last_access`, `attempt_count`, `created_datetime`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/lms/course_content.php](/code/files/api/lms/course_content.php.md) | L84 |  |
| [api/lms/learner_data.php](/code/files/api/lms/learner_data.php.md) | L42 |  |
| [api/lms/progress.php](/code/files/api/lms/progress.php.md) | L62 |  |
| [api/lms/scorm_data.php](/code/files/api/lms/scorm_data.php.md) | L40, L95 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L4008 |  |
| [includes/lms_access.php](/code/files/includes/lms_access.php.md) | lmsMyCourses L339, lmsProgressRowFor L459 |  |
| [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md) | lmsRemindersDue L157 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleCoursesForCompany L558 |  |

