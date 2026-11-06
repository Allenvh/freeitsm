# getWatchtowerData()

`$analystId` is optional and only used to scope the Knowledge card to the company the analyst has switched to. Omitted (or 0) = unscoped, which is the behaviour every other card on this dashboard still has.

```php
function getWatchtowerData($conn, $analystId = 0, $scope = WT_SCOPE_ALL)
```

Defined in [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md), line 20.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` |  | `0` | the analyst viewing, for the Knowledge card's company and the scope; 0 = unscoped |
| `$scope` |  | `WT_SCOPE_ALL` | WT_SCOPE_MINE, WT_SCOPE_TEAM or WT_SCOPE_ALL; anything else, or no analyst, means all |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 169 | `getWatchtowerData` | read [ticket_statuses](/code/tables/ticket_statuses.md), read [tickets](/code/tables/tickets.md) |  |
| 196 | `getWatchtowerData` | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 201 | `getWatchtowerData` | read [tickets](/code/tables/tickets.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 214 | `getWatchtowerData` | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 216 | `getWatchtowerData` | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 229 | `getWatchtowerData` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 242 | `getWatchtowerData` | read [system_settings](/code/tables/system_settings.md) |  |
| 249 | `getWatchtowerData` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_audit](/code/tables/ticket_audit.md) |  |
| 291 | `getWatchtowerData` | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 315 | `getWatchtowerData` | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 328 | `getWatchtowerData` | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 349 | `getWatchtowerData` | read [change_statuses](/code/tables/change_statuses.md), read [changes](/code/tables/changes.md) |  |
| 376 | `getWatchtowerData` | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 399 | `getWatchtowerData` | read [calendar_events](/code/tables/calendar_events.md) |  |
| 409 | `getWatchtowerData` | read [calendar_events](/code/tables/calendar_events.md) |  |
| 435 | `getWatchtowerData` | read [status_services](/code/tables/status_services.md), read [status_incident_services](/code/tables/status_incident_services.md), read [status_incidents](/code/tables/status_incidents.md), read [service_impact_levels](/code/tables/service_impact_levels.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 468 | `getWatchtowerData` | read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 484 | `getWatchtowerData` | read [contracts](/code/tables/contracts.md) |  |
| 531 | `getWatchtowerData` | read [system_settings](/code/tables/system_settings.md) |  |
| 537 | `getWatchtowerData` | read [software_licences](/code/tables/software_licences.md) |  |
| 541 | `getWatchtowerData` | read [software_licences](/code/tables/software_licences.md) |  |
| 547 | `getWatchtowerData` | read [software_licences](/code/tables/software_licences.md) |  |
| 559 | `getWatchtowerData` | read [software_licences](/code/tables/software_licences.md) |  |
| 610 | `getWatchtowerData` | read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 644 | `getWatchtowerData` | read [domain_status_services](/code/tables/domain_status_services.md), read [domains](/code/tables/domains.md), read [status_services](/code/tables/status_services.md), read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 688 | `getWatchtowerData` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 699 | `getWatchtowerData` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 713 | `getWatchtowerData` | read [assets](/code/tables/assets.md) |  |
| 716 | `getWatchtowerData` | read [assets](/code/tables/assets.md) |  |
| 727 | `getWatchtowerData` | read [system_settings](/code/tables/system_settings.md) |  |
| 735 | `getWatchtowerData` | read [assets](/code/tables/assets.md) |  |
| 753 | `getWatchtowerData` | read [system_settings](/code/tables/system_settings.md) |  |
| 764 | `getWatchtowerData` | read [assets](/code/tables/assets.md) |  |
| 786 | `getWatchtowerData` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 796 | `getWatchtowerData` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 813 | `getWatchtowerData` | read [task_statuses](/code/tables/task_statuses.md), read [tasks](/code/tables/tasks.md) |  |
| 863 | `getWatchtowerData` | read [projects](/code/tables/projects.md) |  |
| 882 | `getWatchtowerData` | read [project_stages](/code/tables/project_stages.md) |  |
| 889 | `getWatchtowerData` | read [project_milestones](/code/tables/project_milestones.md) |  |
| 921 | `getWatchtowerData` | read [workflow_executions](/code/tables/workflow_executions.md) |  |
| 926 | `getWatchtowerData` | read [workflow_executions](/code/tables/workflow_executions.md) |  |
| 934 | `getWatchtowerData` | read [workflow_executions](/code/tables/workflow_executions.md), read [workflows](/code/tables/workflows.md) |  |
| 952 | `getWatchtowerData` | read [webhook_deliveries](/code/tables/webhook_deliveries.md) |  |

## Calls

[activeTenantFilter()](/code/functions/activeTenantFilter.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [domainSettings()](/code/functions/domainSettings.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [leaseGoneBackSql()](/code/functions/leaseGoneBackSql.md), [leaseReady()](/code/functions/leaseReady.md), [naive_now()](/code/functions/naive_now.md), [naive_today_sql()](/code/functions/naive_today_sql.md), [projectDecorate()](/code/functions/projectDecorate.md), [projectExceptionColumns()](/code/functions/projectExceptionColumns.md), [projectHealthConfig()](/code/functions/projectHealthConfig.md), [projectTaskStats()](/code/functions/projectTaskStats.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md), [statusPlannedDue()](/code/functions/statusPlannedDue.md), [wtIdListSql()](/code/functions/wtIdListSql.md), [wtItemMembers()](/code/functions/wtItemMembers.md), [wtMorningCheckScope()](/code/functions/wtMorningCheckScope.md), [wtScopeClause()](/code/functions/wtScopeClause.md), [wtScopeIsValid()](/code/functions/wtScopeIsValid.md), [wtVisibleCards()](/code/functions/wtVisibleCards.md)

## Called from

- [api/watchtower/get_dashboard.php](/code/files/api/watchtower/get_dashboard.php.md)
- [api/watchtower/get_dashboard_ext.php](/code/files/api/watchtower/get_dashboard_ext.php.md)

