# getTenantConfigRows()

Resolve a per-company config list under the "add + hide" override model (design §7) — the single primitive every overridable list-setting routes through (ticket types, ticket origins, departments, …).

Returns the rows of `$table` that should be visible to `$tenantId`:
  - global defaults (`tenant_id IS NULL`) that this company has NOT hidden
    (via tenant_config_hidden), PLUS
  - the company's own added rows (`tenant_id = `$tenantId``).

Deliberately defensive:
  - **Single-company install** (or a part-migrated table that lacks the
    `tenant_id` column / the hidden table) → degrades to "all rows", i.e.
    EXACTLY today's behaviour. So this is safe to call anywhere during rollout.
  - Identifiers (`$table`, `$entityType`) are developer-supplied literals; they're
    still regex-guarded so this can never become an injection vector.

```php
function getTenantConfigRows(
    PDO $conn,
    string $table,
    string $entityType,
    int $tenantId,
    string $cols = '*',
    string $activeWhere = '',
    string $orderBy = 'display_order, name'
): array
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 1387.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the config table (e.g. 'ticket_types') |
| `$entityType` | `string` |  | key used in tenant_config_hidden (e.g. 'ticket_type') |
| `$tenantId` | `int` |  | the company to resolve for (usually the active tenant) |
| `$cols` | `string` | `'*'` | columns to select (default '*') |
| `$activeWhere` | `string` | `''` | extra always-applied filter, no leading AND (e.g. 'is_active = 1'); must be unambiguous (no alias) |
| `$orderBy` | `string` | `'display_order, name'` | ORDER BY body, no keyword (default 'display_order, name') |

**Returns:** array rows as associative arrays

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1403 | `getTenantConfigRows` | read [tenant_config_hidden](/code/tables/tenant_config_hidden.md) |  |

## Calls

[isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/assets/create_asset.php](/code/files/api/assets/create_asset.php.md)
- [api/assets/get_asset_status_types.php](/code/files/api/assets/get_asset_status_types.php.md)
- [api/assets/get_asset_types.php](/code/files/api/assets/get_asset_types.php.md)
- [api/tickets/get_ticket_classification.php](/code/files/api/tickets/get_ticket_classification.php.md)
- [api/tickets/get_ticket_origins.php](/code/files/api/tickets/get_ticket_origins.php.md)
- [api/tickets/get_ticket_types.php](/code/files/api/tickets/get_ticket_types.php.md)
- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiAssetTypesList()](/code/functions/apiAssetTypesList.md), [apiAssetStatusesList()](/code/functions/apiAssetStatusesList.md), [apiAssetLocationsList()](/code/functions/apiAssetLocationsList.md)
- [api/v1/resources/reference.php](/code/files/api/v1/resources/reference.php.md) - in [apiTenantConfigList()](/code/functions/apiTenantConfigList.md)
- [includes/reply_templates.php](/code/files/includes/reply_templates.php.md) - in [replyTemplatesVisibleTo()](/code/functions/replyTemplatesVisibleTo.md)
- [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md) - in `AssetFieldsService::catalogue`, `AssetFieldsService::sets`
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::moveToCompany`
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [getTenantConfigRowsByCompany()](/code/functions/getTenantConfigRowsByCompany.md)
- [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md) - in [ticketCategoriesResolved()](/code/functions/ticketCategoriesResolved.md), [ticketResolutionCodesResolved()](/code/functions/ticketResolutionCodesResolved.md)

