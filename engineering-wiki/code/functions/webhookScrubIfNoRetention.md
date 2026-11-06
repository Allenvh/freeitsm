# webhookScrubIfNoRetention()

Retention "don't store": the payload had to exist long enough for the worker to send it, so we scrub it the instant the delivery settles rather than waiting for the next purge sweep. A no-op on every other retention setting.

```php
function webhookScrubIfNoRetention(PDO $conn, int $id): void
```

Defined in [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), line 383.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the webhook_deliveries id |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 387 | `webhookScrubIfNoRetention` | **update** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`request_body`, `response_snippet`, `payload_purged`) |  |

## Calls

[webhookPayloadRetentionDays()](/code/functions/webhookPayloadRetentionDays.md)

## Called from

- [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md) - in [webhookAttemptDelivery()](/code/functions/webhookAttemptDelivery.md)

