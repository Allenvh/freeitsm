# getActiveTenantId()

The analyst's current working company context.

- Single-company install → always the Default tenant.
- Multi-tenant → the session's active tenant if the analyst may access it,
  otherwise the Default tenant (if accessible), otherwise their first
  accessible tenant.

```php
function getActiveTenantId(PDO $conn, ?int $analystId = null): int
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 620.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `?int` | `null` | omit to skip access checks (returns the session value or the default tenant). |

**Returns:** `int`

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/assets/create_asset.php](/code/files/api/assets/create_asset.php.md)
- [api/assets/delete_asset_location.php](/code/files/api/assets/delete_asset_location.php.md)
- [api/assets/delete_asset_status_type.php](/code/files/api/assets/delete_asset_status_type.php.md)
- [api/assets/delete_asset_type.php](/code/files/api/assets/delete_asset_type.php.md)
- [api/assets/get_asset_label_settings.php](/code/files/api/assets/get_asset_label_settings.php.md)
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
- [api/assets/set_asset_status_type_hidden.php](/code/files/api/assets/set_asset_status_type_hidden.php.md)
- [api/assets/set_asset_type_hidden.php](/code/files/api/assets/set_asset_type_hidden.php.md)
- [api/change-management/save.php](/code/files/api/change-management/save.php.md)
- [api/contracts/customer_lookup.php](/code/files/api/contracts/customer_lookup.php.md)
- [api/contracts/save_contract.php](/code/files/api/contracts/save_contract.php.md)
- [api/documents/save.php](/code/files/api/documents/save.php.md)
- [api/problem-management/save.php](/code/files/api/problem-management/save.php.md)
- [api/projects/lookups.php](/code/files/api/projects/lookups.php.md)
- [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md)
- [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md)
- [api/tickets/create_ticket.php](/code/files/api/tickets/create_ticket.php.md)
- [api/tickets/delete_ticket_category.php](/code/files/api/tickets/delete_ticket_category.php.md)
- [api/tickets/delete_ticket_origin.php](/code/files/api/tickets/delete_ticket_origin.php.md)
- [api/tickets/delete_ticket_resolution_code.php](/code/files/api/tickets/delete_ticket_resolution_code.php.md)
- [api/tickets/delete_ticket_type.php](/code/files/api/tickets/delete_ticket_type.php.md)
- [api/tickets/get_sendable_mailboxes.php](/code/files/api/tickets/get_sendable_mailboxes.php.md)
- [api/tickets/get_ticket_classification.php](/code/files/api/tickets/get_ticket_classification.php.md)
- [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md)
- [api/tickets/get_ticket_origins.php](/code/files/api/tickets/get_ticket_origins.php.md)
- [api/tickets/get_ticket_types.php](/code/files/api/tickets/get_ticket_types.php.md)
- [api/tickets/save_reply_template.php](/code/files/api/tickets/save_reply_template.php.md)
- [api/tickets/save_ticket_category.php](/code/files/api/tickets/save_ticket_category.php.md)
- [api/tickets/save_ticket_origin.php](/code/files/api/tickets/save_ticket_origin.php.md)
- [api/tickets/save_ticket_resolution_code.php](/code/files/api/tickets/save_ticket_resolution_code.php.md)
- [api/tickets/save_ticket_type.php](/code/files/api/tickets/save_ticket_type.php.md)
- [api/tickets/set_ticket_origin_hidden.php](/code/files/api/tickets/set_ticket_origin_hidden.php.md)
- [api/tickets/set_ticket_type_hidden.php](/code/files/api/tickets/set_ticket_type_hidden.php.md)
- [asset-management/index.php](/code/files/asset-management/index.php.md)
- [asset-management/labels.php](/code/files/asset-management/labels.php.md)
- [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md) - in [domainApiTenantForCreate()](/code/functions/domainApiTenantForCreate.md)
- [includes/domains/read.php](/code/files/includes/domains/read.php.md) - in [domainApiLookups()](/code/functions/domainApiLookups.md)
- [includes/files/storage.php](/code/files/includes/files/storage.php.md) - in [filesDesktopLogo()](/code/functions/filesDesktopLogo.md)
- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in `KnowledgeViewer::forAnalyst`
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseSave()](/code/functions/leaseSave.md)
- [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md) - in [projectApiTenantForCreate()](/code/functions/projectApiTenantForCreate.md)
- [includes/reply_templates.php](/code/files/includes/reply_templates.php.md) - in [replyTemplatesVisibleTo()](/code/functions/replyTemplatesVisibleTo.md)
- [includes/report_packs/criteria.php](/code/files/includes/report_packs/criteria.php.md) - in [rpTenantClause()](/code/functions/rpTenantClause.md), [rpTenantLabel()](/code/functions/rpTenantLabel.md)
- [includes/search/search.php](/code/files/includes/search/search.php.md) - in [searchScopeForAnalyst()](/code/functions/searchScopeForAnalyst.md)
- [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md) - in `AssetFieldsService::configTenantForCreate`, `AssetFieldsService::catalogue`, `AssetFieldsService::sets`
- [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md) - in `AssetImportService::run`
- [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md) - in `CmdbService::resolveTenantForCreate`
- [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) - in `TasksService::resolveNewTaskTenant`
- [includes/tenancy-switcher.php](/code/files/includes/tenancy-switcher.php.md) - in [renderTenantSwitcher()](/code/functions/renderTenantSwitcher.md)
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [ticketTenantFilter()](/code/functions/ticketTenantFilter.md), [activeTenantFilter()](/code/functions/activeTenantFilter.md), [requestedTenantId()](/code/functions/requestedTenantId.md), [knowledgeTenantFilter()](/code/functions/knowledgeTenantFilter.md)

