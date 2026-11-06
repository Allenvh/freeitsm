# webhookReplayBlockedReason()

Why a replay was refused — so the UI can say "the payload was purged by your retention setting" rather than a generic failure. Replay re-sends the STORED body, so a purged row has nothing to send and must not be re-queued (that would POST an empty payload to a live endpoint).

```php
function webhookReplayBlockedReason(PDO $conn, int $id): ?string
```

Defined in [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), line 450.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the webhook_deliveries id the replay was refused for |

**Returns:** `?string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 451 | `webhookReplayBlockedReason` | read [webhook_deliveries](/code/tables/webhook_deliveries.md) |  |

## Called from

- [api/workflow/deliveries_replay.php](/code/files/api/workflow/deliveries_replay.php.md)

