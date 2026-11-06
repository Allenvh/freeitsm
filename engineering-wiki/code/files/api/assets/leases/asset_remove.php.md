# api/assets/leases/asset_remove.php

> API endpoint - 13 lines

Assets -> Leases (3.5.0): Take an asset off a schedule altogether (a mistake - a return is an outcome). Rules in includes/leases.php.

## The endpoint

| | |
|---|---|
| URL | `api/assets/leases/asset_remove.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md)

**Calls:** [leaseAssetRemove()](/code/functions/leaseAssetRemove.md), [leaseEndpoint()](/code/functions/leaseEndpoint.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

