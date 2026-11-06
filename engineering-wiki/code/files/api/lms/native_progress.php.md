# api/lms/native_progress.php

> API endpoint - 229 lines

LMS API: progress + grading for a native course.

GET  ?course_id=N   — register an attempt, return the resume point.
POST {course_id, bookmark}                       — remember where they got to.
POST {course_id, finished:true, responses:[…]}   — grade the attempt.

GRADING IS SERVER-SIDE AND AUTHORITATIVE. The client posts only which answer
*ids* were chosen; the correct set is re-read from lms_answers here. The
learner never receives is_correct (see course_content.php), so there is
nothing on the client to tamper with — a forged POST can change which answers
it claims were picked, but not whether they were right.

Results are written as SCORM cmi.interactions.* rows into lms_cmi_data, which
is what makes an authored course show up in the existing admin Progress tab
and learner drill-down with no new admin UI at all.

## The endpoint

| | |
|---|---|
| URL | `api/lms/native_progress.php` |
| Protected by | Module access: lms (requireModuleAccessJson); requireLmsCourseAccessJson |
| Reads | `GET course_id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [lmsFormatTime()](/code/functions/lmsFormatTime.md) | 46 | HH:MM:SS — the format lms.js `formatTime()` understands. |
| [lmsParseTime()](/code/functions/lmsParseTime.md) | 56 | Parse HH:MM:SS back to seconds, so session time can be accumulated. |
| [lmsWriteCmi()](/code/functions/lmsWriteCmi.md) | 68 | Upsert one CMI element. The unique key on (progress_id, element) does the work. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 69 | `lmsWriteCmi` | **insert** [lms_cmi_data](/code/tables/lms_cmi_data.md) (`progress_id`, `element`, `value`, `created_datetime`, `updated_datetime`) |  |
| 84 |  | **update** [lms_progress](/code/tables/lms_progress.md) (`attempt_count`, `last_access`, `updated_datetime`) |  |
| 96 |  | read [lms_courses](/code/tables/lms_courses.md) |  |
| 114 |  | **update** [lms_progress](/code/tables/lms_progress.md) (`bookmark`, `status`, `total_time`, `last_access`, `updated_datetime`) |  |
| 123 |  | read [lms_questions](/code/tables/lms_questions.md), read [lms_lessons](/code/tables/lms_lessons.md) |  |
| 134 |  | read [lms_answers](/code/tables/lms_answers.md) |  |
| 207 |  | **update** [lms_progress](/code/tables/lms_progress.md) (`status`, `score_raw`, `score_min`, `score_max`, `total_time`, `completion_datetime`, `last_access`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/lms_access.php](/code/files/includes/lms_access.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsProgressRowFor()](/code/functions/lmsProgressRowFor.md), [requireLmsCourseAccessJson()](/code/functions/requireLmsCourseAccessJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

