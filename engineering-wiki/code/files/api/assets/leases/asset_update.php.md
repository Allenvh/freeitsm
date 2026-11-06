# api/assets/leases/asset_update.php

> API endpoint - 13 lines

Assets -> Leases (3.5.0): Change one asset on a schedule: outcome, its own dates, notes. Rules in includes/leases.php.

## The endpoint

| | |
|---|---|
| URL | `api/assets/leases/asset_update.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md)

**Calls:** [leaseAssetUpdate()](/code/functions/leaseAssetUpdate.md), [leaseEndpoint()](/code/functions/leaseEndpoint.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

