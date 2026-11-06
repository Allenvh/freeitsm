# lms_cmi_data

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `progress_id` | `INT NOT NULL` |  |
| `element` | `VARCHAR(255) NOT NULL` |  |
| `value` | `LONGTEXT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md):69 | `lmsWriteCmi` | insert | `progress_id`, `element`, `value`, `created_datetime`, `updated_datetime` |  |
| [api/lms/scorm_data.php](/code/files/api/lms/scorm_data.php.md):110 |  | update | `value`, `updated_datetime` |  |
| [api/lms/scorm_data.php](/code/files/api/lms/scorm_data.php.md):111 |  | insert | `progress_id`, `element`, `value`, `updated_datetime` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3927 |  | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/lms/learner_data.php](/code/files/api/lms/learner_data.php.md) | L71 |  |
| [api/lms/scorm_data.php](/code/files/api/lms/scorm_data.php.md) | L66, L109 |  |

