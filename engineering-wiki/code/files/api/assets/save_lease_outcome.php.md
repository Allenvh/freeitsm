# api/assets/save_lease_outcome.php

> API endpoint - 60 lines

API Endpoint: Save a lease outcome (3.5.0). kind 'active' = still on lease (counts towards Due back); 'closed' = finished with. is_default = what an asset starts with.

## The endpoint

| | |
|---|---|
| URL | `api/assets/save_lease_outcome.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_LEASING (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 42 |  | read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 45 |  | **update** [lease_outcomes](/code/tables/lease_outcomes.md) (`name`, `kind`, `display_order`, `is_active`, `is_default`) |  |
| 48 |  | **insert** [lease_outcomes](/code/tables/lease_outcomes.md) (`name`, `kind`, `display_order`, `is_active`, `is_default`) |  |
| 53 |  | **update** [lease_outcomes](/code/tables/lease_outcomes.md) (`is_default`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/leases.php](/code/files/includes/leases.php.md)

**Calls:** [analystHasAllTenantAccess()](/code/functions/analystHasAllTenantAccess.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [leaseReady()](/code/functions/leaseReady.md), [leaseSyncCalendar()](/code/functions/leaseSyncCalendar.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

