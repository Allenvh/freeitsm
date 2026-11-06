# contractAssetReachSql()

" AND ..." keeping assets (alias a) in companies this analyst can reach. ['', []] with one company.

```php
function contractAssetReachSql(PDO $conn, int $analystId): array
```

Defined in [includes/contract_assets.php](/code/files/includes/contract_assets.php.md), line 56.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) - in [contractAssetCanReach()](/code/functions/contractAssetCanReach.md), [contractAssetsFor()](/code/functions/contractAssetsFor.md)

