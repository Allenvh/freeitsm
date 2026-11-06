# api/lms/course_content.php

> API endpoint - 92 lines

LMS API: a native course as the LEARNER sees it.

This is the one place course content is handed to a player, and it exists
separately from lessons.php for a single reason: **it never sends the answer
key**. The SELECT below does not read is_correct at all, so there is no
filtering step to forget and nothing to strip — a learner cannot read the
answers out of the network tab because the server never puts them on the wire.
Grading happens in native_progress.php, against the database.

## The endpoint

| | |
|---|---|
| URL | `api/lms/course_content.php` |
| Protected by | Module access: lms (requireModuleAccessJson); requireLmsCourseAccessJson |
| Reads | `GET course_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [lms_courses](/code/tables/lms_courses.md) |  |
| 46 |  | read [lms_lessons](/code/tables/lms_lessons.md) |  |
| 54 |  | read [lms_questions](/code/tables/lms_questions.md) |  |
| 64 |  | read [lms_answers](/code/tables/lms_answers.md) |  |
| 84 |  | read [lms_progress](/code/tables/lms_progress.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/lms_access.php](/code/files/includes/lms_access.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireLmsCourseAccessJson()](/code/functions/requireLmsCourseAccessJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

