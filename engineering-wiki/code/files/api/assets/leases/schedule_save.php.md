# api/assets/leases/schedule_save.php

> API endpoint - 12 lines

Assets -> Leases (3.5.0): Create or update a schedule on a lease. Rules in includes/leases.php.

## The endpoint

| | |
|---|---|
| URL | `api/assets/leases/schedule_save.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md)

**Calls:** [leaseEndpoint()](/code/functions/leaseEndpoint.md), [leaseScheduleSave()](/code/functions/leaseScheduleSave.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

