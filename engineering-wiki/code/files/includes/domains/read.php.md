# includes/domains/read.php

> PHP - 218 lines

Domains — the reads the screens share: the register list and one domain.

Reads live here, not in the service (see the Service-Layer page: writes are
unified, reads stay per surface). The REST API has its own serialiser in
api/v1/resources/domains.php — a frozen public contract, a different shape.

🔑 SCOPE IN SQL, NOT IN JS. The list is filtered by company in the query
(activeTenantReadFilter — one company, or every company the analyst can see
in the "All companies" view). A row the analyst may not see is never sent.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainListSelect()](/code/functions/domainListSelect.md) | 18 | The columns + joins every register read uses. |
| [domainListRows()](/code/functions/domainListRows.md) | 71 | The register, scoped to what this analyst may see. |
| [domainShapeRow()](/code/functions/domainShapeRow.md) | 101 | Types, and the derived numbers every screen wants (days left, attention). |
| [domainNeedsAttention()](/code/functions/domainNeedsAttention.md) | 130 | The reasons a domain belongs on the "needs attention" list — one place, so the register filter, the dashboard and Watchtower cannot disagree. |
| [domainDetail()](/code/functions/domainDetail.md) | 149 | One domain for its page: everything but the auth code's value. |
| [domainApiLookups()](/code/functions/domainApiLookups.md) | 183 | Statuses, owners, registrars, accounts, purposes — for filters and forms. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 | `domainListSelect` | read [users](/code/tables/users.md) |  |
| 32 | `domainListSelect` | read [suppliers](/code/tables/suppliers.md), read [contacts](/code/tables/contacts.md), read [analysts](/code/tables/analysts.md) |  |
| 39 | `domainListSelect` | read [domain_lookalikes](/code/tables/domain_lookalikes.md), read [domain_certificates](/code/tables/domain_certificates.md), read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md), read [suppliers](/code/tables/suppliers.md), read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md), read [analysts](/code/tables/analysts.md), read [contacts](/code/tables/contacts.md), read [tenants](/code/tables/tenants.md) |  |
| 160 | `domainDetail` | read [contracts](/code/tables/contracts.md), read [domains](/code/tables/domains.md) |  |
| 191 | `domainApiLookups` | read [contracts](/code/tables/contracts.md) |  |
| 195 | `domainApiLookups` | read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) |  |
| 198 | `domainApiLookups` | read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 199 | `domainApiLookups` | read [analysts](/code/tables/analysts.md) |  |
| 200 | `domainApiLookups` | read [suppliers](/code/tables/suppliers.md) |  |
| 211 | `domainApiLookups` | read [contacts](/code/tables/contacts.md), read [suppliers](/code/tables/suppliers.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/domains/customer.php](/code/files/includes/domains/customer.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md), [includes/domains/names.php](/code/files/includes/domains/names.php.md)

**Included by:** [api/domains/dashboard.php](/code/files/api/domains/dashboard.php.md), [api/domains/get.php](/code/files/api/domains/get.php.md), [api/domains/list.php](/code/files/api/domains/list.php.md), [api/domains/lookups.php](/code/files/api/domains/lookups.php.md)

**Calls:** [activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [domainCustomerReady()](/code/functions/domainCustomerReady.md), [domainPartiesReady()](/code/functions/domainPartiesReady.md), [domainPurposes()](/code/functions/domainPurposes.md), [domainRenewalModes()](/code/functions/domainRenewalModes.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getAllTenants()](/code/functions/getAllTenants.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

