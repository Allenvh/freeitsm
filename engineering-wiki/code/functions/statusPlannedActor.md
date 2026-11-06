# statusPlannedActor()

The person who scheduled it, as the actor of the incident's history.

```php
function statusPlannedActor(PDO $conn, array $p): ActorContext
```

Defined in [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md), line 262.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$p` | `array` |  | the status_planned row; its created_by_id names the analyst |

**Returns:** `ActorContext`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 267 | `statusPlannedActor` | read [analysts](/code/tables/analysts.md) |  |

## Called from

- [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md) - in [statusPlannedStart()](/code/functions/statusPlannedStart.md), [statusPlannedFinish()](/code/functions/statusPlannedFinish.md)

