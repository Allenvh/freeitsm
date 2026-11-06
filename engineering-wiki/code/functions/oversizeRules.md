# oversizeRules()

The rules for one mailbox: the install defaults, overridden by the mailbox's own oversize_policy when it has one. Limits in BYTES (total 0 = no limit).

```php
function oversizeRules(PDO $conn, ?array $mailbox = null): array
```

Defined in [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md), line 95.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailbox` | `?array` | `null` | the target_mailboxes row, whose oversize_policy JSON overrides the defaults; null for defaults only |

**Returns:** `array`

## Calls

[attachmentMaxBytes()](/code/functions/attachmentMaxBytes.md), [attachmentMb()](/code/functions/attachmentMb.md), [attachmentSetting()](/code/functions/attachmentSetting.md), [oversizeDefaultReplyText()](/code/functions/oversizeDefaultReplyText.md), [oversizeNormalisePolicy()](/code/functions/oversizeNormalisePolicy.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)
- [api/tickets/oversize_settings.php](/code/files/api/tickets/oversize_settings.php.md)

