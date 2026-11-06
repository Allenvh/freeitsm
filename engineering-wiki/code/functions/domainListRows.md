# domainListRows()

The register, scoped to what this analyst may see.

```php
function domainListRows(PDO $conn, int $analystId, array $f = []): array
```

Defined in [includes/domains/read.php](/code/files/includes/domains/read.php.md), line 71.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$f` | `array` | `[]` | optional filters: q, status_id, purpose, owner_analyst_id, registrar_supplier_id, expiring_days, attention (bool), tag |

**Returns:** `array`

## Calls

[activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [domainListSelect()](/code/functions/domainListSelect.md)

## Called from

- [api/domains/dashboard.php](/code/files/api/domains/dashboard.php.md)
- [api/domains/list.php](/code/files/api/domains/list.php.md)

