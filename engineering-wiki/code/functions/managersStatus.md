# managersStatus()

Can the rules run at all on this install - and if not, why not.

```php
function managersStatus(PDO $conn): array
```

Defined in [api/system/managers.php](/code/files/api/system/managers.php.md), line 43.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 48 | `managersStatus` | read [manager_grants](/code/tables/manager_grants.md) |  |

## Calls

[managersActive()](/code/functions/managersActive.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md), [ticketViewsReady()](/code/functions/ticketViewsReady.md)

## Called from

- [api/system/managers.php](/code/files/api/system/managers.php.md)

