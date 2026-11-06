# attachmentMb()

A whole number of MB from a setting, clamped to something sane (0 allowed only where it means "no limit").

```php
function attachmentMb($v, int $default, bool $zeroMeansNone): int
```

Defined in [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md), line 69.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the raw setting or posted value, in MB (string, int or null) |
| `$default` | `int` |  | returned when `$v` is empty or not numeric, and for 0 or less unless `$zeroMeansNone` |
| `$zeroMeansNone` | `bool` |  | true when 0 means "no limit" (the per-email total); 0 or less then returns 0 |

**Returns:** `int`

## Called from

- [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md) - in [attachmentMaxBytes()](/code/functions/attachmentMaxBytes.md), [oversizeRules()](/code/functions/oversizeRules.md), [oversizeNormalisePolicy()](/code/functions/oversizeNormalisePolicy.md)

