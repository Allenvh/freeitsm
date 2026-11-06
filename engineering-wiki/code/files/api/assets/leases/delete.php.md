# api/assets/leases/delete.php

> API endpoint - 13 lines

Assets -> Leases (3.5.0): Delete a lease (its schedules and asset rows go with it; the assets keep their dates). Rules in includes/leases.php.

## The endpoint

| | |
|---|---|
| URL | `api/assets/leases/delete.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md)

**Calls:** [leaseDelete()](/code/functions/leaseDelete.md), [leaseEndpoint()](/code/functions/leaseEndpoint.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

