# api/assets/get_lease_outcomes.php

> API endpoint - 27 lines

API Endpoint: Lease outcomes (3.5.0) - what became of a leased asset. One list for every company, managed in Assets -> Settings -> Leasing.

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_lease_outcomes.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET manage` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/leases.php](/code/files/includes/leases.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [leaseOutcomes()](/code/functions/leaseOutcomes.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

