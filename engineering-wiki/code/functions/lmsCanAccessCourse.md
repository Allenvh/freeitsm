# lmsCanAccessCourse()

May this learner open/play this course? Managers (and admins) may open any course — that's how Preview works. Everyone else may open only what's assigned to them. This is the gate the player and the learner content APIs enforce.

⚠️ THE MANAGER BYPASS IS ANALYST-ONLY, and deliberately so. `lmsCanManage()`
asks an RBAC question about an `analysts` row, and a portal user's id is a
`users` row — passing it in would ask whether analyst #12 is an LMS manager
while holding portal user #12, and answer about the wrong person entirely.
A portal learner has no Preview and no override: assigned, or nothing.

```php
function lmsCanAccessCourse(PDO $conn, LmsLearner $learner, int $courseId): bool
```

Defined in [includes/lms_access.php](/code/files/includes/lms_access.php.md), line 298.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$learner` | `LmsLearner` |  | the person to check; an analyst who manages the LMS may open any course |
| `$courseId` | `int` |  | the lms_courses id |

**Returns:** `bool`

## Calls

[lmsCanManage()](/code/functions/lmsCanManage.md), [lmsCourseAssignedTo()](/code/functions/lmsCourseAssignedTo.md)

## Called from

- [includes/lms_access.php](/code/files/includes/lms_access.php.md) - in [requireLmsCourseAccessJson()](/code/functions/requireLmsCourseAccessJson.md)
- [lms/player.php](/code/files/lms/player.php.md)
- [self-service/course.php](/code/files/self-service/course.php.md)

