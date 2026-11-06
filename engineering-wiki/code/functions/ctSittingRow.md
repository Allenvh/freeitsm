# ctSittingRow()

A sitting row as the analyst pages see it - never the token hash.

```php
function ctSittingRow(array $s): array
```

Defined in [api/lms/tests.php](/code/files/api/lms/tests.php.md), line 51.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `array` |  | an lms_ct_sittings row, ideally with test_title joined in (as `ctLoadSitting()` loads it) |

**Returns:** `array`

## Calls

[lmsCtState()](/code/functions/lmsCtState.md)

## Called from

- [api/lms/tests.php](/code/files/api/lms/tests.php.md)

