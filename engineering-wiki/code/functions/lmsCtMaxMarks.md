# lmsCtMaxMarks()

The best marks a question offers - what a full score on it means.

```php
function lmsCtMaxMarks(array $answers): int
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 171.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$answers` | `array` |  | the question's answers, each with 'marks' |

**Returns:** `int`

## Called from

- [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md) - in [lmsCtBuildSnapshot()](/code/functions/lmsCtBuildSnapshot.md)

