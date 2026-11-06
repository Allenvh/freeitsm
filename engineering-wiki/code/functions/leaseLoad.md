# leaseLoad()

A lease row the analyst may see, or a not_found ServiceError (never reveals another company's).

```php
function leaseLoad(PDO $conn, int $analystId, int $leaseId): array
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 88.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$leaseId` | `int` |  | the leases id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 91 | `leaseLoad` | read [leases](/code/tables/leases.md) |  |

## Calls

[leaseVisibilitySql()](/code/functions/leaseVisibilitySql.md)

## Called from

- [api/assets/leases/asset_search.php](/code/files/api/assets/leases/asset_search.php.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseLoadSchedule()](/code/functions/leaseLoadSchedule.md), [leaseSave()](/code/functions/leaseSave.md), [leaseDelete()](/code/functions/leaseDelete.md), [leaseGet()](/code/functions/leaseGet.md), [leaseScheduleSave()](/code/functions/leaseScheduleSave.md)

