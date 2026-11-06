# webhookPayloadRetentionDays()

Days to keep payload bodies. 0 = never store, -1 = forever.

```php
function webhookPayloadRetentionDays(PDO $conn): int
```

Defined in [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), line 131.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 133 | `webhookPayloadRetentionDays` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md) - in [webhookPurgePayloads()](/code/functions/webhookPurgePayloads.md), [webhookScrubIfNoRetention()](/code/functions/webhookScrubIfNoRetention.md)
- [system/webhooks/index.php](/code/files/system/webhooks/index.php.md)

