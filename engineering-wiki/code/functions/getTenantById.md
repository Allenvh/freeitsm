# getTenantById()

A single tenant row, or null if not found.

```php
function getTenantById(PDO $conn, int $tenantId): ?array
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 171.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 173 | `getTenantById` | read [tenants](/code/tables/tenants.md) |  |

## Calls

[tenancyTablesReady()](/code/functions/tenancyTablesReady.md)

## Called from

- [api/assets/create_asset.php](/code/files/api/assets/create_asset.php.md)
- [api/assets/get_asset_status_types.php](/code/files/api/assets/get_asset_status_types.php.md)
- [api/assets/get_asset_types.php](/code/files/api/assets/get_asset_types.php.md)
- [api/change-management/move_to_company.php](/code/files/api/change-management/move_to_company.php.md)
- [api/rfp-builder/get_rfp.php](/code/files/api/rfp-builder/get_rfp.php.md)
- [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md)
- [api/system/add_freemail_domain.php](/code/files/api/system/add_freemail_domain.php.md)
- [api/system/add_tenant_domain.php](/code/files/api/system/add_tenant_domain.php.md)
- [api/system/add_tenant_sender.php](/code/files/api/system/add_tenant_sender.php.md)
- [api/system/email_routing_test.php](/code/files/api/system/email_routing_test.php.md)
- [api/system/save_tenant.php](/code/files/api/system/save_tenant.php.md)
- [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md)
- [api/tickets/get_ticket_origins.php](/code/files/api/tickets/get_ticket_origins.php.md)
- [api/tickets/get_ticket_types.php](/code/files/api/tickets/get_ticket_types.php.md)
- [api/tickets/move_ticket_to_company.php](/code/files/api/tickets/move_ticket_to_company.php.md)
- [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md)
- [api/v1/index.php](/code/files/api/v1/index.php.md) - in [apiHandlePing()](/code/functions/apiHandlePing.md)
- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiAssetsCreate()](/code/functions/apiAssetsCreate.md)
- [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) - in [apiChangesCreate()](/code/functions/apiChangesCreate.md)
- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractCompany()](/code/functions/apiContractCompany.md)
- [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md) - in [apiCostCentreCompany()](/code/functions/apiCostCentreCompany.md)
- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiDomainsCreate()](/code/functions/apiDomainsCreate.md)
- [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) - in [apiProblemsCreate()](/code/functions/apiProblemsCreate.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectsCreate()](/code/functions/apiProjectsCreate.md)
- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiTicketsCreate()](/code/functions/apiTicketsCreate.md)
- [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md) - in [domainApiTenantForCreate()](/code/functions/domainApiTenantForCreate.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseGet()](/code/functions/leaseGet.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleListRows()](/code/functions/peopleListRows.md), [personDetail()](/code/functions/personDetail.md), [companyDetail()](/code/functions/companyDetail.md)
- [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md) - in [projectApiTenantForCreate()](/code/functions/projectApiTenantForCreate.md)
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::moveToCompany`
- [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) - in `ContractsService::ownerTenant`
- [includes/services/cost_centres.php](/code/files/includes/services/cost_centres.php.md) - in `CostCentresService::assertCompany`
- [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md) - in `KnowledgeService::resolveTenantId`
- [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) - in `TasksService::moveTaskToCompany`
- [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) - in `TicketsService::updateTicket`
- [includes/tenancy-switcher.php](/code/files/includes/tenancy-switcher.php.md) - in [renderTenantSwitcher()](/code/functions/renderTenantSwitcher.md)

