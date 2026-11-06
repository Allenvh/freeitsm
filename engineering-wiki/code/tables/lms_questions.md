# lms_questions

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `lesson_id` | `INT NOT NULL` |  |
| `question_text` | `TEXT NOT NULL` |  |
| `question_type` | `VARCHAR(20) NOT NULL DEFAULT 'single'` |  |
| `explanation` | `TEXT NULL` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/lms/questions.php](/code/files/api/lms/questions.php.md):30 |  | delete |  |  |
| [api/lms/questions.php](/code/files/api/lms/questions.php.md):65 |  | update | `question_text`, `question_type`, `explanation`, `updated_datetime` |  |
| [api/lms/questions.php](/code/files/api/lms/questions.php.md):73 |  | insert | `lesson_id`, `question_text`, `question_type`, `explanation`, `display_order`, `created_datetime`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/lms/course_content.php](/code/files/api/lms/course_content.php.md) | L54 |  |
| [api/lms/lessons.php](/code/files/api/lms/lessons.php.md) | L39 |  |
| [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md) | L123 |  |
| [api/lms/questions.php](/code/files/api/lms/questions.php.md) | L69 |  |

