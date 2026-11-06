# lmsParseTime()

Parse HH:MM:SS back to seconds, so session time can be accumulated.

```php
function lmsParseTime(?string $t): int
```

Defined in [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md), line 56.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$t` | `?string` |  | a duration as HH:MM:SS; null or anything else gives 0 |

**Returns:** `int`

## Called from

- [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md)

