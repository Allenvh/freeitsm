# rfpTenantReady()

True once Database Verification has added rfps.tenant_id.

```php
function rfpTenantReady(PDO $conn): bool
```

Defined in [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md), line 26.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 | `rfpTenantReady` | read [rfps](/code/tables/rfps.md) |  |

## Called from

- [api/rfp-builder/get_rfps.php](/code/files/api/rfp-builder/get_rfps.php.md)
- [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md)
- [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) - in [rfpVisibilitySql()](/code/functions/rfpVisibilitySql.md), [rfpListSql()](/code/functions/rfpListSql.md), [rfpTenantOf()](/code/functions/rfpTenantOf.md), [rfpGate()](/code/functions/rfpGate.md)

