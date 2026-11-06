# activeTenantFilter()

Generic active-company list filter for any tenant-scoped table (Change Management onward). Same semantics as ticketTenantFilter but the table alias and column are parameterised so each Phase-3 module can reuse it: scopes to the analyst's ACTIVE company, and the Default company also owns NULL-tenant rows (so nothing is hidden at N=1 or from Default). Returns ['', []] at N=1.

```php
function activeTenantFilter(
    PDO $conn,
    int $analystId,
    string $alias = 't',
    string $col = 'tenant_id'
): array
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 786.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` | `'t'` | the table's alias in the calling query; '' for none |
| `$col` | `string` | `'tenant_id'` | the company column on that table (default 'tenant_id') |

**Returns:** array [sqlFragment, params] — append to a WHERE/ON clause, merge params.

## Calls

[getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/assets/find_asset.php](/code/files/api/assets/find_asset.php.md)
- [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md)
- [api/assets/get_people.php](/code/files/api/assets/get_people.php.md)
- [api/assets/get_widget_data.php](/code/files/api/assets/get_widget_data.php.md)
- [api/change-management/get_calendar_changes.php](/code/files/api/change-management/get_calendar_changes.php.md)
- [api/change-management/list.php](/code/files/api/change-management/list.php.md)
- [api/cmdb/get_classes.php](/code/files/api/cmdb/get_classes.php.md)
- [api/cmdb/get_object.php](/code/files/api/cmdb/get_object.php.md)
- [api/cmdb/get_objects.php](/code/files/api/cmdb/get_objects.php.md)
- [api/cmdb/search_objects.php](/code/files/api/cmdb/search_objects.php.md)
- [api/knowledge/gap_clusters.php](/code/files/api/knowledge/gap_clusters.php.md)
- [api/knowledge/gap_dismiss.php](/code/files/api/knowledge/gap_dismiss.php.md)
- [api/knowledge/writeup_save.php](/code/files/api/knowledge/writeup_save.php.md)
- [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md)
- [api/network-mapper/get_related_objects.php](/code/files/api/network-mapper/get_related_objects.php.md)
- [api/system/global_search.php](/code/files/api/system/global_search.php.md)
- [api/tasks/reorder.php](/code/files/api/tasks/reorder.php.md)
- [api/tasks/search_links.php](/code/files/api/tasks/search_links.php.md)
- [api/tickets/get_ticket_assets.php](/code/files/api/tickets/get_ticket_assets.php.md)
- [api/tickets/get_ticket_cmdb_objects.php](/code/files/api/tickets/get_ticket_cmdb_objects.php.md)
- [api/tickets/get_ticket_tasks.php](/code/files/api/tickets/get_ticket_tasks.php.md)
- [api/tickets/search_linkable_assets.php](/code/files/api/tickets/search_linkable_assets.php.md)
- [api/tickets/search_linkable_tasks.php](/code/files/api/tickets/search_linkable_tasks.php.md)
- [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md) - in [handleGet()](/code/functions/handleGet.md), [handleSearch()](/code/functions/handleSearch.md), [handleAddMember()](/code/functions/handleAddMember.md)
- [asset-management/labels.php](/code/files/asset-management/labels.php.md)
- [includes/asset_disk_visibility.php](/code/files/includes/asset_disk_visibility.php.md) - in [assetDiskHideRulesFor()](/code/functions/assetDiskHideRulesFor.md), [assetDiskMatchCount()](/code/functions/assetDiskMatchCount.md)
- [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md) - in [cmdbRunAudit()](/code/functions/cmdbRunAudit.md), [cmdbAuditRequiredMissing()](/code/functions/cmdbAuditRequiredMissing.md), [cmdbAuditBrokenReferences()](/code/functions/cmdbAuditBrokenReferences.md), [cmdbAuditDependencyBlank()](/code/functions/cmdbAuditDependencyBlank.md), [cmdbAuditDisconnected()](/code/functions/cmdbAuditDisconnected.md), [cmdbAuditStale()](/code/functions/cmdbAuditStale.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentEntityRegistry()](/code/functions/documentEntityRegistry.md)
- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapWindowSql()](/code/functions/gapWindowSql.md)
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::usersHoldingAssets`, `AssetsService::people`, `AssetsService::assetsForUser`
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)

