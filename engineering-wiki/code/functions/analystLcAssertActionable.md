# analystLcAssertActionable()

The checks every action shares: the analyst exists, it is not you, and it is not the last active administrator.

```php
function analystLcAssertActionable(PDO $conn, ActorContext $ctx, int $analystId): void
```

Defined in [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), line 682.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | the administrator doing it |
| `$analystId` | `int` |  | the analyst being acted on |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 686 | `analystLcAssertActionable` | read [analysts](/code/tables/analysts.md) |  |
| 691 | `analystLcAssertActionable` | read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/tickets/analyst_leaving.php](/code/files/api/tickets/analyst_leaving.php.md)
- [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) - in [analystDeactivate()](/code/functions/analystDeactivate.md), [analystAnonymise()](/code/functions/analystAnonymise.md), [analystDelete()](/code/functions/analystDelete.md)

