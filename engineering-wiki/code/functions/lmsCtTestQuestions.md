# lmsCtTestQuestions()

The questions on a test, in order, decoded.

```php
function lmsCtTestQuestions(PDO $conn, int $testId): array
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 216.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$testId` | `int` |  | the lms_ct_tests id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 218 | `lmsCtTestQuestions` | read [lms_ct_test_questions](/code/tables/lms_ct_test_questions.md), read [lms_ct_questions](/code/tables/lms_ct_questions.md) |  |

## Called from

- [api/lms/tests.php](/code/files/api/lms/tests.php.md)
- [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md) - in [lmsCtBuildSnapshot()](/code/functions/lmsCtBuildSnapshot.md)

