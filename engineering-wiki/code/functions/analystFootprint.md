# analystFootprint()

Everything the screen needs to explain what Deactivate, Anonymise and Delete would do to this analyst.

```php
function analystFootprint(PDO $conn, int $analystId): array
```

Defined in [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), line 435.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array{analyst:array,live:array,setup:array,history:int,history_top:array,personal:int,secret:int,can_delete:bool,is_last_admin:bool}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 436 | `analystFootprint` | read [analysts](/code/tables/analysts.md) |  |
| 466 | `analystFootprint` | read [analysts](/code/tables/analysts.md) |  |

## Calls

[analystLcCount()](/code/functions/analystLcCount.md), [analystLiveGroups()](/code/functions/analystLiveGroups.md), [analystReferenceRegistry()](/code/functions/analystReferenceRegistry.md), [analystSetupLabels()](/code/functions/analystSetupLabels.md)

## Called from

- [api/tickets/analyst_leaving.php](/code/files/api/tickets/analyst_leaving.php.md)
- [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) - in [analystDelete()](/code/functions/analystDelete.md)

