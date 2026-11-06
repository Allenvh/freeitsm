# contractTenantReady()

True once Database Verification has added contracts.tenant_id.

```php
function contractTenantReady(PDO $conn): bool
```

Defined in [includes/contract_party.php](/code/files/includes/contract_party.php.md), line 32.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 | `contractTenantReady` | read [contracts](/code/tables/contracts.md) |  |

## Called from

- [api/assets/leases/options.php](/code/files/api/assets/leases/options.php.md)
- [api/assets/search_linkable_contracts.php](/code/files/api/assets/search_linkable_contracts.php.md)
- [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md)
- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractsList()](/code/functions/apiContractsList.md)
- [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) - in [contractAssetLink()](/code/functions/contractAssetLink.md), [contractAssetSearch()](/code/functions/contractAssetSearch.md)
- [includes/contract_party.php](/code/files/includes/contract_party.php.md) - in [contractTenantOf()](/code/functions/contractTenantOf.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [contractVisibilitySqlForScope()](/code/functions/contractVisibilitySqlForScope.md), [contractListSql()](/code/functions/contractListSql.md), [contractOwnerSql()](/code/functions/contractOwnerSql.md)
- [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) - in `ContractsService::ownerTenant`, `ContractsService::assertNumberFree`, `ContractsService::writeTenant`
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::assertContractVisible`

