# api/assets/leases/for_asset.php

> API endpoint - 14 lines

Assets -> Leases (3.5.0): The leases one asset has been on, for the asset panel. Rules in includes/leases.php.

## The endpoint

| | |
|---|---|
| URL | `api/assets/leases/for_asset.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [leaseEndpoint()](/code/functions/leaseEndpoint.md), [leaseHistoryForAsset()](/code/functions/leaseHistoryForAsset.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

