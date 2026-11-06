# webhookAttemptDelivery()

Attempt to deliver one already-claimed row (status must be 'delivering'). Updates the row with the outcome and returns 'delivered' | 'failed' | 'dead'.

```php
function webhookAttemptDelivery(PDO $conn, array $row): string
```

Defined in [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), line 326.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | the webhook_deliveries row, already claimed (status 'delivering') |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 346 | `webhookAttemptDelivery` | **update** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`status`, `attempts`, `last_status_code`, `last_error`, `response_snippet`, `delivered_datetime`, `next_attempt_at`, `updated_datetime`) |  |
| 359 | `webhookAttemptDelivery` | **update** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`status`, `attempts`, `last_status_code`, `last_error`, `response_snippet`, `next_attempt_at`, `updated_datetime`) |  |
| 368 | `webhookAttemptDelivery` | **update** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`status`, `attempts`, `last_status_code`, `last_error`, `response_snippet`, `next_attempt_at`, `updated_datetime`) |  |

## Calls

[webhookBackoffSeconds()](/code/functions/webhookBackoffSeconds.md), [webhookDecrypt()](/code/functions/webhookDecrypt.md), [webhookHttpSend()](/code/functions/webhookHttpSend.md), [webhookScrubIfNoRetention()](/code/functions/webhookScrubIfNoRetention.md)

## Called from

- [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md) - in [webhookRunQueue()](/code/functions/webhookRunQueue.md)

