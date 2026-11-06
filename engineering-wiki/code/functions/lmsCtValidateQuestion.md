# lmsCtValidateQuestion()

Check a question from the editor or the AI. Returns [`$clean`, null] or [null, 'why']. A choice question has exactly one answer worth anything (stored as 1); a graded one needs a best answer worth more than zero.

```php
function lmsCtValidateQuestion(array $in): array
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 123.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$in` | `array` |  | untrusted question fields: skill, difficulty, format, question_text, answers [{text, marks}], explanation, status |

**Returns:** `array`

## Called from

- [api/lms/tests.php](/code/files/api/lms/tests.php.md)
- [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md) - in [lmsCtParseDrafts()](/code/functions/lmsCtParseDrafts.md)

