# costCentreRef()

{id, code, name} for display and the API, or null.

```php
function costCentreRef(PDO $conn, $id): ?array
```

Defined in [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md), line 118.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` |  |  | a cost_centres.id as stored on a record; null or 0 gives null |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 121 | `costCentreRef` | read [cost_centres](/code/tables/cost_centres.md) |  |

## Called from

- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseGet()](/code/functions/leaseGet.md)
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::auditDisplay`

