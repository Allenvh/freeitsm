# api/lms/assignment_targets.php

> API endpoint - 112 lines

LMS API: everything a course can be assigned TO, as one flat list.

🔑 ONE PICKER, NOT A TYPE-THEN-THING PAIR. A manager knows they want "Finance"
— they do not want to first declare whether Finance is a learning group or a
people group, which is an implementation detail of where the members are
stored. Same reasoning as the Knowledge permission picker, which searches four
principal tables at once for exactly this reason.

Each row carries its `type` back, so the caller returns the pair the
assignment table actually stores without having to work it out.

## The endpoint

| | |
|---|---|
| URL | `api/lms/assignment_targets.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |
| Reads | `GET q` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [analysts](/code/tables/analysts.md) |  |
| 51 |  | read [users](/code/tables/users.md) |  |
| 78 |  | read [users](/code/tables/users.md) |  |
| 81 |  | read [lms_learning_group_members](/code/tables/lms_learning_group_members.md), read [lms_learning_groups](/code/tables/lms_learning_groups.md) |  |
| 95 |  | read [knowledge_user_group_members](/code/tables/knowledge_user_group_members.md), read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/lms_access.php](/code/files/includes/lms_access.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsUserGroupsAvailable()](/code/functions/lmsUserGroupsAvailable.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

