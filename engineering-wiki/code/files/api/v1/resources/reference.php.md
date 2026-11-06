# api/v1/resources/reference.php

> API endpoint - 133 lines

FreeITSM REST API v1 — reference data: the lookups an integration needs to build valid ticket writes (statuses, priorities, ticket types, origins, departments), plus analysts and companies.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/reference.php` |
| Protected by | REST API key |
| Reads | `GET include_inactive`, `GET email`, `GET company_id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiAnalystsList()](/code/functions/apiAnalystsList.md) | 12 | GET /analysts — active analysts (assignment targets). 3.5.0 (PR #174): ?email= finds one by address (case-insensitive) and ?include_inactive=1 adds deactivated ones, so a provisioning client can find someone it deactivated and switch them back on. is_active is in every row. |
| [apiCompaniesList()](/code/functions/apiCompaniesList.md) | 36 | GET /companies — the companies (tenants) this key can see. |
| [apiStatusesList()](/code/functions/apiStatusesList.md) | 53 | GET /statuses |
| [apiPrioritiesList()](/code/functions/apiPrioritiesList.md) | 70 | GET /priorities |
| [apiTicketTypesList()](/code/functions/apiTicketTypesList.md) | 85 | GET /ticket-types (?company_id= applies the per-company add/hide overrides) |
| [apiOriginsList()](/code/functions/apiOriginsList.md) | 90 | GET /origins (?company_id= applies the per-company add/hide overrides) |
| [apiTenantConfigList()](/code/functions/apiTenantConfigList.md) | 100 | Shared list for the tenant-overridable config tables (add/hide model). |
| [apiDepartmentsList()](/code/functions/apiDepartmentsList.md) | 120 | GET /departments |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 22 | `apiAnalystsList` | read [analysts](/code/tables/analysts.md) |  |
| 55 | `apiStatusesList` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 72 | `apiPrioritiesList` | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 122 | `apiDepartmentsList` | read [departments](/code/tables/departments.md) |  |

## How it hangs together

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiRespond()](/code/functions/apiRespond.md), [getAllTenants()](/code/functions/getAllTenants.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md)

