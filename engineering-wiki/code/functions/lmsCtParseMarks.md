# lmsCtParseMarks()

'5,3,1,0' -> [5,3,1,0]: four whole numbers, best first, the best above zero. Null if unusable.

```php
function lmsCtParseMarks(string $raw): ?array
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 93.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | comma-separated marks as typed in the settings, e.g. '5,3,1,0' |

**Returns:** `?array`

## Called from

- [api/lms/tests.php](/code/files/api/lms/tests.php.md)
- [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md) - in [lmsCtGradedMarks()](/code/functions/lmsCtGradedMarks.md)

