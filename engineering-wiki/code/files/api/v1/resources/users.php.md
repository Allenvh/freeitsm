# api/v1/resources/users.php

> API endpoint - 310 lines

FreeITSM REST API v1 — requesters (the `users` table: end users who raise tickets, distinct from analysts).

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/users.php` |
| Protected by | REST API key |
| Reads | `GET email`, `GET q` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiUserColumns()](/code/functions/apiUserColumns.md) | 13 | The columns every read of a requester selects - including the cost centre they are charged to (GH #160), once Database Verification has added it. |
| [apiUserCostCentre()](/code/functions/apiUserCostCentre.md) | 31 | `cost_centre_id` from a request body for a requester in company `$tenantId` (NULL = Default): an ACTIVE one of that company, or the one they hold. 422 otherwise. Returns [sent?, value]. |
| [apiSerializeUser()](/code/functions/apiSerializeUser.md) | 48 | Shapes a requester (users row) for the API - email (null for a directory user with no mailbox), names, company, active flag, cost centre and created date. |
| [apiUsersList()](/code/functions/apiUsersList.md) | 71 | GET /users?q=&email= |
| [apiUsersGet()](/code/functions/apiUsersGet.md) | 116 | GET /users/{id} |
| [apiUsersCreate()](/code/functions/apiUsersCreate.md) | 150 | POST /users |
| [apiUsersUpdate()](/code/functions/apiUsersUpdate.md) | 214 | PATCH /users/{id} |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 17 | `apiUserColumns` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 98 | `apiUsersList` | read [users](/code/tables/users.md) |  |
| 105 | `apiUsersList` | read [users](/code/tables/users.md) |  |
| 124 | `apiUsersGet` | read [users](/code/tables/users.md) |  |
| 135 | `apiUsersGet` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 175 | `apiUsersCreate` | read [users](/code/tables/users.md) |  |
| 182 | `apiUsersCreate` | read [users](/code/tables/users.md) |  |
| 203 | `apiUsersCreate` | **insert** [users](/code/tables/users.md) (`email`, `username`, `display_name`, `preferred_name`, `tenant_id`, `created_at`) |  |
| 206 | `apiUsersCreate` | **update** [users](/code/tables/users.md) (`cost_centre_id`) |  |
| 208 | `apiUsersCreate` | read [users](/code/tables/users.md) |  |
| 229 | `apiUsersUpdate` | read [users](/code/tables/users.md) |  |
| 243 | `apiUsersUpdate` | read [users](/code/tables/users.md) |  |
| 282 | `apiUsersUpdate` | read [users](/code/tables/users.md) |  |
| 294 | `apiUsersUpdate` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 304 | `apiUsersUpdate` | **update** [users](/code/tables/users.md) |  |
| 306 | `apiUsersUpdate` | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiKeyTicketFilter()](/code/functions/apiKeyTicketFilter.md), [apiPagination()](/code/functions/apiPagination.md), [apiRespond()](/code/functions/apiRespond.md), [costCentreCheck()](/code/functions/costCentreCheck.md), [costCentreReady()](/code/functions/costCentreReady.md), [costCentreRecordTenant()](/code/functions/costCentreRecordTenant.md), [resolveTenantForNewUser()](/code/functions/resolveTenantForNewUser.md)

