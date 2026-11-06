# api/contracts/customer_lookup.php

> API endpoint - 68 lines

Contracts: choosing a contract's CUSTOMER (#153). GET {multi_company, companies: [{id, name}]} - the companies you can access GET ?q=jane[&tenant_id=4] {people: [{id, name, email, company}]} - people in those companies

Its own endpoint rather than api/tickets/get_users.php: that one needs Tickets or
Assets, and an analyst who only does contracts should still be able to name a
customer. It never returns a person from a company the analyst cannot access.

## The endpoint

| | |
|---|---|
| URL | `api/contracts/customer_lookup.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET q`, `GET tenant_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [users](/code/tables/users.md), read [tenants](/code/tables/tenants.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getAllTenants()](/code/functions/getAllTenants.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

