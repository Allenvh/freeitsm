# lmsAssignedLearnersSql()

THE OTHER DIRECTION: every (learner, course) an assignment reaches.

`lmsAssignmentReachSql()` answers "what reaches THIS person", which is what a
learner's own page needs. This answers "who does this reach", which is what
the manager's Progress tab and the reminder run need — and it cannot be the
same query, because one starts from a person and the other from an assignment.

Returns a parenthesised UNION usable as a derived table, with columns:
  learner_type, learner_id, course_id, group_id, target_type, deadline

🔑 UNION, not UNION ALL. Somebody reached by two routes — in a people group
AND caught by "everyone on the portal" — is ONE person expected to do ONE
course. Callers still reduce by (learner, course) afterwards, because two
routes can carry two different DEADLINES, and the earliest has to win.

⚠️ The people-group branch applies the membership expiry, for the same reason
the learner's own view does: somebody whose access has lapsed is no longer
expected to do the course, and leaving them in would generate chasing emails
for training they can no longer open.

```php
function lmsAssignedLearnersSql(PDO $conn): string
```

Defined in [includes/lms_access.php](/code/files/includes/lms_access.php.md), line 509.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 514 | `lmsAssignedLearnersSql` | read [lms_course_assignments](/code/tables/lms_course_assignments.md), read [lms_learning_groups](/code/tables/lms_learning_groups.md), read [lms_learning_group_members](/code/tables/lms_learning_group_members.md), read [analysts](/code/tables/analysts.md) |  |
| 524 | `lmsAssignedLearnersSql` | read [lms_course_assignments](/code/tables/lms_course_assignments.md), read [knowledge_user_groups](/code/tables/knowledge_user_groups.md), read [knowledge_user_group_members](/code/tables/knowledge_user_group_members.md) |  |
| 534 | `lmsAssignedLearnersSql` | read [lms_course_assignments](/code/tables/lms_course_assignments.md), read [users](/code/tables/users.md) |  |
| 544 | `lmsAssignedLearnersSql` | read [lms_course_assignments](/code/tables/lms_course_assignments.md), read [analysts](/code/tables/analysts.md) |  |
| 550 | `lmsAssignedLearnersSql` | read [lms_course_assignments](/code/tables/lms_course_assignments.md), read [users](/code/tables/users.md) |  |

## Calls

[lmsUserGroupsAvailable()](/code/functions/lmsUserGroupsAvailable.md)

## Called from

- [api/lms/progress.php](/code/files/api/lms/progress.php.md)
- [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md) - in [lmsRemindersDue()](/code/functions/lmsRemindersDue.md)

