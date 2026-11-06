# api/lms/progress.php

> API endpoint - 200 lines

LMS API: everyone's progress, for the manager Progress tab.

─────────────────────────────────────────────────────────────────────────────
WHY THIS IS A UNION AND NOT A JOIN

It used to be one chain — assignments -> learning group -> members ->
analysts — which worked while an assignment could only ever reach an analyst.
A course can now be given to a shared people group (holding analysts AND
portal users) or to every portal user at once, so "who is expected to do this
course" comes from three different places and lands in two different tables.

🔴 THE OLD SHAPE WOULD NOT HAVE FAILED LOUDLY. `JOIN analysts a ON
m.analyst_id = a.id` simply matches nothing for a portal learner, so the
screen would have reported a course pushed to 400 people as having nobody
assigned to it — a confident, wrong, empty table. That is the whole reason
this file was rewritten rather than extended.
─────────────────────────────────────────────────────────────────────────────

## The endpoint

| | |
|---|---|
| URL | `api/lms/progress.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |
| Reads | `GET course_id`, `GET group_id`, `GET status`, `GET learner`, `GET analyst_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 62 |  | read [lms_courses](/code/tables/lms_courses.md), read [lms_progress](/code/tables/lms_progress.md) |  |
| 97 |  | read [analysts](/code/tables/analysts.md) |  |
| 103 |  | read [users](/code/tables/users.md) |  |
| 111 |  | read [lms_learning_groups](/code/tables/lms_learning_groups.md) |  |
| 115 |  | read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/lms_access.php](/code/files/includes/lms_access.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsAssignedLearnersSql()](/code/functions/lmsAssignedLearnersSql.md), [lmsIsOverdue()](/code/functions/lmsIsOverdue.md), [lmsUserGroupsAvailable()](/code/functions/lmsUserGroupsAvailable.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

