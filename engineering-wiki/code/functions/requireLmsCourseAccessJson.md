# requireLmsCourseAccessJson()

Hard gate for a learner course API: 403 unless the caller may access `$courseId`. Resolves the learner from whichever session is present, so one guard covers both the analyst app and the portal. Assumes the session check and module gate have already run.

```php
function requireLmsCourseAccessJson(PDO $conn, int $courseId): void
```

Defined in [includes/lms_access.php](/code/files/includes/lms_access.php.md), line 311.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$courseId` | `int` |  | the lms_courses id the request is about |

**Returns:** `void`

## Calls

[lmsCanAccessCourse()](/code/functions/lmsCanAccessCourse.md)

## Called from

- [api/lms/course_content.php](/code/files/api/lms/course_content.php.md)
- [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md)
- [api/lms/scorm_data.php](/code/files/api/lms/scorm_data.php.md)

