# tenantCount()

Number of tenants on this install. Returns 1 if the table isn't ready.

⚠️ Same rule as above: the COUNT can itself fail transiently, and answering 1 would
switch every guard off. A count we cannot take is reported as 2 — "assume more than
one company" — which engages the guards rather than bypassing them. Uncached, for
the same reason.

```php
function tenantCount(PDO $conn): int
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 104.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 111 | `tenantCount` | read [tenants](/code/tables/tenants.md) |  |

## Calls

[tenancyTablesReady()](/code/functions/tenancyTablesReady.md)

## Called from

- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [isMultiTenant()](/code/functions/isMultiTenant.md)

