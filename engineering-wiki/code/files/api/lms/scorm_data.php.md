# api/lms/scorm_data.php

> API endpoint - 204 lines

LMS API: SCORM runtime data endpoint

GET  ?course_id=N  — Load all CMI data for the current analyst + course (called on Initialize)
POST               — Save CMI data elements (called on Commit/Finish)

## The endpoint

| | |
|---|---|
| URL | `api/lms/scorm_data.php` |
| Protected by | Module access: lms (requireModuleAccessJson); requireLmsCourseAccessJson |
| Reads | `GET course_id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [mapScormStatus()](/code/functions/mapScormStatus.md) | 191 | Map SCORM status values to our normalised status |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 |  | read [lms_progress](/code/tables/lms_progress.md) |  |
| 55 |  | **update** [lms_progress](/code/tables/lms_progress.md) (`attempt_count`, `last_access`, `updated_datetime`) |  |
| 62 |  | **update** [lms_progress](/code/tables/lms_progress.md) (`attempt_count`, `last_access`, `updated_datetime`) |  |
| 66 |  | read [lms_cmi_data](/code/tables/lms_cmi_data.md) |  |
| 95 |  | read [lms_progress](/code/tables/lms_progress.md) |  |
| 109 |  | read [lms_cmi_data](/code/tables/lms_cmi_data.md) |  |
| 110 |  | **update** [lms_cmi_data](/code/tables/lms_cmi_data.md) (`value`, `updated_datetime`) |  |
| 111 |  | **insert** [lms_cmi_data](/code/tables/lms_cmi_data.md) (`progress_id`, `element`, `value`, `updated_datetime`) |  |
| 173 |  | **update** [lms_progress](/code/tables/lms_progress.md) |  |
| 175 |  | **update** [lms_progress](/code/tables/lms_progress.md) (`last_access`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/lms_access.php](/code/files/includes/lms_access.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsProgressRowFor()](/code/functions/lmsProgressRowFor.md), [requireLmsCourseAccessJson()](/code/functions/requireLmsCourseAccessJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

