# oversizePolicyForStorage()

A normalised policy in the shape stored on a mailbox (MB, not bytes) and read back by `oversizeNormalisePolicy()`.

```php
function oversizePolicyForStorage(array $p): array
```

Defined in [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md), line 349.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `array` |  | a policy as returned by `oversizeNormalisePolicy()` - limits in bytes |

**Returns:** `array`

## Called from

- [api/tickets/save_mailbox.php](/code/files/api/tickets/save_mailbox.php.md)

