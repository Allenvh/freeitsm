# webhookReplay()

Reset a delivery so the worker sends it again (the "Replay" button).

```php
function webhookReplay(PDO $conn, int $id): bool
```

Defined in [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), line 432.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the webhook_deliveries id to send again |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 434 | `webhookReplay` | **update** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`status`, `next_attempt_at`, `last_error`, `updated_datetime`) |  |

## Called from

- [api/workflow/deliveries_replay.php](/code/files/api/workflow/deliveries_replay.php.md)

