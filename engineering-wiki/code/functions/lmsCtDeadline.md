# lmsCtDeadline()

When a started sitting's time runs out (UTC unix time), or null if not started.

```php
function lmsCtDeadline(array $s): ?int
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 341.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `array` |  | an lms_ct_sittings row (started_datetime, time_limit_minutes) |

**Returns:** `?int`

## Called from

- [api/lms/test_public.php](/code/files/api/lms/test_public.php.md)
- [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md) - in [lmsCtState()](/code/functions/lmsCtState.md), [lmsCtFinalise()](/code/functions/lmsCtFinalise.md)
- [lms/test.php](/code/files/lms/test.php.md)

