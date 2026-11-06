# contractVisibilitySql()

" AND (...)" keeping only contracts in a company this analyst can reach, for a query over `contracts` aliased `$c` - every company they may see, whatever the switcher says. For search, previews, links and anything else that is not the contracts list itself. ['', []] on a single-company install.

```php
function contractVisibilitySql(PDO $conn, int $analystId, string $c = 'c'): array
```

Defined in [includes/contract_party.php](/code/files/includes/contract_party.php.md), line 91.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$c` | `string` | `'c'` | the alias the calling query gives the contracts table (trusted - put straight into SQL) |

**Returns:** array{0:string,1:array}

## Calls

[allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [contractTenantReady()](/code/functions/contractTenantReady.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/assets/leases/options.php](/code/files/api/assets/leases/options.php.md)
- [api/assets/search_linkable_contracts.php](/code/files/api/assets/search_linkable_contracts.php.md)
- [api/contracts/get_contract.php](/code/files/api/contracts/get_contract.php.md)
- [api/system/global_search.php](/code/files/api/system/global_search.php.md)
- [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) - in [contractsForAsset()](/code/functions/contractsForAsset.md)
- [includes/contract_party.php](/code/files/includes/contract_party.php.md) - in [contractCanView()](/code/functions/contractCanView.md)
- [includes/contract_report.php](/code/files/includes/contract_report.php.md) - in [contractReportLoad()](/code/functions/contractReportLoad.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentEntityRegistry()](/code/functions/documentEntityRegistry.md)
- [includes/domains/read.php](/code/files/includes/domains/read.php.md) - in [domainDetail()](/code/functions/domainDetail.md), [domainApiLookups()](/code/functions/domainApiLookups.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleContracts()](/code/functions/peopleContracts.md), [peopleSupplierRows()](/code/functions/peopleSupplierRows.md), [peopleSupplierContracts()](/code/functions/peopleSupplierContracts.md)
- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreviewContract()](/code/functions/recordPreviewContract.md)
- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotContractScope()](/code/functions/warbotContractScope.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)

