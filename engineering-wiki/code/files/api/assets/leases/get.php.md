# api/assets/leases/get.php

> API endpoint - 12 lines

Assets -> Leases (3.5.0): One lease with its schedules and the assets on them. Rules in includes/leases.php.

## The endpoint

| | |
|---|---|
| URL | `api/assets/leases/get.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md)

**Calls:** [leaseEndpoint()](/code/functions/leaseEndpoint.md), [leaseGet()](/code/functions/leaseGet.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

