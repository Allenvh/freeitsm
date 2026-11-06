# oversizeNormalisePolicy()

A policy as posted by a settings screen (MB, strings), made safe. Used for the mailbox override on save and on read, so a hand-edited row cannot break a check.

```php
function oversizeNormalisePolicy(array $p, ?array $fallback = null): array
```

Defined in [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md), line 123.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `array` |  | the policy as posted or stored - max_mb, max_total_mb, mode, strip_order, alert, alert_to, autoreply... |
| `$fallback` | `?array` | `null` | rules to take max_bytes and max_total_bytes from when `$p` has none; null uses 10 MB and no total |

**Returns:** `array`

## Calls

[attachmentMb()](/code/functions/attachmentMb.md), [oversizeCleanAddresses()](/code/functions/oversizeCleanAddresses.md), [oversizeDefaultReplyText()](/code/functions/oversizeDefaultReplyText.md)

## Called from

- [api/tickets/oversize_settings.php](/code/files/api/tickets/oversize_settings.php.md)
- [api/tickets/save_mailbox.php](/code/files/api/tickets/save_mailbox.php.md)
- [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md) - in [oversizeRules()](/code/functions/oversizeRules.md)

