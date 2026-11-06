# api/lms/course.php

> API endpoint - 105 lines

LMS API: Single course operations (GET detail, PUT update, DELETE deactivate)

## The endpoint

| | |
|---|---|
| URL | `api/lms/course.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |
| Reads | `GET id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [lmsReadPassMark()](/code/functions/lmsReadPassMark.md) | 42 | Read a pass mark off the request. Null means "no pass mark" — the course is marked complete on reaching the end rather than passed/failed. Anything outside 0-100 is a bad request rather than something to silently clamp: a course whose pass mark isn't what the author typed is worse than an error. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 21 |  | read [lms_courses](/code/tables/lms_courses.md) |  |
| 66 |  | **insert** [lms_courses](/code/tables/lms_courses.md) (`title`, `description`, `content_type`, `pass_mark`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |
| 87 |  | **update** [lms_courses](/code/tables/lms_courses.md) (`title`, `description`, `pass_mark`, `updated_datetime`) |  |
| 90 |  | **update** [lms_courses](/code/tables/lms_courses.md) (`title`, `description`, `updated_datetime`) |  |
| 98 |  | **update** [lms_courses](/code/tables/lms_courses.md) (`is_active`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

