# api/contracts/search_contract_assets.php

> API endpoint - 41 lines

API: Assets that could be added to a contract (discussion #106). GET ?contract_id=&q=

Scoped to what the analyst can reach, and excludes what is already linked —
offering something already attached is only a way to produce a no-op.

## The endpoint

| | |
|---|---|
| URL | `api/contracts/search_contract_assets.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET contract_id`, `GET q` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/contract_assets.php](/code/files/includes/contract_assets.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [contractAssetSearch()](/code/functions/contractAssetSearch.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

