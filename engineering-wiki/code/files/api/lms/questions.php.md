# api/lms/questions.php

> API endpoint - 91 lines

LMS API: save/delete a question and its answers (author-side).

A question and its options are saved as one unit — the answers are deleted and
re-inserted inside a transaction rather than diffed. That means answer ids are
not stable across an edit, which is fine: nothing references them once a
learner's response has been recorded (responses are stored as TEXT in
lms_cmi_data, deliberately, so an edit can never rewrite history).

## The endpoint

| | |
|---|---|
| URL | `api/lms/questions.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | **delete** [lms_questions](/code/tables/lms_questions.md) |  |
| 65 |  | **update** [lms_questions](/code/tables/lms_questions.md) (`question_text`, `question_type`, `explanation`, `updated_datetime`) |  |
| 67 |  | **delete** [lms_answers](/code/tables/lms_answers.md) |  |
| 69 |  | read [lms_questions](/code/tables/lms_questions.md) |  |
| 73 |  | **insert** [lms_questions](/code/tables/lms_questions.md) (`lesson_id`, `question_text`, `question_type`, `explanation`, `display_order`, `created_datetime`, `updated_datetime`) |  |
| 79 |  | **insert** [lms_answers](/code/tables/lms_answers.md) (`question_id`, `answer_text`, `is_correct`, `display_order`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

