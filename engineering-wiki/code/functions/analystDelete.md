# analystDelete()

Delete: only for an analyst nothing in history or setup refers to (once their open work has been moved). Personal settings and sign-in material go with them, in one transaction. Anything else refers to them -> refused, with the reason, so the screen can offer Deactivate or Anonymise instead.

```php
function analystDelete(PDO $conn, ActorContext $ctx, int $analystId): void
```

Defined in [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), line 654.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | the administrator doing it |
| `$analystId` | `int` |  | the analyst to delete |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 666 | `analystDelete` | **delete** [analysts](/code/tables/analysts.md) |  |

## Calls

[analystFootprint()](/code/functions/analystFootprint.md), [analystLcAssertActionable()](/code/functions/analystLcAssertActionable.md), [analystLcLog()](/code/functions/analystLcLog.md), [analystRemovePersonal()](/code/functions/analystRemovePersonal.md)

## Called from

- [api/tickets/analyst_leaving.php](/code/files/api/tickets/analyst_leaving.php.md)
- [api/tickets/delete_analyst.php](/code/files/api/tickets/delete_analyst.php.md)

