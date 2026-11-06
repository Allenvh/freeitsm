# api/contracts/delete_contract_asset.php

> API endpoint - 37 lines

API: Remove an asset from a contract (discussion #106). POST { link_id }

Deletes the LINK only. The asset and the contract both survive it — this says
"this equipment is not on that agreement", never "this equipment is gone".

## The endpoint

| | |
|---|---|
| URL | `api/contracts/delete_contract_asset.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/contract_assets.php](/code/files/includes/contract_assets.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [contractAssetUnlink()](/code/functions/contractAssetUnlink.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

