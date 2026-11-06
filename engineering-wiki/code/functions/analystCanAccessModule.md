# analystCanAccessModule()

Module access — the enforcement half of issue #30. Mirrors the admin gate above. True if `$analystId` may use `$moduleKey` under the current most/least policy.

'system' is NOT one of these modules — it's governed by is_admin — so passing
'system' here defers to `analystIsAdmin()`, and any unknown key is denied. The
heavy lifting (union/intersection across the analyst + their teams) lives in the
single choke-point `getAnalystAllowedModules()`, which this just consults.

```php
function analystCanAccessModule(PDO $conn, int $analystId, string $moduleKey): bool
```

Defined in [includes/functions.php](/code/files/includes/functions.php.md), line 284.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$moduleKey` | `string` |  | the module key, e.g. 'tickets', 'assets', 'files'; 'system' means administrators only |

**Returns:** `bool`

## Calls

[analystIsAdmin()](/code/functions/analystIsAdmin.md), [getAnalystAllowedModules()](/code/functions/getAnalystAllowedModules.md)

## Called from

- [api/assets/get_asset_contracts.php](/code/files/api/assets/get_asset_contracts.php.md)
- [api/assets/search_linkable_contracts.php](/code/files/api/assets/search_linkable_contracts.php.md)
- [api/domains/links.php](/code/files/api/domains/links.php.md)
- [api/domains/people.php](/code/files/api/domains/people.php.md)
- [api/files/shortcut.php](/code/files/api/files/shortcut.php.md)
- [api/forms/image.php](/code/files/api/forms/image.php.md)
- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [api/projects/lookups.php](/code/files/api/projects/lookups.php.md)
- [api/rfp-builder/generate_framing.php](/code/files/api/rfp-builder/generate_framing.php.md)
- [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md)
- [api/rfp-builder/restyle_framing.php](/code/files/api/rfp-builder/restyle_framing.php.md)
- [api/rfp-builder/restyle_section.php](/code/files/api/rfp-builder/restyle_section.php.md)
- [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md)
- [api/self-service/get_recording.php](/code/files/api/self-service/get_recording.php.md)
- [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md)
- [api/tasks/contractors.php](/code/files/api/tasks/contractors.php.md)
- [api/war-room/alerts.php](/code/files/api/war-room/alerts.php.md)
- [asset-management/index.php](/code/files/asset-management/index.php.md)
- [asset-management/scan.php](/code/files/asset-management/scan.php.md)
- [asset-management/users.php](/code/files/asset-management/users.php.md)
- [change-management/index.php](/code/files/change-management/index.php.md)
- [cmdb/object.php](/code/files/cmdb/object.php.md)
- [contracts/suppliers/view/index.php](/code/files/contracts/suppliers/view/index.php.md)
- [contracts/view.php](/code/files/contracts/view.php.md)
- [domains/view.php](/code/files/domains/view.php.md)
- [files/index.php](/code/files/files/index.php.md)
- [includes/domains/links.php](/code/files/includes/domains/links.php.md) - in [domainLinkKindAllowed()](/code/functions/domainLinkKindAllowed.md), [domainLinkDomainTenant()](/code/functions/domainLinkDomainTenant.md), [domainsForContract()](/code/functions/domainsForContract.md)
- [includes/domains/read.php](/code/files/includes/domains/read.php.md) - in [domainApiLookups()](/code/functions/domainApiLookups.md)
- [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) - in [domainStatusState()](/code/functions/domainStatusState.md)
- [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md) - in [filesArticleTarget()](/code/functions/filesArticleTarget.md)
- [includes/functions.php](/code/files/includes/functions.php.md) - in [requireModuleAccess()](/code/functions/requireModuleAccess.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)
- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpToolAllowed()](/code/functions/mcpToolAllowed.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleCompanyCards()](/code/functions/peopleCompanyCards.md), [personDetail()](/code/functions/personDetail.md), [companyDetail()](/code/functions/companyDetail.md), [peopleForms()](/code/functions/peopleForms.md), [peopleCanSeeSuppliers()](/code/functions/peopleCanSeeSuppliers.md), [supplierDetail()](/code/functions/supplierDetail.md), [supplierContactDetail()](/code/functions/supplierContactDetail.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)
- [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) - in [projectAssistOverview()](/code/functions/projectAssistOverview.md)
- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatTurn()](/code/functions/projectChatTurn.md), [projectChatApplyOne()](/code/functions/projectChatApplyOne.md)
- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)
- [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) - in [projectCapacity()](/code/functions/projectCapacity.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkKindAllowed()](/code/functions/projectLinkKindAllowed.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabels()](/code/functions/recentTrailLabels.md), [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)
- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreview()](/code/functions/recordPreview.md)
- [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md) - in [rpBlockData()](/code/functions/rpBlockData.md), [rpCatalogue()](/code/functions/rpCatalogue.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::addContractorMember`, `ProjectToolsService::lessonToKnowledge`, `ProjectToolsService::issueToTicket`, `ProjectToolsService::assertAssets`, `ProjectToolsService::saveBudgetLine`, `ProjectToolsService::announce`, `ProjectToolsService::withdrawAnnouncement`, `ProjectToolsService::announcements`
- [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) - in `TasksService::contractorAllowed`
- [includes/settings_keys.php](/code/files/includes/settings_keys.php.md) - in [analystCanWriteSettingKey()](/code/functions/analystCanWriteSettingKey.md)
- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotToolAllowed()](/code/functions/warbotToolAllowed.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)
- [knowledge/index.php](/code/files/knowledge/index.php.md)
- [people/includes/header.php](/code/files/people/includes/header.php.md)
- [people/person.php](/code/files/people/person.php.md)
- [problem-management/index.php](/code/files/problem-management/index.php.md)
- [tickets/index.php](/code/files/tickets/index.php.md)
- [tickets/users.php](/code/files/tickets/users.php.md)

