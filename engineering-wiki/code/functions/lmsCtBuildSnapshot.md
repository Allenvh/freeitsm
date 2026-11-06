# lmsCtBuildSnapshot()

Freeze a test into what a candidate will sit. Throws, with a sentence an analyst can act on, when the test is not ready: no questions, or any that are still drafts or have been hidden since they were added.

```php
function lmsCtBuildSnapshot(PDO $conn, int $testId): array
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 264.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$testId` | `int` |  | the lms_ct_tests id to freeze |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 266 | `lmsCtBuildSnapshot` | read [lms_ct_tests](/code/tables/lms_ct_tests.md) |  |

## Calls

[lmsCtMaxMarks()](/code/functions/lmsCtMaxMarks.md), [lmsCtTestQuestions()](/code/functions/lmsCtTestQuestions.md)

## Called from

- [api/lms/tests.php](/code/files/api/lms/tests.php.md)

