# lms_answers

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `question_id` | `INT NOT NULL` |  |
| `answer_text` | `VARCHAR(500) NOT NULL` |  |
| `is_correct` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/lms/questions.php](/code/files/api/lms/questions.php.md):67 |  | delete |  |  |
| [api/lms/questions.php](/code/files/api/lms/questions.php.md):79 |  | insert | `question_id`, `answer_text`, `is_correct`, `display_order`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/lms/course_content.php](/code/files/api/lms/course_content.php.md) | L64 |  |
| [api/lms/lessons.php](/code/files/api/lms/lessons.php.md) | L48 |  |
| [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md) | L134 |  |

