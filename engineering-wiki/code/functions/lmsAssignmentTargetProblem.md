# lmsAssignmentTargetProblem()

Is this target real? Returns a human-readable problem, or null if it is fine.

🔑 Checked on the way IN, on both create and edit. Without it a typo'd id is
accepted happily and the assignment reaches nobody at all — which looks
exactly like the feature not working, and is the worst kind of silence:
the row is there on the screen, and nothing ever happens because of it.

```php
function lmsAssignmentTargetProblem(PDO $conn, string $targetType, int $groupId): ?string
```

Defined in [includes/lms_access.php](/code/files/includes/lms_access.php.md), line 584.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$targetType` | `string` |  | one of LMS_TARGET_TYPES - 'learning_group', 'user_group', 'all_users', 'analyst', 'user' |
| `$groupId` | `int` |  | the target's id, whose meaning depends on `$targetType` (ignored for 'all_users') |

**Returns:** `?string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 589 | `lmsAssignmentTargetProblem` | read [lms_learning_groups](/code/tables/lms_learning_groups.md) |  |
| 596 | `lmsAssignmentTargetProblem` | read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |
| 602 | `lmsAssignmentTargetProblem` | read [analysts](/code/tables/analysts.md) |  |
| 608 | `lmsAssignmentTargetProblem` | read [users](/code/tables/users.md) |  |

## Calls

[lmsUserGroupsAvailable()](/code/functions/lmsUserGroupsAvailable.md)

## Called from

- [api/lms/assignment.php](/code/files/api/lms/assignment.php.md)
- [api/lms/assignments.php](/code/files/api/lms/assignments.php.md)

