# api/assets/leases/_boot.php

> API endpoint - 59 lines

Shared start for the Assets -> Leases endpoints (3.5.0): signed in, has the Assets module, JSON out. The rules - who may see which lease, what may go on one - are all in includes/leases.php; these files only adapt requests to it.

leaseEndpoint('GET'|'POST', function (PDO `$conn`, ActorContext `$ctx`, array `$in`) { return [...]; });

`$in` is the JSON body for POST, `$_GET` for GET. A ServiceError becomes
{success:false, error}; anything else is logged and reported generically.

## The endpoint

| | |
|---|---|
| URL | `api/assets/leases/_boot.php` |
| Protected by | Helper - included by other endpoints, not called directly |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [leaseEndpoint()](/code/functions/leaseEndpoint.md) | 30 | Runs one Leases endpoint: checks sign-in, Assets module access, the HTTP method and that the lease tables exist, then calls `$fn` and echoes its result as JSON. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/leases.php](/code/files/includes/leases.php.md)

**Included by:** [api/assets/leases/assets_add.php](/code/files/api/assets/leases/assets_add.php.md), [api/assets/leases/asset_remove.php](/code/files/api/assets/leases/asset_remove.php.md), [api/assets/leases/asset_search.php](/code/files/api/assets/leases/asset_search.php.md), [api/assets/leases/asset_update.php](/code/files/api/assets/leases/asset_update.php.md), [api/assets/leases/delete.php](/code/files/api/assets/leases/delete.php.md), [api/assets/leases/due_back.php](/code/files/api/assets/leases/due_back.php.md), [api/assets/leases/for_asset.php](/code/files/api/assets/leases/for_asset.php.md), [api/assets/leases/get.php](/code/files/api/assets/leases/get.php.md), [api/assets/leases/list.php](/code/files/api/assets/leases/list.php.md), [api/assets/leases/options.php](/code/files/api/assets/leases/options.php.md), [api/assets/leases/save.php](/code/files/api/assets/leases/save.php.md), [api/assets/leases/schedule_delete.php](/code/files/api/assets/leases/schedule_delete.php.md), [api/assets/leases/schedule_save.php](/code/files/api/assets/leases/schedule_save.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [leaseReady()](/code/functions/leaseReady.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

