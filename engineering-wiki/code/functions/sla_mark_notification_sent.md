# sla_mark_notification_sent()

Records that an SLA notification went out for a ticket, so it is not sent twice.

```php
function sla_mark_notification_sent(
    PDO $conn,
    int $ticketId,
    string $targetType,
    string $trigger,
    array $recipients
): void
```

Defined in [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md), line 402.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$targetType` | `string` |  | 'response' or 'resolution' |
| `$trigger` | `string` |  | 'warning' or 'breach' |
| `$recipients` | `array` |  | the addresses it went to, stored comma-separated |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 403 | `sla_mark_notification_sent` | **insert** [sla_notifications_sent](/code/tables/sla_notifications_sent.md) (`ticket_id`, `target_type`, `trigger_type`, `recipients`) |  |

## Called from

- [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) - in [sla_run_breach_check()](/code/functions/sla_run_breach_check.md)

