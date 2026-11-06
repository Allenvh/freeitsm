# lmsMyCourses()

The courses assigned to a learner by any route, each with their own progress — the data behind My Courses and the portal's Training page. One row per course even if it reaches them several ways (earliest deadline wins). is_overdue is computed here so the page and any caller agree.

🔑 The three-table join this used to open with is gone. Which assignments
reach somebody is now one question, answered in `lmsAssignmentReachSql()`, and a
plain EXISTS keeps this query returning ONE ROW PER COURSE — joining through
group membership instead would multiply the row by every group that reaches
it, which the old GROUP BY was quietly there to mop up.

```php
function lmsMyCourses(PDO $conn, LmsLearner $learner): array
```

Defined in [includes/lms_access.php](/code/files/includes/lms_access.php.md), line 336.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$learner` | `LmsLearner` |  | the person whose assigned courses are listed |

**Returns:** array<int,array<string,mixed>>

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 339 | `lmsMyCourses` | read [lms_course_assignments](/code/tables/lms_course_assignments.md), read [lms_courses](/code/tables/lms_courses.md), read [lms_progress](/code/tables/lms_progress.md) |  |

## Calls

[lmsAssignmentReachSql()](/code/functions/lmsAssignmentReachSql.md), [lmsAttachLessonProgress()](/code/functions/lmsAttachLessonProgress.md), [lmsIsOverdue()](/code/functions/lmsIsOverdue.md)

## Called from

- [api/lms/my_courses.php](/code/files/api/lms/my_courses.php.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleCoursesForPerson()](/code/functions/peopleCoursesForPerson.md)

