# lms_ct_test_questions

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `test_id` | `INT NOT NULL` |  |
| `question_id` | `INT NOT NULL` |  |
| `sort_order` | `INT NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/lms/tests.php](/code/files/api/lms/tests.php.md):255 |  | delete |  |  |
| [api/lms/tests.php](/code/files/api/lms/tests.php.md):328 |  | delete |  |  |
| [api/lms/tests.php](/code/files/api/lms/tests.php.md):336 |  | update | `sort_order` |  |
| [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md):248 | `lmsCtAttach` | insert | `test_id`, `question_id`, `sort_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/lms/tests.php](/code/files/api/lms/tests.php.md) | L94, L133, L272, L280 |  |
| [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md) | lmsCtTestQuestions L218, lmsCtNextSort L234 |  |

