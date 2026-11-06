# analystCanAccessTenant()

May this analyst access this tenant?

```php
function analystCanAccessTenant(PDO $conn, int $analystId, int $tenantId): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 249.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md)

## Called from

- [api/assets/create_asset.php](/code/files/api/assets/create_asset.php.md)
- [api/assets/get_asset_label_settings.php](/code/files/api/assets/get_asset_label_settings.php.md)
- [api/assets/get_asset_tag_settings.php](/code/files/api/assets/get_asset_tag_settings.php.md)
- [api/assets/leases/options.php](/code/files/api/assets/leases/options.php.md)
- [api/assets/save_asset_label_settings.php](/code/files/api/assets/save_asset_label_settings.php.md)
- [api/assets/save_asset_tag_settings.php](/code/files/api/assets/save_asset_tag_settings.php.md)
- [api/change-management/move_to_company.php](/code/files/api/change-management/move_to_company.php.md)
- [api/cost-centres/options.php](/code/files/api/cost-centres/options.php.md)
- [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md)
- [api/system/set_active_tenant.php](/code/files/api/system/set_active_tenant.php.md)
- [api/tasks/collaborators.php](/code/files/api/tasks/collaborators.php.md) - in [collaboratorCandidates()](/code/functions/collaboratorCandidates.md)
- [api/tickets/create_ticket.php](/code/files/api/tickets/create_ticket.php.md)
- [api/tickets/move_ticket_to_company.php](/code/files/api/tickets/move_ticket_to_company.php.md)
- [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md)
- [api/tickets/save_ticket_classification_settings.php](/code/files/api/tickets/save_ticket_classification_settings.php.md)
- [api/tickets/save_time_tracking_settings.php](/code/files/api/tickets/save_time_tracking_settings.php.md)
- [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md)
- [includes/contract_party.php](/code/files/includes/contract_party.php.md) - in [contractNormaliseParty()](/code/functions/contractNormaliseParty.md)
- [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md) - in [domainApiTenantForCreate()](/code/functions/domainApiTenantForCreate.md)
- [includes/domains/read.php](/code/files/includes/domains/read.php.md) - in [domainApiLookups()](/code/functions/domainApiLookups.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseSave()](/code/functions/leaseSave.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleListRows()](/code/functions/peopleListRows.md), [companyDetail()](/code/functions/companyDetail.md)
- [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md) - in [projectApiTenantForCreate()](/code/functions/projectApiTenantForCreate.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)
- [includes/report_packs/criteria.php](/code/files/includes/report_packs/criteria.php.md) - in [rpTenantClause()](/code/functions/rpTenantClause.md)
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::moveToCompany`
- [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) - in `TasksService::assertAnalystInTaskCompany`, `TasksService::moveTaskToCompany`
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [analystCanAccessUser()](/code/functions/analystCanAccessUser.md), [analystCanAccessProblem()](/code/functions/analystCanAccessProblem.md), [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [analystCanAccessProject()](/code/functions/analystCanAccessProject.md), [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [analystCanAccessChannel()](/code/functions/analystCanAccessChannel.md), [analystCanAssignTenant()](/code/functions/analystCanAssignTenant.md), [analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [requestedTenantId()](/code/functions/requestedTenantId.md), [analystCanAccessArticle()](/code/functions/analystCanAccessArticle.md)

