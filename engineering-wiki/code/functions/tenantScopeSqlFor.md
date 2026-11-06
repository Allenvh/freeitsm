# tenantScopeSqlFor()

`activeTenantFilter()`'s clause for ONE NAMED company instead of the active one. Same Default-owns-NULL rule. ['', []] on a single-company install.

```php
function tenantScopeSqlFor(PDO $conn, int $tenantId, string $qualified): array
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 900.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$qualified` | `string` |  | the already-qualified column, e.g. "l.tenant_id" |

**Returns:** `array`

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/assets/leases/asset_search.php](/code/files/api/assets/leases/asset_search.php.md)
- [api/assets/leases/options.php](/code/files/api/assets/leases/options.php.md)
- [api/assets/search_linkable_contracts.php](/code/files/api/assets/search_linkable_contracts.php.md)
- [includes/asset_locations.php](/code/files/includes/asset_locations.php.md) - in [assetLocationScope()](/code/functions/assetLocationScope.md)
- [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) - in [contractAssetSearch()](/code/functions/contractAssetSearch.md)

