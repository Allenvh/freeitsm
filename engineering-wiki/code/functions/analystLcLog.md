# analystLcLog()

Record the action in the system log - by id only. Writing the name here would keep, in the log of an anonymisation, the very thing it removed.

```php
function analystLcLog(PDO $conn, ActorContext $ctx, int $analystId, string $what): void
```

Defined in [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), line 706.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | the administrator doing it |
| `$analystId` | `int` |  | the analyst acted on |
| `$what` | `string` |  | 'deactivated' \| 'anonymised' \| 'deleted' |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 709 | `analystLcLog` | **insert** [system_logs](/code/tables/system_logs.md) (`log_type`, `analyst_id`, `details`, `created_datetime`) |  |

## Called from

- [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) - in [analystDeactivate()](/code/functions/analystDeactivate.md), [analystAnonymise()](/code/functions/analystAnonymise.md), [analystDelete()](/code/functions/analystDelete.md)

