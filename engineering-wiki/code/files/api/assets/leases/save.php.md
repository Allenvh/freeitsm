# api/assets/leases/save.php

> API endpoint - 12 lines

Assets -> Leases (3.5.0): Create or update a lease. Rules in includes/leases.php.

## The endpoint

| | |
|---|---|
| URL | `api/assets/leases/save.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md)

**Calls:** [leaseEndpoint()](/code/functions/leaseEndpoint.md), [leaseSave()](/code/functions/leaseSave.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

