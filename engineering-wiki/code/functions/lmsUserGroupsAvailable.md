# lmsUserGroupsAvailable()

Are the shared people groups available on this install?

⚠️ Guarded because the assignment query joins `knowledge_user_groups`, and an
install part-way through an upgrade may not have it yet. Without this the
whole My Courses page would be a 500 rather than a page listing the courses
that reach the learner by every OTHER route. Cached per request; the same
habit as `tenancyColumnExists()` and the try/catch in knowledge/visibility.php.

```php
function lmsUserGroupsAvailable(PDO $conn): bool
```

Defined in [includes/lms_access.php](/code/files/includes/lms_access.php.md), line 195.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 199 | `lmsUserGroupsAvailable` | read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |

## Called from

- [api/lms/assignment_targets.php](/code/files/api/lms/assignment_targets.php.md)
- [api/lms/assignments.php](/code/files/api/lms/assignments.php.md)
- [api/lms/progress.php](/code/files/api/lms/progress.php.md)
- [includes/lms_access.php](/code/files/includes/lms_access.php.md) - in [lmsAssignmentReachSql()](/code/functions/lmsAssignmentReachSql.md), [lmsAssignedLearnersSql()](/code/functions/lmsAssignedLearnersSql.md), [lmsAssignmentTargetProblem()](/code/functions/lmsAssignmentTargetProblem.md)

