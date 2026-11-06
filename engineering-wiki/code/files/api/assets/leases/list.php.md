# api/assets/leases/list.php

> API endpoint - 12 lines

Assets -> Leases (3.5.0): Every lease in the active company (or all of them), with dates and counts. Rules in includes/leases.php.

## The endpoint

| | |
|---|---|
| URL | `api/assets/leases/list.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md)

**Calls:** [leaseEndpoint()](/code/functions/leaseEndpoint.md), [leaseList()](/code/functions/leaseList.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

