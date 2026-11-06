# api/contracts/get_contract_assets.php

> API endpoint - 37 lines

API: Equipment covered by a contract (discussion #106). GET ?contract_id=

The list is filtered to the companies this analyst can reach — see the header
of includes/contract_assets.php for why the asset side is the only side that
can answer that, and why no count of the hidden remainder is returned.

## The endpoint

| | |
|---|---|
| URL | `api/contracts/get_contract_assets.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET contract_id` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/contract_assets.php](/code/files/includes/contract_assets.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [contractAssetsFor()](/code/functions/contractAssetsFor.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

