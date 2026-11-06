# webhookPurgePayloads()

Blank the stored request/response bodies of deliveries past the retention window. Returns how many rows were scrubbed. Called by the cron worker after each queue run, so retention is enforced without another scheduled task.

```php
function webhookPurgePayloads(PDO $conn): int
```

Defined in [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), line 148.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 156 | `webhookPurgePayloads` | **update** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`request_body`, `response_snippet`, `payload_purged`, `updated_datetime`) |  |

## Calls

[webhookPayloadRetentionDays()](/code/functions/webhookPayloadRetentionDays.md)

## Called from

- [api/workflow/save_payload_retention.php](/code/files/api/workflow/save_payload_retention.php.md)
- [cron/webhook_deliveries.php](/code/files/cron/webhook_deliveries.php.md)

