# getDefaultTenantId()

The id of the silent Default tenant (the one that owns everything on a single-company install). Falls back to the lowest tenant id, then to TENANCY_FALLBACK_TENANT_ID. (Cached.)

```php
function getDefaultTenantId(PDO $conn): int
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 134.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 140 | `getDefaultTenantId` | read [tenants](/code/tables/tenants.md) |  |
| 142 | `getDefaultTenantId` | read [tenants](/code/tables/tenants.md) |  |

## Calls

[tenancyTablesReady()](/code/functions/tenancyTablesReady.md)

## Called from

- [api/assets/delete_asset_location.php](/code/files/api/assets/delete_asset_location.php.md)
- [api/assets/delete_asset_status_type.php](/code/files/api/assets/delete_asset_status_type.php.md)
- [api/assets/delete_asset_type.php](/code/files/api/assets/delete_asset_type.php.md)
- [api/assets/get_asset_status_types.php](/code/files/api/assets/get_asset_status_types.php.md)
- [api/assets/get_asset_types.php](/code/files/api/assets/get_asset_types.php.md)
- [api/assets/save_asset_location.php](/code/files/api/assets/save_asset_location.php.md)
- [api/assets/save_asset_status_type.php](/code/files/api/assets/save_asset_status_type.php.md)
- [api/assets/save_asset_type.php](/code/files/api/assets/save_asset_type.php.md)
- [api/assets/save_disk_hide_rule.php](/code/files/api/assets/save_disk_hide_rule.php.md)
- [api/assets/search_linkable_contracts.php](/code/files/api/assets/search_linkable_contracts.php.md)
- [api/assets/set_asset_status_type_hidden.php](/code/files/api/assets/set_asset_status_type_hidden.php.md)
- [api/assets/set_asset_type_hidden.php](/code/files/api/assets/set_asset_type_hidden.php.md)
- [api/change-management/list_linkable_tickets.php](/code/files/api/change-management/list_linkable_tickets.php.md)
- [api/change-management/move_to_company.php](/code/files/api/change-management/move_to_company.php.md)
- [api/contracts/customer_lookup.php](/code/files/api/contracts/customer_lookup.php.md)
- [api/forms/lookup_search.php](/code/files/api/forms/lookup_search.php.md)
- [api/integrations/connections_for_ticket.php](/code/files/api/integrations/connections_for_ticket.php.md)
- [api/problem-management/list_linkable_tickets.php](/code/files/api/problem-management/list_linkable_tickets.php.md)
- [api/projects/tools.php](/code/files/api/projects/tools.php.md)
- [api/rfp-builder/get_rfps.php](/code/files/api/rfp-builder/get_rfps.php.md)
- [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md)
- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md)
- [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)
- [api/system/get_topology.php](/code/files/api/system/get_topology.php.md)
- [api/tasks/collaborators.php](/code/files/api/tasks/collaborators.php.md) - in [collaboratorCandidates()](/code/functions/collaboratorCandidates.md)
- [api/tickets/delete_ticket_category.php](/code/files/api/tickets/delete_ticket_category.php.md)
- [api/tickets/delete_ticket_origin.php](/code/files/api/tickets/delete_ticket_origin.php.md)
- [api/tickets/delete_ticket_resolution_code.php](/code/files/api/tickets/delete_ticket_resolution_code.php.md)
- [api/tickets/delete_ticket_type.php](/code/files/api/tickets/delete_ticket_type.php.md)
- [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md)
- [api/tickets/get_ticket_origins.php](/code/files/api/tickets/get_ticket_origins.php.md)
- [api/tickets/get_ticket_types.php](/code/files/api/tickets/get_ticket_types.php.md)
- [api/tickets/move_ticket_to_company.php](/code/files/api/tickets/move_ticket_to_company.php.md)
- [api/tickets/save_reply_template.php](/code/files/api/tickets/save_reply_template.php.md)
- [api/tickets/save_ticket_asset.php](/code/files/api/tickets/save_ticket_asset.php.md)
- [api/tickets/save_ticket_category.php](/code/files/api/tickets/save_ticket_category.php.md)
- [api/tickets/save_ticket_cmdb_object.php](/code/files/api/tickets/save_ticket_cmdb_object.php.md)
- [api/tickets/save_ticket_origin.php](/code/files/api/tickets/save_ticket_origin.php.md)
- [api/tickets/save_ticket_resolution_code.php](/code/files/api/tickets/save_ticket_resolution_code.php.md)
- [api/tickets/save_ticket_task.php](/code/files/api/tickets/save_ticket_task.php.md)
- [api/tickets/save_ticket_type.php](/code/files/api/tickets/save_ticket_type.php.md)
- [api/tickets/set_ticket_origin_hidden.php](/code/files/api/tickets/set_ticket_origin_hidden.php.md)
- [api/tickets/set_ticket_type_hidden.php](/code/files/api/tickets/set_ticket_type_hidden.php.md)
- [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) - in [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiKeyDefaultTenantId()](/code/functions/apiKeyDefaultTenantId.md)
- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiAssetsList()](/code/functions/apiAssetsList.md)
- [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) - in [apiChangesList()](/code/functions/apiChangesList.md)
- [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) - in [apiCmdbObjectsList()](/code/functions/apiCmdbObjectsList.md)
- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractsList()](/code/functions/apiContractsList.md)
- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiDomainsList()](/code/functions/apiDomainsList.md)
- [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) - in [apiProblemsList()](/code/functions/apiProblemsList.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectsList()](/code/functions/apiProjectsList.md)
- [api/v1/resources/reference.php](/code/files/api/v1/resources/reference.php.md) - in [apiCompaniesList()](/code/functions/apiCompaniesList.md)
- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiTicketsList()](/code/functions/apiTicketsList.md)
- [asset-management/index.php](/code/files/asset-management/index.php.md)
- [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) - in [catalogueCreateTicketFromSubmission()](/code/functions/catalogueCreateTicketFromSubmission.md)
- [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md) - in [cmdbImpactTenantScope()](/code/functions/cmdbImpactTenantScope.md)
- [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) - in [contractAssetLink()](/code/functions/contractAssetLink.md)
- [includes/contract_party.php](/code/files/includes/contract_party.php.md) - in [contractTenantOf()](/code/functions/contractTenantOf.md), [contractStoreTenant()](/code/functions/contractStoreTenant.md), [contractVisibilitySqlForScope()](/code/functions/contractVisibilitySqlForScope.md), [contractOwnerSql()](/code/functions/contractOwnerSql.md), [contractNormaliseParty()](/code/functions/contractNormaliseParty.md)
- [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md) - in [costCentreRecordTenant()](/code/functions/costCentreRecordTenant.md)
- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainRenewalActionsRun()](/code/functions/domainRenewalActionsRun.md)
- [includes/domains/customer.php](/code/files/includes/domains/customer.php.md) - in [domainCustomerPersonOk()](/code/functions/domainCustomerPersonOk.md), [domainCustomerSearch()](/code/functions/domainCustomerSearch.md)
- [includes/domains/links.php](/code/files/includes/domains/links.php.md) - in [domainLinkTenantOf()](/code/functions/domainLinkTenantOf.md), [domainLinkSearch()](/code/functions/domainLinkSearch.md), [domainLinkPickDomains()](/code/functions/domainLinkPickDomains.md)
- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsEscalate()](/code/functions/integrationsEscalate.md)
- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneLinkDevicesToAssets()](/code/functions/intuneLinkDevicesToAssets.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseTenant()](/code/functions/leaseTenant.md), [leaseSave()](/code/functions/leaseSave.md), [leaseList()](/code/functions/leaseList.md), [leaseAssetsAdd()](/code/functions/leaseAssetsAdd.md), [leaseDueBack()](/code/functions/leaseDueBack.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [managerReachSql()](/code/functions/managerReachSql.md), [managerCompany()](/code/functions/managerCompany.md)
- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpProjectCompanySql()](/code/functions/mcpProjectCompanySql.md)
- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [messagingFindUsersByPhone()](/code/functions/messagingFindUsersByPhone.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleCompanyMatch()](/code/functions/peopleCompanyMatch.md), [peopleListRows()](/code/functions/peopleListRows.md), [peopleCompanies()](/code/functions/peopleCompanies.md), [peopleCompanyCards()](/code/functions/peopleCompanyCards.md), [personDetail()](/code/functions/personDetail.md), [companyDetail()](/code/functions/companyDetail.md), [peopleContracts()](/code/functions/peopleContracts.md)
- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectBudgetCostCentres()](/code/functions/projectBudgetCostCentres.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkTenantOf()](/code/functions/projectLinkTenantOf.md), [projectLinkProjectTenant()](/code/functions/projectLinkProjectTenant.md), [projectLinkSearch()](/code/functions/projectLinkSearch.md), [projectsPickableFor()](/code/functions/projectsPickableFor.md)
- [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) - in [projectTargetSql()](/code/functions/projectTargetSql.md), [projectTargetOptions()](/code/functions/projectTargetOptions.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)
- [includes/report_packs/criteria.php](/code/files/includes/report_packs/criteria.php.md) - in [rpTenantClause()](/code/functions/rpTenantClause.md)
- [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) - in [rfpTenantOf()](/code/functions/rfpTenantOf.md)
- [includes/search/search.php](/code/files/includes/search/search.php.md) - in [searchScopeForAnalyst()](/code/functions/searchScopeForAnalyst.md)
- [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md) - in `AssetFieldsService::configTenantForCreate`, `AssetFieldsService::catalogue`, `AssetFieldsService::sets`
- [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md) - in `AssetImportService::run`
- [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md) - in `AssetTagsService::storeTenant`, `AssetTagsService::generate`, `AssetTagsService::noteManualTag`
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::assertScope`, `AssetsService::createAsset`, `AssetsService::moveToCompany`
- [includes/services/changes.php](/code/files/includes/services/changes.php.md) - in `ChangesService::linkTicket`, `ChangesService::canAccessTenantRow`, `ChangesService::loadJoined`
- [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md) - in `CmdbService::assertScope`, `CmdbService::resolveTenantForCreate`, `CmdbService::assertSameCompany`, `CmdbService::createObject`, `CmdbService::tenantOf`, `CmdbService::assertObjectRefsInCompany`
- [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) - in `ContractsService::effectiveTenant`, `ContractsService::ownerTenant`, `ContractsService::assertNumberFree`, `ContractsService::assertMovable`
- [includes/services/cost_centres.php](/code/files/includes/services/cost_centres.php.md) - in `CostCentresService::companiesFor`, `CostCentresService::assertCompany`
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::createDomain`, `DomainsService::saveRegistrarAccount`, `DomainsService::assertScope`, `DomainsService::storeTenant`, `DomainsService::assertContractVisible`
- [includes/services/forms.php](/code/files/includes/services/forms.php.md) - in `FormsService::lookupTenantClause`
- [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md) - in `NetworkMapperService::validateObjectExists`
- [includes/services/problems.php](/code/files/includes/services/problems.php.md) - in `ProblemsService::linkTicket`, `ProblemsService::loadProblem`, `ProblemsService::canAccessTenantRow`
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::addMember`, `ProjectToolsService::saveRaid`, `ProjectToolsService::lessonToKnowledge`, `ProjectToolsService::issueToTicket`
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::createProject`, `ProjectsService::createTaskInProject`, `ProjectsService::assertScope`, `ProjectsService::sameTenant`, `ProjectsService::storeTenant`
- [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) - in `TasksService::assertAnalystInTaskCompany`, `TasksService::moveTaskToCompany`, `TasksService::taskAccessible`, `TasksService::ticketAccessible`
- [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) - in `TicketsService::updateTicket`, `TicketsService::loadTicket`, `TicketsService::canAccessTicket`
- [includes/sso_identity.php](/code/files/includes/sso_identity.php.md) - in [ssoApplyJitCompanies()](/code/functions/ssoApplyJitCompanies.md)
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [analystCanAccessUser()](/code/functions/analystCanAccessUser.md), [analystCanAccessProblem()](/code/functions/analystCanAccessProblem.md), [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [analystCanAccessProject()](/code/functions/analystCanAccessProject.md), [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md), [activeTenantFilter()](/code/functions/activeTenantFilter.md), [allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [tenantScopeSqlFor()](/code/functions/tenantScopeSqlFor.md), [resolveTenantForNewUser()](/code/functions/resolveTenantForNewUser.md), [resolveTicketTenantForEmail()](/code/functions/resolveTicketTenantForEmail.md), [resolveTicketTenantForChannel()](/code/functions/resolveTicketTenantForChannel.md)
- [includes/ticket_links.php](/code/files/includes/ticket_links.php.md) - in [ticketLinkCreate()](/code/functions/ticketLinkCreate.md), [ticketLinkableList()](/code/functions/ticketLinkableList.md)
- [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) - in [mergeTickets()](/code/functions/mergeTickets.md)

