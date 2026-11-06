# analystLiveGroups()

The open work that can be handed on, one group per kind of thing, each moved through its module's own service. 'count' is how many rows the group holds.

```php
function analystLiveGroups(PDO $conn, int $analystId): array
```

Defined in [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), line 364.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array<string,array{label:string,count:int}>

## Calls

[analystLcIds()](/code/functions/analystLcIds.md), [analystReferenceRegistry()](/code/functions/analystReferenceRegistry.md)

## Called from

- [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) - in [analystFootprint()](/code/functions/analystFootprint.md), [analystReassignLive()](/code/functions/analystReassignLive.md)

