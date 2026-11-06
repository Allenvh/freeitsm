# leaseCanView()

True when the lease is in a company the analyst may read.

```php
function leaseCanView(PDO $conn, int $analystId, int $leaseId): bool
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 75.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$leaseId` | `int` |  | the leases id |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 78 | `leaseCanView` | read [leases](/code/tables/leases.md) |  |

## Calls

[leaseVisibilitySql()](/code/functions/leaseVisibilitySql.md)

## Called from

- [asset-management/leases/view.php](/code/files/asset-management/leases/view.php.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentEntityRegistry()](/code/functions/documentEntityRegistry.md)

