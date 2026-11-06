# lmsProgressRowFor()

This learner's progress row for a course, created on first sight.

🔑 ONE COPY, because there were two and they are the rows that record whether
somebody has done their mandatory training. api/lms/native_progress.php and
api/lms/scorm_data.php each had their own find-or-create, written against
`analyst_id`; two independent INSERTs into a table whose unique key was about
to change is precisely the pair you do not want to leave lying around.

⚠️ `analyst_id` IS WRITTEN TOO, for an analyst, and left NULL for a portal
learner. It is legacy (see freeitsm.sql) and nothing reads it — but while the
column still exists it must not go stale, or the next person to write a report
against the obvious-looking column gets a partial answer with no hint that it
is partial. It goes at the next MAJOR, and this line goes with it.

```php
function lmsProgressRowFor(PDO $conn, LmsLearner $learner, int $courseId): array
```

Defined in [includes/lms_access.php](/code/files/includes/lms_access.php.md), line 457.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$learner` | `LmsLearner` |  | the person taking the course |
| `$courseId` | `int` |  | the lms_courses id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 459 | `lmsProgressRowFor` | read [lms_progress](/code/tables/lms_progress.md) |  |
| 471 | `lmsProgressRowFor` | **insert** [lms_progress](/code/tables/lms_progress.md) (`analyst_id`, `learner_type`, `learner_id`, `course_id`, `status`, `first_access`, `last_access`, `attempt_count`, `created_datetime`, `updated_datetime`) |  |

## Called from

- [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md)
- [api/lms/scorm_data.php](/code/files/api/lms/scorm_data.php.md)

