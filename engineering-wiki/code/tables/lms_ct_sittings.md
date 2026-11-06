# lms_ct_sittings

One candidate's attempt. snapshot_json freezes exactly what they were shown (questions, answers in their shuffled order, marks), so editing or hiding a bank question later never changes a result. The link's token is stored only as a SHA-256 hash.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `test_id` | `INT NULL` |  |
| `candidate_name` | `VARCHAR(200) NOT NULL` |  |
| `candidate_email` | `VARCHAR(255) NULL` |  |
| `token_hash` | `CHAR(64) NOT NULL` |  |
| `snapshot_json` | `LONGTEXT NOT NULL` |  |
| `responses_json` | `TEXT NULL` |  |
| `time_limit_minutes` | `INT NULL` |  |
| `expires_datetime` | `DATETIME NOT NULL` |  |
| `started_datetime` | `DATETIME NULL` |  |
| `submitted_datetime` | `DATETIME NULL` |  |
| `finish_reason` | `VARCHAR(20) NULL` |  |
| `score_percent` | `DECIMAL(5,1) NULL` |  |
| `skills_json` | `TEXT NULL` |  |
| `notes` | `TEXT NULL` |  |
| `is_cancelled` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/lms/tests.php](/code/files/api/lms/tests.php.md):393 |  | insert | `test_id`, `candidate_name`, `candidate_email`, `token_hash`, `snapshot_json`, `time_limit_minutes`, `expires_datetime`, `created_by_analyst_id`, `created_datetime` |  |
| [api/lms/tests.php](/code/files/api/lms/tests.php.md):423 |  | update | `token_hash`, `expires_datetime` |  |
| [api/lms/tests.php](/code/files/api/lms/tests.php.md):430 |  | update | `is_cancelled` |  |
| [api/lms/tests.php](/code/files/api/lms/tests.php.md):435 |  | delete |  |  |
| [api/lms/tests.php](/code/files/api/lms/tests.php.md):442 |  | update | `notes` |  |
| [api/lms/test_public.php](/code/files/api/lms/test_public.php.md):56 |  | update | `started_datetime` |  |
| [api/lms/test_public.php](/code/files/api/lms/test_public.php.md):70 |  | update | `responses_json` |  |
| [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md):409 | `lmsCtFinalise` | update | `submitted_datetime`, `finish_reason`, `score_percent`, `skills_json` |  |
| [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md):442 | `lmsCtPurge` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/lms/test_public.php](/code/files/api/lms/test_public.php.md) | L78 |  |
| [api/lms/tests.php](/code/files/api/lms/tests.php.md) | ctLoadSitting L79, L94, L112, L152, L249 |  |
| [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md) | lmsCtFindSitting L331, lmsCtFinaliseOverdue L419 |  |
| [lms/test.php](/code/files/lms/test.php.md) | L34 |  |

