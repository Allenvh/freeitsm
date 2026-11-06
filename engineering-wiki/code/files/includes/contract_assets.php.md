# includes/contract_assets.php

> PHP - 289 lines

Assets covered by a contract (discussion #106, dschipfel).

Both directions of one link: which equipment a contract covers, and which
contracts cover a piece of equipment. A mobile service agreement and the
handsets and SIMs on it is the worked example from the request.

⚠️ WHY THIS FILE EXISTS RATHER THAN FOUR ENDPOINTS DOING IT THEMSELVES.
Every read of the asset side has to be filtered to the companies the analyst
can reach. Four endpoints filtering separately is four chances to forget;
forgetting once puts one customer's equipment on another customer's contract
page, and it looks exactly like a working feature.

🔑 ONE COMPANY PER LINK (3.5.0). Contracts belong to a company now, and a
contract covers only assets of its OWN company - checked on every link, even
for an analyst who can reach both companies (that is precisely the analyst
who could otherwise wire two clients together). The picker offers the
contract's company's assets, so the rule is discoverable rather than an error.

🔑 A scoped list is not a gate. `contractAssetCanReach()` is called again on
every write and every delete, because the id in a POST body did not
necessarily come from the list we rendered.

⚠️ NO HIDDEN COUNT. When a contract covers ten assets and the reader may see
three, they are shown three and told nothing about the other seven. A count
of what you cannot see is a leak of its own, and this is the mistake the
requester picker made: it scoped the list and not the count.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [contractAssetCanReach()](/code/functions/contractAssetCanReach.md) | 41 | Can this analyst reach this asset at all? |
| [contractAssetReachSql()](/code/functions/contractAssetReachSql.md) | 56 | " AND ..." keeping assets (alias a) in companies this analyst can reach. ['', []] with one company. |
| [contractAssetSelect()](/code/functions/contractAssetSelect.md) | 62 | The columns every asset row in this feature shows. One home, so the two directions match. |
| [contractAssetsFor()](/code/functions/contractAssetsFor.md) | 79 | Equipment covered by a contract, filtered to what this analyst may see. |
| [contractsForAsset()](/code/functions/contractsForAsset.md) | 103 | Contracts covering an asset. |
| [contractAssetLink()](/code/functions/contractAssetLink.md) | 152 | Link an asset to a contract. Returns the new link id. |
| [contractAssetSetReference()](/code/functions/contractAssetSetReference.md) | 196 | Change the note on an existing link. |
| [contractAssetUnlink()](/code/functions/contractAssetUnlink.md) | 214 | Remove a link. The asset and the contract both survive it — this only ever says "this equipment is not on that agreement". |
| [contractAssetLoad()](/code/functions/contractAssetLoad.md) | 226 | One link, but only if this analyst can reach the asset on it. |
| [contractAssetSearch()](/code/functions/contractAssetSearch.md) | 252 | Assets this analyst could add to a contract, matching `$q`. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 | `contractAssetCanReach` | read [assets](/code/tables/assets.md) |  |
| 64 | `contractAssetSelect` | read [contract_assets](/code/tables/contract_assets.md), read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md), read [asset_locations](/code/tables/asset_locations.md) |  |
| 111 | `contractsForAsset` | read [contract_assets](/code/tables/contract_assets.md), read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md), read [contract_statuses](/code/tables/contract_statuses.md) |  |
| 166 | `contractAssetLink` | read [assets](/code/tables/assets.md) |  |
| 179 | `contractAssetLink` | **insert** [contract_assets](/code/tables/contract_assets.md) (`contract_id`, `asset_id`, `reference`, `linked_by_id`, `created_datetime`) |  |
| 184 | `contractAssetLink` | read [contract_assets](/code/tables/contract_assets.md) |  |
| 203 | `contractAssetSetReference` | **update** [contract_assets](/code/tables/contract_assets.md) (`reference`) |  |
| 217 | `contractAssetUnlink` | **delete** [contract_assets](/code/tables/contract_assets.md) |  |
| 228 | `contractAssetLoad` | read [contract_assets](/code/tables/contract_assets.md) |  |
| 263 | `contractAssetSearch` | read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md), read [asset_locations](/code/tables/asset_locations.md), read [contract_assets](/code/tables/contract_assets.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md)

**Included by:** [api/assets/get_asset_contracts.php](/code/files/api/assets/get_asset_contracts.php.md), [api/assets/search_linkable_contracts.php](/code/files/api/assets/search_linkable_contracts.php.md), [api/contracts/delete_contract_asset.php](/code/files/api/contracts/delete_contract_asset.php.md), [api/contracts/get_contract_assets.php](/code/files/api/contracts/get_contract_assets.php.md), [api/contracts/save_contract_asset.php](/code/files/api/contracts/save_contract_asset.php.md), [api/contracts/search_contract_assets.php](/code/files/api/contracts/search_contract_assets.php.md), [includes/contract_report.php](/code/files/includes/contract_report.php.md)

**Calls:** [allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [contractCanView()](/code/functions/contractCanView.md), [contractPartyLabel()](/code/functions/contractPartyLabel.md), [contractPartySql()](/code/functions/contractPartySql.md), [contractTenantOf()](/code/functions/contractTenantOf.md), [contractTenantReady()](/code/functions/contractTenantReady.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [tenantScopeSqlFor()](/code/functions/tenantScopeSqlFor.md)

