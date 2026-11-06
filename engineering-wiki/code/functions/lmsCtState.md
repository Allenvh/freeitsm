# lmsCtState()

Where a sitting stands: cancelled | submitted | expired (never started, link out of date) | timeup (started, clock run out, not yet closed) | ready | in_progress.

```php
function lmsCtState(array $s, ?int $now = null): string
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 356.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `array` |  | an lms_ct_sittings row |
| `$now` | `?int` | `null` | the time to judge against, as a Unix timestamp; null for now |

**Returns:** `string`

## Calls

[lmsCtDeadline()](/code/functions/lmsCtDeadline.md)

## Called from

- [api/lms/test_public.php](/code/files/api/lms/test_public.php.md)
- [api/lms/tests.php](/code/files/api/lms/tests.php.md) - in [ctSittingRow()](/code/functions/ctSittingRow.md)
- [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md) - in [lmsCtFinaliseOverdue()](/code/functions/lmsCtFinaliseOverdue.md)
- [lms/test.php](/code/files/lms/test.php.md)

