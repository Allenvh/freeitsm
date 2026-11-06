# analystDeactivate()

Deactivate: no sign-in, no access, API keys refused - and every name kept. Reversible by ticking Active again. Moving their open work is a separate, optional step (analystReassignLive()), done first by the screen.

```php
function analystDeactivate(PDO $conn, ActorContext $ctx, int $analystId): void
```

Defined in [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), line 585.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | the administrator doing it |
| `$analystId` | `int` |  | the analyst leaving |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 587 | `analystDeactivate` | **update** [analysts](/code/tables/analysts.md) (`is_active`, `last_modified_datetime`) |  |

## Calls

[analystLcAssertActionable()](/code/functions/analystLcAssertActionable.md), [analystLcLog()](/code/functions/analystLcLog.md)

## Called from

- [api/tickets/analyst_leaving.php](/code/files/api/tickets/analyst_leaving.php.md)

