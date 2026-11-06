# api/assets/leases/assets_add.php

> API endpoint - 13 lines

Assets -> Leases (3.5.0): Put assets on a schedule. Returns how many went on and why any were refused. Rules in includes/leases.php.

## The endpoint

| | |
|---|---|
| URL | `api/assets/leases/assets_add.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md)

**Calls:** [leaseAssetsAdd()](/code/functions/leaseAssetsAdd.md), [leaseEndpoint()](/code/functions/leaseEndpoint.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

