# oversizeMbWords()

Formats a byte count as megabytes for messages - whole numbers without a decimal ("10 MB"), otherwise one place ("2.5 MB").

```php
function oversizeMbWords(int $bytes): string
```

Defined in [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md), line 214.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$bytes` | `int` |  | the size in bytes |

**Returns:** `string`

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [oversizeLogStep()](/code/functions/oversizeLogStep.md), [oversizeSummary()](/code/functions/oversizeSummary.md)
- [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md) - in [oversizePlan()](/code/functions/oversizePlan.md), [oversizeNotify()](/code/functions/oversizeNotify.md)
- [system/security/index.php](/code/files/system/security/index.php.md)

