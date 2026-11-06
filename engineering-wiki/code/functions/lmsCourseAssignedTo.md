# lmsCourseAssignedTo()

Is this course assigned to this learner? This is what they are *entitled* to take, by any of the routes above.

```php
function lmsCourseAssignedTo(PDO $conn, LmsLearner $learner, int $courseId): bool
```

Defined in [includes/lms_access.php](/code/files/includes/lms_access.php.md), line 273.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$learner` | `LmsLearner` |  | the person to check |
| `$courseId` | `int` |  | the lms_courses id; 0 or less gives false |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 277 | `lmsCourseAssignedTo` | read [lms_course_assignments](/code/tables/lms_course_assignments.md) |  |

## Calls

[lmsAssignmentReachSql()](/code/functions/lmsAssignmentReachSql.md)

## Called from

- [includes/lms_access.php](/code/files/includes/lms_access.php.md) - in [lmsCanAccessCourse()](/code/functions/lmsCanAccessCourse.md)

