# oversizeCleanAddresses()

"a@x.com; b@y.com, junk" -> "a@x.com, b@y.com" - valid addresses only.

```php
function oversizeCleanAddresses(string $raw): string
```

Defined in [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md), line 146.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | addresses as typed, separated by commas, semicolons or spaces |

**Returns:** `string`

## Called from

- [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md) - in [oversizeNormalisePolicy()](/code/functions/oversizeNormalisePolicy.md)

