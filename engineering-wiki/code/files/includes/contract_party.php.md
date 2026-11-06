# includes/contract_party.php

> PHP - 265 lines

Who OWNS a contract, who it is WITH, and who may see it.

OWNER (3.5.0) - contracts.tenant_id, the company the contract belongs to. Scoped
data, exactly like assets and tickets: NULL is the Default company, never
"shared". An analyst sees a contract when they can reach its company, and the
contracts list follows the company switcher.

PARTY (#153) - who the contract is WITH: a supplier (we buy, supplier_id) or a
customer (we sell: customer_tenant_id and/or customer_user_id). Set
independently of the owner - a contract owned by your Default company can be
with customer X. It no longer decides visibility.

Until 3.5.0 a customer contract was hidden by its CUSTOMER's company, and a
setting (contracts_customer_visibility) could open them to everyone. The first
Database Verification of 3.5.0 filed each existing customer contract under its
customer's company, so nobody's view changed on upgrade; the setting is gone.

⚠️ EVERY place that reads `contracts` must apply `contractVisibilitySql()` (any
read - search, previews, links), `contractListSql()` (the contracts list and its
dashboard, which follow the switcher) or `contractCanView()` (one record). A read
that forgets it shows one company's contract and its value to another's analyst.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [contractTenantReady()](/code/functions/contractTenantReady.md) | 32 | True once Database Verification has added contracts.tenant_id. |
| [contractTenantOf()](/code/functions/contractTenantOf.md) | 45 | The company a contract belongs to (NULL resolved to Default), or null if there is no such contract. |
| [contractStoreTenant()](/code/functions/contractStoreTenant.md) | 56 | How a company is stored on a contract: the Default company is NULL, like every scoped table. |
| [contractPartyReady()](/code/functions/contractPartyReady.md) | 63 | True once Database Verification has added the party columns. |
| [contractCustomerTenantExpr()](/code/functions/contractCustomerTenantExpr.md) | 77 | The SQL expression for a contract's customer company, for alias `$c`. Uses the customer person's company when no company was chosen. |
| [contractVisibilitySql()](/code/functions/contractVisibilitySql.md) | 91 | " AND (...)" keeping only contracts in a company this analyst can reach, for a query over `contracts` aliased `$c` - every company they may see, whatever the switcher says. For search, previews, links and anything else that is not the contracts list itself. ['', []] on a single-company install. |
| [contractVisibilitySqlForScope()](/code/functions/contractVisibilitySqlForScope.md) | 107 | The same rule for an explicit company list, in the API's null-means-all convention (ActorContext::`$companyScope`). |
| [contractListSql()](/code/functions/contractListSql.md) | 129 | The contracts LIST and its dashboard: the active company, or every company the analyst can see when the switcher says "All companies" - the same as Assets and Domains. ['', []] on a single-company install. |
| [contractCanView()](/code/functions/contractCanView.md) | 142 | May this analyst see contract `$id`? (Existence is the caller's business.) |
| [contractOwnerSql()](/code/functions/contractOwnerSql.md) | 158 | The owning company, for alias `$c`: tenant_id (NULL read as Default) and company_name. A NULL column before Database Verification, so callers need no second branch. |
| [contractPartySql()](/code/functions/contractPartySql.md) | 176 | Joins and columns that name a contract's customer, for alias `$c`: customer_company_name, customer_person_name, customer_person_email Empty strings before Database Verification, so callers need no second branch. |
| [contractPartyLabel()](/code/functions/contractPartyLabel.md) | 199 | One line naming who a contract is with, for lists, previews and payloads: the supplier's name, or "Company · Person" / "Company" / "Person". |
| [contractNormaliseParty()](/code/functions/contractNormaliseParty.md) | 223 | Check and normalise the party fields of a contract being saved. Throws InvalidArgumentException with a message fit for the analyst. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 | `contractTenantReady` | read [contracts](/code/tables/contracts.md) |  |
| 48 | `contractTenantOf` | read [contracts](/code/tables/contracts.md) |  |
| 67 | `contractPartyReady` | read [contracts](/code/tables/contracts.md) |  |
| 79 | `contractCustomerTenantExpr` | read [users](/code/tables/users.md) |  |
| 145 | `contractCanView` | read [contracts](/code/tables/contracts.md) |  |
| 165 | `contractOwnerSql` | read [tenants](/code/tables/tenants.md) |  |
| 187 | `contractPartySql` | read [tenants](/code/tables/tenants.md), read [users](/code/tables/users.md) |  |
| 245 | `contractNormaliseParty` | read [tenants](/code/tables/tenants.md) |  |
| 252 | `contractNormaliseParty` | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Included by:** [api/assets/leases/options.php](/code/files/api/assets/leases/options.php.md), [api/assets/search_linkable_contracts.php](/code/files/api/assets/search_linkable_contracts.php.md), [api/contracts/get_contract.php](/code/files/api/contracts/get_contract.php.md), [api/contracts/get_contracts.php](/code/files/api/contracts/get_contracts.php.md), [api/contracts/get_dashboard_stats.php](/code/files/api/contracts/get_dashboard_stats.php.md), [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md), [api/system/global_search.php](/code/files/api/system/global_search.php.md), [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), [includes/contract_assets.php](/code/files/includes/contract_assets.php.md), [includes/contract_report.php](/code/files/includes/contract_report.php.md), [includes/documents.php](/code/files/includes/documents.php.md), [includes/domains/read.php](/code/files/includes/domains/read.php.md), [includes/leases.php](/code/files/includes/leases.php.md), [includes/people.php](/code/files/includes/people.php.md), [includes/projects/links.php](/code/files/includes/projects/links.php.md), [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), [includes/record_preview.php](/code/files/includes/record_preview.php.md), [includes/services/calendar.php](/code/files/includes/services/calendar.php.md), [includes/services/contracts.php](/code/files/includes/services/contracts.php.md), [includes/services/domains.php](/code/files/includes/services/domains.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md), [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md), [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md)

**Calls:** [activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [t()](/code/functions/t.md)

