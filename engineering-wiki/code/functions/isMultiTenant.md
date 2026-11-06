# isMultiTenant()

Is multi-tenancy "awake"? True only once a second tenant exists. This is the master switch: while false, switchers/scoping/triage all stay dormant and the app behaves exactly as a single-company install.

```php
function isMultiTenant(PDO $conn): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 125.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[tenantCount()](/code/functions/tenantCount.md)

## Called from

- [api/assets/create_asset.php](/code/files/api/assets/create_asset.php.md)
- [api/assets/delete_asset_location.php](/code/files/api/assets/delete_asset_location.php.md)
- [api/assets/delete_asset_status_type.php](/code/files/api/assets/delete_asset_status_type.php.md)
- [api/assets/delete_asset_type.php](/code/files/api/assets/delete_asset_type.php.md)
- [api/assets/get_asset_label_settings.php](/code/files/api/assets/get_asset_label_settings.php.md)
- [api/assets/get_asset_locations.php](/code/files/api/assets/get_asset_locations.php.md)
- [api/assets/get_asset_status_types.php](/code/files/api/assets/get_asset_status_types.php.md)
- [api/assets/get_asset_tag_settings.php](/code/files/api/assets/get_asset_tag_settings.php.md)
- [api/assets/get_asset_types.php](/code/files/api/assets/get_asset_types.php.md)
- [api/assets/leases/options.php](/code/files/api/assets/leases/options.php.md)
- [api/assets/save_asset_label_settings.php](/code/files/api/assets/save_asset_label_settings.php.md)
- [api/assets/save_asset_location.php](/code/files/api/assets/save_asset_location.php.md)
- [api/assets/save_asset_status_type.php](/code/files/api/assets/save_asset_status_type.php.md)
- [api/assets/save_asset_tag_settings.php](/code/files/api/assets/save_asset_tag_settings.php.md)
- [api/assets/save_asset_type.php](/code/files/api/assets/save_asset_type.php.md)
- [api/assets/save_disk_hide_rule.php](/code/files/api/assets/save_disk_hide_rule.php.md)
- [api/assets/search_linkable_contracts.php](/code/files/api/assets/search_linkable_contracts.php.md)
- [api/assets/set_asset_status_type_hidden.php](/code/files/api/assets/set_asset_status_type_hidden.php.md)
- [api/assets/set_asset_type_hidden.php](/code/files/api/assets/set_asset_type_hidden.php.md)
- [api/auth/resolve_login.php](/code/files/api/auth/resolve_login.php.md)
- [api/change-management/list_linkable_tickets.php](/code/files/api/change-management/list_linkable_tickets.php.md)
- [api/contracts/customer_lookup.php](/code/files/api/contracts/customer_lookup.php.md)
- [api/contracts/get_contract.php](/code/files/api/contracts/get_contract.php.md)
- [api/contracts/save_contract.php](/code/files/api/contracts/save_contract.php.md)
- [api/cost-centres/options.php](/code/files/api/cost-centres/options.php.md)
- [api/domains/dashboard.php](/code/files/api/domains/dashboard.php.md)
- [api/domains/get.php](/code/files/api/domains/get.php.md)
- [api/domains/list.php](/code/files/api/domains/list.php.md)
- [api/domains/lookups.php](/code/files/api/domains/lookups.php.md)
- [api/integrations/list_connections.php](/code/files/api/integrations/list_connections.php.md)
- [api/problem-management/list_linkable_tickets.php](/code/files/api/problem-management/list_linkable_tickets.php.md)
- [api/projects/export.php](/code/files/api/projects/export.php.md)
- [api/projects/list.php](/code/files/api/projects/list.php.md)
- [api/projects/lookups.php](/code/files/api/projects/lookups.php.md)
- [api/projects/tools.php](/code/files/api/projects/tools.php.md)
- [api/reporting/packs/catalogue.php](/code/files/api/reporting/packs/catalogue.php.md)
- [api/rfp-builder/get_rfp.php](/code/files/api/rfp-builder/get_rfp.php.md)
- [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md)
- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md)
- [api/system/api_keys/list_keys.php](/code/files/api/system/api_keys/list_keys.php.md)
- [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)
- [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md)
- [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md)
- [api/system/email_routing_test.php](/code/files/api/system/email_routing_test.php.md)
- [api/system/get_orphaned_tickets.php](/code/files/api/system/get_orphaned_tickets.php.md)
- [api/system/get_topology.php](/code/files/api/system/get_topology.php.md)
- [api/system/global_search.php](/code/files/api/system/global_search.php.md)
- [api/tasks/collaborators.php](/code/files/api/tasks/collaborators.php.md) - in [collaboratorCandidates()](/code/functions/collaboratorCandidates.md)
- [api/tickets/delete_ticket_category.php](/code/files/api/tickets/delete_ticket_category.php.md)
- [api/tickets/delete_ticket_origin.php](/code/files/api/tickets/delete_ticket_origin.php.md)
- [api/tickets/delete_ticket_resolution_code.php](/code/files/api/tickets/delete_ticket_resolution_code.php.md)
- [api/tickets/delete_ticket_type.php](/code/files/api/tickets/delete_ticket_type.php.md)
- [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md)
- [api/tickets/get_sendable_mailboxes.php](/code/files/api/tickets/get_sendable_mailboxes.php.md)
- [api/tickets/get_ticket_classification.php](/code/files/api/tickets/get_ticket_classification.php.md)
- [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md)
- [api/tickets/get_ticket_origins.php](/code/files/api/tickets/get_ticket_origins.php.md)
- [api/tickets/get_ticket_types.php](/code/files/api/tickets/get_ticket_types.php.md)
- [api/tickets/get_time_tracking_settings.php](/code/files/api/tickets/get_time_tracking_settings.php.md)
- [api/tickets/get_triage.php](/code/files/api/tickets/get_triage.php.md)
- [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md) - in [maState()](/code/functions/maState.md)
- [api/tickets/save_reply_template.php](/code/files/api/tickets/save_reply_template.php.md)
- [api/tickets/save_ticket_asset.php](/code/files/api/tickets/save_ticket_asset.php.md)
- [api/tickets/save_ticket_category.php](/code/files/api/tickets/save_ticket_category.php.md)
- [api/tickets/save_ticket_cmdb_object.php](/code/files/api/tickets/save_ticket_cmdb_object.php.md)
- [api/tickets/save_ticket_origin.php](/code/files/api/tickets/save_ticket_origin.php.md)
- [api/tickets/save_ticket_resolution_code.php](/code/files/api/tickets/save_ticket_resolution_code.php.md)
- [api/tickets/save_ticket_task.php](/code/files/api/tickets/save_ticket_task.php.md)
- [api/tickets/save_ticket_type.php](/code/files/api/tickets/save_ticket_type.php.md)
- [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md)
- [api/tickets/set_ticket_origin_hidden.php](/code/files/api/tickets/set_ticket_origin_hidden.php.md)
- [api/tickets/set_ticket_type_hidden.php](/code/files/api/tickets/set_ticket_type_hidden.php.md)
- [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) - in [apiEffectiveCompanyScope()](/code/functions/apiEffectiveCompanyScope.md), [apiKeyCheckCompanies()](/code/functions/apiKeyCheckCompanies.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiKeyKnowledgeFilter()](/code/functions/apiKeyKnowledgeFilter.md), [apiKeyCanAccessArticle()](/code/functions/apiKeyCanAccessArticle.md)
- [asset-management/index.php](/code/files/asset-management/index.php.md)
- [asset-management/leases/index.php](/code/files/asset-management/leases/index.php.md)
- [asset-management/leases/view.php](/code/files/asset-management/leases/view.php.md)
- [cmdb/help.php](/code/files/cmdb/help.php.md)
- [domains/help.php](/code/files/domains/help.php.md)
- [includes/asset_labels.php](/code/files/includes/asset_labels.php.md) - in [assetLabelCustomFields()](/code/functions/assetLabelCustomFields.md)
- [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) - in [catalogueCreateTicketFromSubmission()](/code/functions/catalogueCreateTicketFromSubmission.md)
- [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md) - in [cmdbImpactTenantScope()](/code/functions/cmdbImpactTenantScope.md)
- [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) - in [contractAssetReachSql()](/code/functions/contractAssetReachSql.md), [contractAssetLink()](/code/functions/contractAssetLink.md), [contractAssetSearch()](/code/functions/contractAssetSearch.md)
- [includes/contract_party.php](/code/files/includes/contract_party.php.md) - in [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [contractVisibilitySqlForScope()](/code/functions/contractVisibilitySqlForScope.md), [contractListSql()](/code/functions/contractListSql.md), [contractNormaliseParty()](/code/functions/contractNormaliseParty.md)
- [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md) - in [domainApiTenantForCreate()](/code/functions/domainApiTenantForCreate.md)
- [includes/domains/customer.php](/code/files/includes/domains/customer.php.md) - in [domainCustomerPersonOk()](/code/functions/domainCustomerPersonOk.md), [domainCustomerSearch()](/code/functions/domainCustomerSearch.md)
- [includes/domains/links.php](/code/files/includes/domains/links.php.md) - in [domainLinkTargetOk()](/code/functions/domainLinkTargetOk.md), [domainLinkSearch()](/code/functions/domainLinkSearch.md), [domainLinkPickDomains()](/code/functions/domainLinkPickDomains.md)
- [includes/domains/read.php](/code/files/includes/domains/read.php.md) - in [domainApiLookups()](/code/functions/domainApiLookups.md)
- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in `KnowledgeViewer::scopeForOneCompany`, `KnowledgeViewer::scopeForAllAccessible`, `KnowledgeViewer::forApiKey`
- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapPortalProviders()](/code/functions/ldapPortalProviders.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseVisibilitySql()](/code/functions/leaseVisibilitySql.md), [leaseListSql()](/code/functions/leaseListSql.md), [leaseSave()](/code/functions/leaseSave.md), [leaseAssetsAdd()](/code/functions/leaseAssetsAdd.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [managerReachSql()](/code/functions/managerReachSql.md), [portalTicketAccess()](/code/functions/portalTicketAccess.md)
- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpToolAllowed()](/code/functions/mcpToolAllowed.md), [mcpProjectCompanySql()](/code/functions/mcpProjectCompanySql.md)
- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [messagingFindUsersByPhone()](/code/functions/messagingFindUsersByPhone.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleScope()](/code/functions/peopleScope.md), [peopleCompanyMatch()](/code/functions/peopleCompanyMatch.md), [peopleListRows()](/code/functions/peopleListRows.md), [peopleCompanies()](/code/functions/peopleCompanies.md), [companyDetail()](/code/functions/companyDetail.md)
- [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md) - in [projectApiTenantForCreate()](/code/functions/projectApiTenantForCreate.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md), [projectLinkSearch()](/code/functions/projectLinkSearch.md), [projectsPickableFor()](/code/functions/projectsPickableFor.md)
- [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) - in [projectTargetSql()](/code/functions/projectTargetSql.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)
- [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) - in [rpProjectChoices()](/code/functions/rpProjectChoices.md)
- [includes/report_packs/criteria.php](/code/files/includes/report_packs/criteria.php.md) - in [rpTenantClause()](/code/functions/rpTenantClause.md), [rpTenantLabel()](/code/functions/rpTenantLabel.md)
- [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) - in [rfpVisibilitySql()](/code/functions/rfpVisibilitySql.md), [rfpListSql()](/code/functions/rfpListSql.md), [rfpGate()](/code/functions/rfpGate.md)
- [includes/search/search.php](/code/files/includes/search/search.php.md) - in [searchScopeForAnalyst()](/code/functions/searchScopeForAnalyst.md)
- [includes/self_service_mailboxes.php](/code/files/includes/self_service_mailboxes.php.md) - in [selfServiceMailboxes()](/code/functions/selfServiceMailboxes.md)
- [includes/service_context.php](/code/files/includes/service_context.php.md) - in `ActorContext::sessionCompanyScope`
- [includes/services/analysts.php](/code/files/includes/services/analysts.php.md) - in `AnalystsService::create`
- [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md) - in `AssetFieldsService::configTenantForCreate`
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::moveToCompany`
- [includes/services/changes.php](/code/files/includes/services/changes.php.md) - in `ChangesService::linkTicket`, `ChangesService::canAccessTenantRow`, `ChangesService::loadJoined`
- [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md) - in `CmdbService::assertSameCompany`, `CmdbService::createObject`, `CmdbService::assertObjectRefsInCompany`
- [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) - in `ContractsService::ownerTenant`
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::assertScope`, `DomainsService::validateField`, `DomainsService::assertContractVisible`
- [includes/services/problems.php](/code/files/includes/services/problems.php.md) - in `ProblemsService::linkTicket`, `ProblemsService::loadProblem`, `ProblemsService::canAccessTenantRow`
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::addMember`, `ProjectToolsService::lessonToKnowledge`
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::createTaskInProject`, `ProjectsService::assertScope`, `ProjectsService::sameTenant`
- [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) - in `TasksService::assertAnalystInTaskCompany`, `TasksService::moveTaskToCompany`, `TasksService::taskAccessible`, `TasksService::resolveNewTaskTenant`, `TasksService::ticketAccessible`
- [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) - in `TicketsService::createTicket`, `TicketsService::updateTicket`, `TicketsService::loadTicket`, `TicketsService::canAccessTicket`
- [includes/sso_identity.php](/code/files/includes/sso_identity.php.md) - in [ssoApplyJitCompanies()](/code/functions/ssoApplyJitCompanies.md)
- [includes/tenancy-switcher.php](/code/files/includes/tenancy-switcher.php.md) - in [renderTenantSwitcher()](/code/functions/renderTenantSwitcher.md)
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [analystCanAccessUser()](/code/functions/analystCanAccessUser.md), [analystCanAccessProblem()](/code/functions/analystCanAccessProblem.md), [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [analystCanAccessProject()](/code/functions/analystCanAccessProject.md), [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [analystCanAccessChannel()](/code/functions/analystCanAccessChannel.md), [analystCanAssignTenant()](/code/functions/analystCanAssignTenant.md), [analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [isActiveTenantAll()](/code/functions/isActiveTenantAll.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md), [activeTenantFilter()](/code/functions/activeTenantFilter.md), [activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [analystHasAllTenantAccess()](/code/functions/analystHasAllTenantAccess.md), [requestedTenantId()](/code/functions/requestedTenantId.md), [tenantScopeSqlFor()](/code/functions/tenantScopeSqlFor.md), [knowledgeTenantFilterForCompany()](/code/functions/knowledgeTenantFilterForCompany.md), [knowledgeTenantFilter()](/code/functions/knowledgeTenantFilter.md), [analystCanAccessArticle()](/code/functions/analystCanAccessArticle.md), [resolveTenantForNewUser()](/code/functions/resolveTenantForNewUser.md), [resolveTicketTenantForEmail()](/code/functions/resolveTicketTenantForEmail.md), [resolveTicketTenantForChannel()](/code/functions/resolveTicketTenantForChannel.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md)
- [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md) - in [tenantSetting()](/code/functions/tenantSetting.md)
- [includes/ticket_links.php](/code/files/includes/ticket_links.php.md) - in [ticketLinkCreate()](/code/functions/ticketLinkCreate.md), [ticketLinkableList()](/code/functions/ticketLinkableList.md)
- [people/companies.php](/code/files/people/companies.php.md)
- [people/help.php](/code/files/people/help.php.md)
- [people/index.php](/code/files/people/index.php.md)
- [people/person.php](/code/files/people/person.php.md)
- [projects/help.php](/code/files/projects/help.php.md)
- [self-service/login.php](/code/files/self-service/login.php.md)
- [system/help/index.php](/code/files/system/help/index.php.md)
- [system/index.php](/code/files/system/index.php.md)
- [system/integrations/provider.php](/code/files/system/integrations/provider.php.md)
- [system/integrations/slack.php](/code/files/system/integrations/slack.php.md)
- [system/sso/index.php](/code/files/system/sso/index.php.md)
- [system/sso/provider.php](/code/files/system/sso/provider.php.md)
- [tasks/help.php](/code/files/tasks/help.php.md)
- [tickets/help.php](/code/files/tickets/help.php.md)

