# api/assets/leases/schedule_delete.php

> API endpoint - 13 lines

Assets -> Leases (3.5.0): Delete a schedule (not the last one). Rules in includes/leases.php.

## The endpoint

| | |
|---|---|
| URL | `api/assets/leases/schedule_delete.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md)

**Calls:** [leaseEndpoint()](/code/functions/leaseEndpoint.md), [leaseScheduleDelete()](/code/functions/leaseScheduleDelete.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

