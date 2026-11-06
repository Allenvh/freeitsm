# lms_lessons

Native course content. Lesson bodies are TinyMCE HTML (as knowledge articles are); answers carry the key, which never leaves the server.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `course_id` | `INT NOT NULL` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `body` | `LONGTEXT NULL` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/lms/lessons.php](/code/files/api/lms/lessons.php.md):77 |  | delete |  |  |
| [api/lms/lessons.php](/code/files/api/lms/lessons.php.md):88 |  | update | `display_order` |  |
| [api/lms/lessons.php](/code/files/api/lms/lessons.php.md):106 |  | update | `title`, `body`, `updated_datetime` |  |
| [api/lms/lessons.php](/code/files/api/lms/lessons.php.md):114 |  | insert | `course_id`, `title`, `body`, `display_order`, `created_datetime`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/lms/ai_author.php](/code/files/api/lms/ai_author.php.md) | L139 |  |
| [api/lms/course_content.php](/code/files/api/lms/course_content.php.md) | L46 |  |
| [api/lms/lessons.php](/code/files/api/lms/lessons.php.md) | L31, L110 |  |
| [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md) | L123 |  |
| [includes/lms_access.php](/code/files/includes/lms_access.php.md) | lmsAttachLessonProgress L411 |  |

