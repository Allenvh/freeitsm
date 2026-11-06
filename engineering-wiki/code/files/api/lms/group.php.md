# api/lms/group.php

> API endpoint - 63 lines

LMS API: Single group operations (PUT update with members, DELETE deactivate)

## The endpoint

| | |
|---|---|
| URL | `api/lms/group.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | **update** [lms_learning_groups](/code/tables/lms_learning_groups.md) (`name`, `description`, `updated_datetime`) |  |
| 39 |  | **delete** [lms_learning_group_members](/code/tables/lms_learning_group_members.md) |  |
| 41 |  | **insert** [lms_learning_group_members](/code/tables/lms_learning_group_members.md) (`group_id`, `analyst_id`) |  |
| 57 |  | **update** [lms_learning_groups](/code/tables/lms_learning_groups.md) (`is_active`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

