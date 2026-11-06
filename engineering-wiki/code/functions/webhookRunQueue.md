# webhookRunQueue()

Deliver up to `$limit` due rows. Claims each atomically (so overlapping worker runs never double-send). Returns counts.

```php
function webhookRunQueue(PDO $conn, int $limit = 50): array
```

Defined in [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), line 402.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$limit` | `int` | `50` | the most due deliveries to attempt in this run |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 405 | `webhookRunQueue` | read [webhook_deliveries](/code/tables/webhook_deliveries.md) |  |
| 413 | `webhookRunQueue` | **update** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`status`, `updated_datetime`) |  |
| 414 | `webhookRunQueue` | read [webhook_deliveries](/code/tables/webhook_deliveries.md) |  |

## Calls

[webhookAttemptDelivery()](/code/functions/webhookAttemptDelivery.md)

## Called from

- [cron/webhook_deliveries.php](/code/files/cron/webhook_deliveries.php.md)

