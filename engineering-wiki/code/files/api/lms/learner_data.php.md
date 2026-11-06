# api/lms/learner_data.php

> API endpoint - 156 lines

LMS API: Get all CMI data for a specific analyst + course Returns structured data grouped by category for display.

## The endpoint

| | |
|---|---|
| URL | `api/lms/learner_data.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |
| Reads | `GET learner_type`, `GET learner_id`, `GET analyst_id`, `GET course_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [lms_progress](/code/tables/lms_progress.md), read [lms_courses](/code/tables/lms_courses.md) |  |
| 58 |  | read [analysts](/code/tables/analysts.md) |  |
| 60 |  | read [users](/code/tables/users.md) |  |
| 71 |  | read [lms_cmi_data](/code/tables/lms_cmi_data.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

