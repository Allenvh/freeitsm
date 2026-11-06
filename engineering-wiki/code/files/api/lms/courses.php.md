# api/lms/courses.php

> API endpoint - 178 lines

LMS API: List courses (GET) or Upload new course (POST)

## The endpoint

| | |
|---|---|
| URL | `api/lms/courses.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |
| Reads | `POST title`, `POST description` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 23 |  | read [lms_courses](/code/tables/lms_courses.md), read [analysts](/code/tables/analysts.md) |  |
| 67 |  | **insert** [lms_courses](/code/tables/lms_courses.md) (`title`, `description`, `original_filename`, `created_by_id`) |  |
| 165 |  | **update** [lms_courses](/code/tables/lms_courses.md) (`scorm_version`, `manifest_identifier`, `launch_url`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/lms_package.php](/code/files/includes/lms_package.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsExtractPackage()](/code/functions/lmsExtractPackage.md), [lmsHardenContentDir()](/code/functions/lmsHardenContentDir.md), [lmsValidatePackage()](/code/functions/lmsValidatePackage.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

