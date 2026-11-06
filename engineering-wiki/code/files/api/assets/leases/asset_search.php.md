# api/assets/leases/asset_search.php

> API endpoint - 34 lines

Assets -> Leases (3.5.0): find assets to put on a lease - the lease's own company only, by hostname, asset tag, service tag, manufacturer or model. Each says which lease it is already out on, if any (it cannot go on two).

GET ?lease_id=&q=

## The endpoint

| | |
|---|---|
| URL | `api/assets/leases/asset_search.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 22 |  | read [lease_assets](/code/tables/lease_assets.md), read [lease_schedules](/code/tables/lease_schedules.md), read [leases](/code/tables/leases.md), read [lease_outcomes](/code/tables/lease_outcomes.md), read [assets](/code/tables/assets.md) |  |

## How it hangs together

**Includes:** [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md)

**Calls:** [leaseActiveSql()](/code/functions/leaseActiveSql.md), [leaseEndpoint()](/code/functions/leaseEndpoint.md), [leaseLoad()](/code/functions/leaseLoad.md), [leaseTenant()](/code/functions/leaseTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [tenantScopeSqlFor()](/code/functions/tenantScopeSqlFor.md)

