# lmsAttachLessonProgress()

How far through a course somebody is, as "lesson N of M".

🔑 THERE IS NO STORED PERCENTAGE, AND THIS DOES NOT INVENT ONE. The only
position the product records for a native course is `lms_progress.bookmark`,
which the native player writes as the ID of the lesson being read. So the
honest reading is a POSITION IN A LIST - lesson 2 of 3 - and that is what the
page shows beside the bar. A bar on its own would imply a measured percentage
of a course that nothing measures.

⚠️ SCORM courses get nothing. They have no `lms_lessons` rows at all (their
content is a package on disk), and their bookmark holds `cmi.core.lesson_location`,
which is whatever the package chose to put there - a page id, a slide name, a
blob. It is not a lesson id and must never be compared to one, which is also
why this ranks in PHP rather than joining `lb.id = p.bookmark` in SQL: that
join would coerce arbitrary text to an integer and quietly match lesson 0.

A finished course is full whatever the bookmark says, because somebody who
passed and then reopened lesson 1 has not gone backwards.

Adds `lesson_count` and `lesson_position` to each row, both 0 when unknown.
One extra query for the whole page, not one per course.

```php
function lmsAttachLessonProgress(PDO $conn, array &$rows): void
```

Defined in [includes/lms_access.php](/code/files/includes/lms_access.php.md), line 391.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rows` | `array` |  | course rows (id, content_type, status, bookmark), changed in place - lesson_count and lesson_position are added |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 411 | `lmsAttachLessonProgress` | read [lms_lessons](/code/tables/lms_lessons.md) |  |

## Called from

- [includes/lms_access.php](/code/files/includes/lms_access.php.md) - in [lmsMyCourses()](/code/functions/lmsMyCourses.md)

