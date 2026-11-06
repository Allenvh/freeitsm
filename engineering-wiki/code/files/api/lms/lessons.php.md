# api/lms/lessons.php

> API endpoint - 125 lines

LMS API: lessons of a native course — the AUTHOR's view.

GET returns lessons with their questions and answers INCLUDING the answer key,
because an author needs to see and edit it. The learner's view is a different
endpoint (course_content.php) which strips it. Keeping the two apart is what
stops the key leaking: there is no flag to get wrong, just two endpoints with
different jobs, and only this one is behind the module write guard.

## The endpoint

| | |
|---|---|
| URL | `api/lms/lessons.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |
| Reads | `GET course_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [lms_lessons](/code/tables/lms_lessons.md) |  |
| 39 |  | read [lms_questions](/code/tables/lms_questions.md) |  |
| 48 |  | read [lms_answers](/code/tables/lms_answers.md) |  |
| 77 |  | **delete** [lms_lessons](/code/tables/lms_lessons.md) |  |
| 88 |  | **update** [lms_lessons](/code/tables/lms_lessons.md) (`display_order`) |  |
| 106 |  | **update** [lms_lessons](/code/tables/lms_lessons.md) (`title`, `body`, `updated_datetime`) |  |
| 110 |  | read [lms_lessons](/code/tables/lms_lessons.md) |  |
| 114 |  | **insert** [lms_lessons](/code/tables/lms_lessons.md) (`course_id`, `title`, `body`, `display_order`, `created_datetime`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

