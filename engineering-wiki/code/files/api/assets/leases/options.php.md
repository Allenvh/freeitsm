# api/assets/leases/options.php

> API endpoint - 58 lines

Assets -> Leases (3.5.0): what the lease form offers for one company - the companies this analyst can reach, suppliers, that company's active cost centres, that company's contracts they may see, and the outcomes.

GET ?company_id= (default: the active company; one they cannot reach is ignored)
    &keep_cost_centre= (the lease's current one, kept even if since made inactive)

## The endpoint

| | |
|---|---|
| URL | `api/assets/leases/options.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | read [suppliers](/code/tables/suppliers.md) |  |
| 42 |  | read [contracts](/code/tables/contracts.md) |  |

## How it hangs together

**Includes:** [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md), [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [contractTenantReady()](/code/functions/contractTenantReady.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [costCentreOptions()](/code/functions/costCentreOptions.md), [costCentreReady()](/code/functions/costCentreReady.md), [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getAllTenants()](/code/functions/getAllTenants.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [leaseEndpoint()](/code/functions/leaseEndpoint.md), [leaseOutcomes()](/code/functions/leaseOutcomes.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [tenantScopeSqlFor()](/code/functions/tenantScopeSqlFor.md)

