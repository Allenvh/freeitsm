# oversizeLogStep()

The processing-log step describing the size check.

```php
function oversizeLogStep(array $atts, array $plan, array $rules, string $outcome): array
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 1489.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$atts` | `array` |  | the file attachments as measured: [['name' => string, 'size' => bytes], ...] |
| `$plan` | `array` |  | the decision from `oversizePlan()` - total and reasons are read |
| `$rules` | `array` |  | the mailbox's rules from `oversizeRules()` |
| `$outcome` | `string` |  | what happened to the email, e.g. 'rejected' or 'stripped' |

**Returns:** `array`

## Calls

[oversizeMbWords()](/code/functions/oversizeMbWords.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)

