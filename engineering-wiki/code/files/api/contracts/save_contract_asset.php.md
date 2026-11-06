# api/contracts/save_contract_asset.php

> API endpoint - 55 lines

API: Link an asset to a contract, or change the note on an existing link (#106).

POST { contract_id, asset_id, reference? }   link, or update the note
POST { link_id, reference }                  change the note only

⚠️ The asset id is re-checked against what this analyst can reach. It arrived
in a request body, not from the list this server rendered — a scoped list has
never been a substitute for a gate.

## The endpoint

| | |
|---|---|
| URL | `api/contracts/save_contract_asset.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/contract_assets.php](/code/files/includes/contract_assets.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [contractAssetLink()](/code/functions/contractAssetLink.md), [contractAssetSetReference()](/code/functions/contractAssetSetReference.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

