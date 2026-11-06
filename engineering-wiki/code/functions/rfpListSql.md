# rfpListSql()

The RFP LIST: the active company, or every company the analyst can see under "All companies".

```php
function rfpListSql(PDO $conn, int $analystId, string $r = 'r'): array
```

Defined in [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md), line 50.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$r` | `string` | `'r'` | the alias the calling query gives the rfps table |

**Returns:** `array`

## Calls

[activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [rfpTenantReady()](/code/functions/rfpTenantReady.md)

## Called from

- [api/rfp-builder/get_rfps.php](/code/files/api/rfp-builder/get_rfps.php.md)

