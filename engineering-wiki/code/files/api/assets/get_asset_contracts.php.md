# api/assets/get_asset_contracts.php

> API endpoint - 51 lines

API: Contracts covering an asset (discussion #106). GET ?asset_id=

The other half of the feature, and the half that pays for it: standing on a
handset and asking "what agreement is this on, and when can we get out of
it?" is the question the requester actually had.

Gated on the ASSETS module, because this is the asset's own page — but the
contract titles are only returned to somebody who may see the contracts
module, so an asset manager without it gets an empty panel rather than a list
of agreements they cannot open.

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_contracts.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET asset_id` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/contract_assets.php](/code/files/includes/contract_assets.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [contractAssetCanReach()](/code/functions/contractAssetCanReach.md), [contractsForAsset()](/code/functions/contractsForAsset.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

