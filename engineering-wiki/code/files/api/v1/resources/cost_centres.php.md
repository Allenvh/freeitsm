# api/v1/resources/cost_centres.php

> API endpoint - 260 lines

FreeITSM REST API v1 — cost centres resource (GH #160).

Every write goes through CostCentresService (includes/services/cost_centres.php),
the same code System -> Cost Centres and its spreadsheet import use, so a cost
centre changed through the API gets exactly the same checks as one changed by
a person. The reads + serialiser live here: the API shape is a public contract.

🔑 POST /cost-centres/sync IS THE ONE AN ACCOUNTING SYSTEM CALLS. Send the
list, matched on code, as often as you like: new codes are added, changed
ones updated, nothing duplicated, and - with deactivate_missing - anything no
longer in the list made inactive. All or nothing: one bad row and nothing is
written, and every problem comes back in error.details.

COMPANY SCOPE: cost centres are always one company's. The list is filtered to
the key's companies, every by-id route 404s outside them (never 403, so ids
cannot be probed), and writes land in `company_id` (checked against the key)
or the key's default company.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/cost_centres.php` |
| Protected by | REST API key |
| Reads | `GET company_id`, `GET code`, `GET q`, `GET active`, `GET parent_id`, `GET sort` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiCostCentreSelect()](/code/functions/apiCostCentreSelect.md) | 32 | The SELECT for cost centres, with the parent's code and name, the company name and the number of child cost centres. |
| [apiSerializeCostCentre()](/code/functions/apiSerializeCostCentre.md) | 46 | Shapes a cost centre row for the API - code, name, description, parent, active flag, company, child count and dates. |
| [apiLoadCostCentre()](/code/functions/apiLoadCostCentre.md) | 66 | The by-id gate: existence + company scope, both failing as 404. |
| [apiCostCentreCompany()](/code/functions/apiCostCentreCompany.md) | 81 | company_id from the body/query, checked against the key - or the key's default company. |
| [apiCostCentresReady()](/code/functions/apiCostCentresReady.md) | 93 | Answers 503 and stops when the cost centre tables are not there yet (Database Verification has not been run). |
| [apiCostCentresList()](/code/functions/apiCostCentresList.md) | 102 | GET /cost-centres |
| [apiCostCentresGet()](/code/functions/apiCostCentresGet.md) | 161 | GET /cost-centres/{id} |
| [apiCostCentresCreate()](/code/functions/apiCostCentresCreate.md) | 169 | POST /cost-centres |
| [apiCostCentresUpdate()](/code/functions/apiCostCentresUpdate.md) | 184 | PATCH /cost-centres/{id} |
| [apiCostCentresDelete()](/code/functions/apiCostCentresDelete.md) | 203 | DELETE /cost-centres/{id} |
| [apiCostCentresSync()](/code/functions/apiCostCentresSync.md) | 218 | POST /cost-centres/sync — bring a company's list into line, matched on code |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 | `apiCostCentreSelect` | read [cost_centres](/code/tables/cost_centres.md), read [tenants](/code/tables/tenants.md) |  |
| 147 | `apiCostCentresList` | read [cost_centres](/code/tables/cost_centres.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/cost_centres.php](/code/files/includes/services/cost_centres.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md), [apiKeyDefaultTenantId()](/code/functions/apiKeyDefaultTenantId.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiPagination()](/code/functions/apiPagination.md), [apiRequirePermission()](/code/functions/apiRequirePermission.md), [apiRespond()](/code/functions/apiRespond.md), [getTenantById()](/code/functions/getTenantById.md)

