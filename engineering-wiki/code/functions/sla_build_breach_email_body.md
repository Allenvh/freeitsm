# sla_build_breach_email_body()

Render the HTML body for a breach / warning email.

```php
function sla_build_breach_email_body(
    array $merge,
    array $target,
    string $targetLabel,
    string $headline,
    string $trigger
): string
```

Defined in [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md), line 339.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$merge` | `array` |  | the ticket merge data from `buildTicketMergeData()` |
| `$target` | `array` |  | that target's state: target_minutes, elapsed_minutes, remaining_minutes |
| `$targetLabel` | `string` |  | 'Response' or 'Resolution' |
| `$headline` | `string` |  | the banner text - trusted HTML, written into the email as it is |
| `$trigger` | `string` |  | 'warning' or 'breach'; sets the banner colour and the status line |

**Returns:** `string`

## Calls

[sla_format_minutes()](/code/functions/sla_format_minutes.md)

## Called from

- [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) - in [sla_send_breach_email()](/code/functions/sla_send_breach_email.md)

