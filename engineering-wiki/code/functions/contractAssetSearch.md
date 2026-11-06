# contractAssetSearch()

Assets this analyst could add to a contract, matching `$q`.

Searches location as well as hostname, model, serial and tag, for the same
reason the ticket picker does: nobody knows the hostname of a meeting-room
TV, and nobody knows the service tag of a SIM card. They know what it is and
where it lives.

```php
function contractAssetSearch(
    PDO $conn,
    int $analystId,
    int $contractId,
    string $q,
    int $limit = 25
): array
```

Defined in [includes/contract_assets.php](/code/files/includes/contract_assets.php.md), line 252.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$contractId` | `int` |  | contracts.id the assets would be added to; its company bounds the search |
| `$q` | `string` |  | the search text, matched as a substring; empty lists everything not yet linked |
| `$limit` | `int` | `25` | most rows to return |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 263 | `contractAssetSearch` | read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md), read [asset_locations](/code/tables/asset_locations.md), read [contract_assets](/code/tables/contract_assets.md) |  |

## Calls

[contractCanView()](/code/functions/contractCanView.md), [contractTenantOf()](/code/functions/contractTenantOf.md), [contractTenantReady()](/code/functions/contractTenantReady.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [tenantScopeSqlFor()](/code/functions/tenantScopeSqlFor.md)

## Called from

- [api/contracts/search_contract_assets.php](/code/files/api/contracts/search_contract_assets.php.md)

