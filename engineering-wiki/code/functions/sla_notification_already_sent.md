# sla_notification_already_sent()

Check if we've already fired this notification for (ticket, target, trigger).

```php
function sla_notification_already_sent(
    PDO $conn,
    int $ticketId,
    string $targetType,
    string $trigger
): bool
```

Defined in [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md), line 389.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$targetType` | `string` |  | 'response' or 'resolution' |
| `$trigger` | `string` |  | 'warning' or 'breach' |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 390 | `sla_notification_already_sent` | read [sla_notifications_sent](/code/tables/sla_notifications_sent.md) |  |

## Called from

- [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) - in [sla_run_breach_check()](/code/functions/sla_run_breach_check.md)

