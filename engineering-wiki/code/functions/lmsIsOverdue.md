# lmsIsOverdue()

Has this course's deadline passed?

🔴 A DEADLINE IS A DAY, NOT AN INSTANT — and this used to treat it as one.
`lms_course_assignments.deadline` is filled in by an <input type="date">, so
what lands in the column is midnight: "due 9 September" is stored
`2026-09-09 00:00:00`. Comparing that to `now` as a UTC instant made a course
OVERDUE THE MOMENT ITS DUE DATE BEGAN — somebody told they had until the 9th
opened it on the morning of the 9th and found it already in red. Measured on
a real assignment before this was changed.

So the comparison is between CALENDAR DAYS, in the installation's own zone
(the same frame `naive_now()` reads local "now" in): you are overdue once the
day you were given has finished, which is what everybody means by a deadline.
The third kind of stored date — no time, no zone. See
Timezones-and-Time-Handling.

⚠️ Rows seeded with a real time of day (the demo data has a few at 17:00) are
treated the same way, which is the kinder reading and keeps one rule.

🔑 One function, called by both the learner's view and the manager's Progress
tab, because the two screens disagreeing about whether somebody is late is
worse than either of them being wrong on its own.

```php
function lmsIsOverdue(?string $deadline, string $status): bool
```

Defined in [includes/lms_access.php](/code/files/includes/lms_access.php.md), line 177.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$deadline` | `?string` |  | the assignment deadline as stored (a date, read as a whole local day); null or '' is never overdue |
| `$status` | `string` |  | the learner's progress status; 'completed' and 'passed' are never overdue |

**Returns:** `bool`

## Called from

- [api/lms/progress.php](/code/files/api/lms/progress.php.md)
- [includes/lms_access.php](/code/files/includes/lms_access.php.md) - in [lmsMyCourses()](/code/functions/lmsMyCourses.md)

