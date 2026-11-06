# oversizePlan()

Decide what to do with one email's attachments.

]
An attachment over the per-attachment limit is always removed. Then, if what is
left still comes to more than the total limit, strip_order decides what else goes.

```php
function oversizePlan(array $atts, array $rules): array
```

Defined in [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md), line 170.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$atts` | `array` |  | [['name' => string, 'size' => int bytes], ...] in message order |
| `$rules` | `array` |  | the mailbox's rules from `oversizeRules()` - max_bytes, max_total_bytes, mode, strip_order |

**Returns:** array [ 'over'   => bool          anything breaks a limit 'reject' => bool          the whole email is to be refused (mode = reject) 'strip'  => [index => reason]   attachments to leave out (mode = strip) 'total'  => int           total bytes of all attachments

## Calls

[oversizeMbWords()](/code/functions/oversizeMbWords.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)

