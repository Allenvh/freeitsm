# lmsCtGradedMarks()

The marks for each grade of a graded question, from the settings, or the default 5, 3, 1, 0.

```php
function lmsCtGradedMarks(array $settings): array
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 111.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$settings` | `array` |  | the competency test settings from `lmsCtSettings()` |

**Returns:** `array`

## Calls

[lmsCtParseMarks()](/code/functions/lmsCtParseMarks.md)

## Called from

- [api/lms/tests.php](/code/files/api/lms/tests.php.md)

