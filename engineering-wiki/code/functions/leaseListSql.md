# leaseListSql()

The Leases list and Due back: follow the company switcher, like Assets.

```php
function leaseListSql(PDO $conn, int $analystId, string $l = 'l'): array
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 64.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$l` | `string` | `'l'` | the leases table's alias in the calling query |

**Returns:** `array`

## Calls

[activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseList()](/code/functions/leaseList.md), [leaseDueBack()](/code/functions/leaseDueBack.md)

