# api/lms/assignments.php

> API endpoint - 128 lines

LMS API: List assignments (GET) or Create one (POST).

An assignment says WHO a course is for. Three kinds of target, described on
lms_course_assignments in freeitsm.sql:
  learning_group  an analyst learning group (the original; still the default)
  user_group      a shared people group — analysts and portal users together
  all_users       every active portal user

## The endpoint

| | |
|---|---|
| URL | `api/lms/assignments.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 46 |  | read [lms_learning_groups](/code/tables/lms_learning_groups.md) |  |
| 48 |  | read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |
| 52 |  | read [analysts](/code/tables/analysts.md) |  |
| 53 |  | read [users](/code/tables/users.md) |  |
| 55 |  | read [lms_course_assignments](/code/tables/lms_course_assignments.md), read [lms_courses](/code/tables/lms_courses.md), read [analysts](/code/tables/analysts.md) |  |
| 115 |  | **insert** [lms_course_assignments](/code/tables/lms_course_assignments.md) (`course_id`, `target_type`, `group_id`, `deadline`, `assigned_by_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/lms_access.php](/code/files/includes/lms_access.php.md), [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsAssignmentTargetProblem()](/code/functions/lmsAssignmentTargetProblem.md), [lmsRemindersRunOpportunistic()](/code/functions/lmsRemindersRunOpportunistic.md), [lmsUserGroupsAvailable()](/code/functions/lmsUserGroupsAvailable.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

