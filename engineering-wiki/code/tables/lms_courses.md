# lms_courses

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `description` | `LONGTEXT NULL` |  |
| `content_type` | `VARCHAR(10) NOT NULL DEFAULT 'scorm'` | 'scorm' (uploaded package) or 'native' (authored here). Defaulting to 'scorm' is what silently classifies every pre-existing course correctly. |
| `pass_mark` | `INT NULL` |  |
| `scorm_version` | `VARCHAR(20) NULL` |  |
| `manifest_identifier` | `VARCHAR(255) NULL` |  |
| `launch_url` | `VARCHAR(500) NULL` |  |
| `original_filename` | `VARCHAR(255) NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/lms/course.php](/code/files/api/lms/course.php.md):66 |  | insert | `title`, `description`, `content_type`, `pass_mark`, `created_by_id`, `created_datetime`, `updated_datetime` |  |
| [api/lms/course.php](/code/files/api/lms/course.php.md):87 |  | update | `title`, `description`, `pass_mark`, `updated_datetime` |  |
| [api/lms/course.php](/code/files/api/lms/course.php.md):90 |  | update | `title`, `description`, `updated_datetime` |  |
| [api/lms/course.php](/code/files/api/lms/course.php.md):98 |  | update | `is_active`, `updated_datetime` |  |
| [api/lms/courses.php](/code/files/api/lms/courses.php.md):67 |  | insert | `title`, `description`, `original_filename`, `created_by_id` |  |
| [api/lms/courses.php](/code/files/api/lms/courses.php.md):165 |  | update | `scorm_version`, `manifest_identifier`, `launch_url` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/lms/assignments.php](/code/files/api/lms/assignments.php.md) | L55 |  |
| [api/lms/course.php](/code/files/api/lms/course.php.md) | L21 |  |
| [api/lms/course_content.php](/code/files/api/lms/course_content.php.md) | L39 |  |
| [api/lms/courses.php](/code/files/api/lms/courses.php.md) | L23 |  |
| [api/lms/learner_data.php](/code/files/api/lms/learner_data.php.md) | L42 |  |
| [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md) | L96 |  |
| [api/lms/progress.php](/code/files/api/lms/progress.php.md) | L62 |  |
| [includes/lms_access.php](/code/files/includes/lms_access.php.md) | lmsMyCourses L339 |  |
| [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md) | lmsRemindersDue L157 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleCoursesForCompany L558 |  |
| [lms/editor.php](/code/files/lms/editor.php.md) | L26 |  |
| [lms/player.php](/code/files/lms/player.php.md) | L28 |  |
| [self-service/course.php](/code/files/self-service/course.php.md) | L44 |  |
| [self-service/includes/header.php](/code/files/self-service/includes/header.php.md) | L153 |  |

