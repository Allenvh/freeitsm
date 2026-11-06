# api/assets/delete_lease_outcome.php

> API endpoint - 42 lines

API Endpoint: Delete a lease outcome (3.5.0). Refused while any leased asset has it - switch it off instead, so the record of what happened stays.

## The endpoint

| | |
|---|---|
| URL | `api/assets/delete_lease_outcome.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_LEASING (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 |  | read [lease_assets](/code/tables/lease_assets.md) |  |
| 37 |  | **delete** [lease_outcomes](/code/tables/lease_outcomes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/leases.php](/code/files/includes/leases.php.md)

**Calls:** [analystHasAllTenantAccess()](/code/functions/analystHasAllTenantAccess.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [leaseReady()](/code/functions/leaseReady.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

