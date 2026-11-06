# rfpVisibilitySql()

" AND ..." keeping RFPs (alias `$r`) in companies this analyst can reach.

```php
function rfpVisibilitySql(PDO $conn, int $analystId, string $r = 'r'): array
```

Defined in [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md), line 39.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$r` | `string` | `'r'` | the alias the calling query gives the rfps table |

**Returns:** `array`

## Calls

[allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [rfpTenantReady()](/code/functions/rfpTenantReady.md)

## Called from

- [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) - in [rfpCanView()](/code/functions/rfpCanView.md)

