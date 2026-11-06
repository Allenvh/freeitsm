# leaseDefaultOutcomeId()

The outcome a newly added asset starts with: the default, else the first active kind.

```php
function leaseDefaultOutcomeId(PDO $conn): ?int
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 167.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 169 | `leaseDefaultOutcomeId` | read [lease_outcomes](/code/tables/lease_outcomes.md) |  |

## Called from

- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseAssetsAdd()](/code/functions/leaseAssetsAdd.md)

