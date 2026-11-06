# includes/projects/links.php

> PHP - 525 lines

Projects, joined to the rest of FreeITSM (3.2.0) - the ONE place that decides who may see a link and who may make one. Modelled on includes/domains/links.php.

Seven kinds of link, each a plain join table:

  asset    project_assets              the equipment the project works on
  change   project_changes             changes the project raises
  ticket   project_tickets             tickets about it - including the ones it caused
  contract project_contracts           supplier contracts it relies on
  cmdb     project_cmdb_objects        configuration items it touches
  article  project_knowledge_articles  runbooks and write-ups
  problem  project_problems            problems it exists to fix (3.3.0)

🔑 THE RULE, for every kind: a link is only shown, made or removed between two
records the analyst can already open - Projects plus the other module, and the
record itself (its company, or for an article its knowledge permissions). A
link the reader cannot see both ends of does not exist for them: it is left
out, never shown as "hidden".

🔑 And a link never crosses companies. An asset, change, ticket or CI must be
in the project's own company (and so must a problem). Contracts and knowledge articles are not
company records (contracts are install-wide; articles have their own audience
model), so they are judged by their own permissions only.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectLinkKinds()](/code/functions/projectLinkKinds.md) | 36 | kind => table, the column naming the other record, the module it belongs to, and its company table (null = not a company record). |
| [projectLinksReady()](/code/functions/projectLinksReady.md) | 56 | Before Database Verification the tables are not there: no links, never an error. With `$kind`, that one kind's table (3.3.0) - so an upgrade that adds a kind (problem) hides only that kind until Verification, not every link. |
| [projectLinkKind()](/code/functions/projectLinkKind.md) | 78 | The definition (table, column, module) of one link kind; throws a validation error for an unknown kind. |
| [projectLinkKindAllowed()](/code/functions/projectLinkKindAllowed.md) | 90 | May this analyst use links of this kind at all? (Projects AND the other module.) |
| [projectLinkTenantOf()](/code/functions/projectLinkTenantOf.md) | 104 | A record's company with NULL read as the Default company. |
| [projectLinkProjectTenant()](/code/functions/projectLinkProjectTenant.md) | 114 | The project's company (Default for NULL), or not-found for a project out of reach. |
| [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md) | 129 | Is the OTHER end of a link one this analyst may see, and (for company records) in the project's company? |
| [projectLinkDescribe()](/code/functions/projectLinkDescribe.md) | 182 | Linked records, shaped for display: id, label, a sub-line, a status and a link. |
| [projectLinks()](/code/functions/projectLinks.md) | 253 | Everything linked to one project, per kind, for this analyst: a kind they cannot use is left out entirely; within a kind, rows they cannot see are dropped. |
| [projectLinkAdd()](/code/functions/projectLinkAdd.md) | 279 | Links a record (asset, ticket, contract...) to a project and records it in the project's history. |
| [projectLinkRemove()](/code/functions/projectLinkRemove.md) | 307 | Removes a link between a project and a record and records it in the project's history. |
| [projectLinkSearch()](/code/functions/projectLinkSearch.md) | 333 | Records of one kind that could be linked: matching `$q`, in the project's company where that applies, visible to the analyst, not already linked. At most 20. |
| [projectLinkProjectRows()](/code/functions/projectLinkProjectRows.md) | 397 | Projects by id, shaped for a list on another module's page. |
| [projectsLinkedTo()](/code/functions/projectsLinkedTo.md) | 426 | The projects one record (asset, change, ticket, contract, CI, article) is linked to that this analyst may see. Empty - never an error - when they cannot open Projects, the other module, or the record, so a page can call it unconditionally. |
| [projectsPickableFor()](/code/functions/projectsPickableFor.md) | 449 | The picker on the record's page: live projects this analyst may CHANGE, in the record's company (for company records), not already linked, matching `$q`. At most 20. |
| [projectLinksDeleteAll()](/code/functions/projectLinksDeleteAll.md) | 478 | Remove every link a project holds - called when the project is deleted. |
| [projectUnapprovedChanges()](/code/functions/projectUnapprovedChanges.md) | 499 | The project's linked changes that are still open and NOT approved - what a stage gate warns about (3.2.0, "going live safely"). |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 144 | `projectLinkTargetOk` | read [tickets](/code/tables/tickets.md) |  |
| 189 | `projectLinkDescribe` | read [assets](/code/tables/assets.md), read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 198 | `projectLinkDescribe` | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 207 | `projectLinkDescribe` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 216 | `projectLinkDescribe` | read [contracts](/code/tables/contracts.md), read [contract_statuses](/code/tables/contract_statuses.md), read [suppliers](/code/tables/suppliers.md) |  |
| 227 | `projectLinkDescribe` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 233 | `projectLinkDescribe` | read [problems](/code/tables/problems.md), read [problem_statuses](/code/tables/problem_statuses.md) |  |
| 241 | `projectLinkDescribe` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 346 | `projectLinkSearch` | read [assets](/code/tables/assets.md) |  |
| 350 | `projectLinkSearch` | read [changes](/code/tables/changes.md) |  |
| 354 | `projectLinkSearch` | read [tickets](/code/tables/tickets.md) |  |
| 358 | `projectLinkSearch` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 362 | `projectLinkSearch` | read [problems](/code/tables/problems.md) |  |
| 366 | `projectLinkSearch` | read [contracts](/code/tables/contracts.md) |  |
| 372 | `projectLinkSearch` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 402 | `projectLinkProjectRows` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 457 | `projectsPickableFor` | read [projects](/code/tables/projects.md) |  |
| 503 | `projectUnapprovedChanges` | read [project_changes](/code/tables/project_changes.md), read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/entity_links.php](/code/files/includes/entity_links.php.md), [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/projects/settings.php](/code/files/includes/projects/settings.php.md)

**Included by:** [api/projects/get.php](/code/files/api/projects/get.php.md), [api/projects/links.php](/code/files/api/projects/links.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystCanAccessProblem()](/code/functions/analystCanAccessProblem.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [contractCanView()](/code/functions/contractCanView.md), [entityLink()](/code/functions/entityLink.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [knowledgeCanRead()](/code/functions/knowledgeCanRead.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [projectCanChange()](/code/functions/projectCanChange.md), [projectColourHex()](/code/functions/projectColourHex.md), [projectDecorate()](/code/functions/projectDecorate.md), [projectHealthConfig()](/code/functions/projectHealthConfig.md), [projectTaskStats()](/code/functions/projectTaskStats.md)

