# api/assets/search_linkable_contracts.php

> API endpoint - 104 lines

API: Contracts an asset could be added to (discussion #106). GET ?asset_id=&q=

The mirror of api/contracts/search_contract_assets.php, for linking from the
equipment's side. Somebody standing on a new handset should not have to go and
find the agreement first.

⚠️ Gated on BOTH modules. Assets, because it is the asset's page; contracts,
because it lists contract numbers and titles. An analyst with equipment access
and no contracts access must not be able to read the contract register through
a search box on an asset.

Only the ASSET's own company's contracts are offered (3.5.0): a contract covers
assets of its own company and no other. See includes/contract_assets.php.

## The endpoint

| | |
|---|---|
| URL | `api/assets/search_linkable_contracts.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET asset_id`, `GET q` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 |  | read [assets](/code/tables/assets.md) |  |
| 62 |  | read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md), read [contract_assets](/code/tables/contract_assets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/contract_assets.php](/code/files/includes/contract_assets.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [contractAssetCanReach()](/code/functions/contractAssetCanReach.md), [contractPartyReady()](/code/functions/contractPartyReady.md), [contractPartySql()](/code/functions/contractPartySql.md), [contractTenantReady()](/code/functions/contractTenantReady.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [tenantScopeSqlFor()](/code/functions/tenantScopeSqlFor.md)

