# webhookEnqueue()

Queue a webhook for asynchronous delivery. `$d` keys: workflow_id, execution_id, preset, url, method, headers (array of header lines), body (string), max_attempts. Returns the new delivery id.

```php
function webhookEnqueue(PDO $conn, array $d): int
```

Defined in [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), line 263.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$d` | `array` |  | the delivery: workflow_id, execution_id, preset, url, method, headers, body, max_attempts (default 6) |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 265 | `webhookEnqueue` | **insert** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`workflow_id`, `execution_id`, `preset`, `url`, `method`, `request_headers`, `request_body`, `status`, `attempts`, `max_attempts`, `next_attempt_at`, `created_datetime`) |  |

## Calls

[webhookEncrypt()](/code/functions/webhookEncrypt.md)

## Called from

- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_send_webhook`

