# api/lms/groups.php

> API endpoint - 71 lines

LMS API: List groups (GET) or Create group (POST)

## The endpoint

| | |
|---|---|
| URL | `api/lms/groups.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 19 |  | read [lms_learning_group_members](/code/tables/lms_learning_group_members.md), read [lms_learning_groups](/code/tables/lms_learning_groups.md) |  |
| 29 |  | read [lms_learning_group_members](/code/tables/lms_learning_group_members.md), read [analysts](/code/tables/analysts.md) |  |
| 54 |  | **insert** [lms_learning_groups](/code/tables/lms_learning_groups.md) (`name`, `description`, `created_by_id`) |  |
| 59 |  | **insert** [lms_learning_group_members](/code/tables/lms_learning_group_members.md) (`group_id`, `analyst_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

