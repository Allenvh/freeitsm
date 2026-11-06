# api/assets/get_assets.php

> API endpoint - 395 lines

API Endpoint: Get assets list Returns assets with optional search filtering and user counts

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_assets.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET search` |
| Called from | [assets/js/asset-table.js](/code/files/assets/js/asset-table.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 72 |  | read [cost_centres](/code/tables/cost_centres.md) |  |
| 136 |  | read [assets](/code/tables/assets.md), read [users_assets](/code/tables/users_assets.md) |  |
| 146 |  | read [asset_types](/code/tables/asset_types.md) |  |
| 149 |  | read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 153 |  | read [assets](/code/tables/assets.md) |  |
| 221 |  | read [asset_locations](/code/tables/asset_locations.md) |  |
| 225 |  | read [contract_assets](/code/tables/contract_assets.md), read [contracts](/code/tables/contracts.md) |  |
| 262 |  | read [asset_locations](/code/tables/asset_locations.md) |  |
| 287 |  | read [suppliers](/code/tables/suppliers.md) |  |
| 309 |  | read [asset_import_run_entries](/code/tables/asset_import_run_entries.md), read [asset_import_runs](/code/tables/asset_import_runs.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md), [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [assetLabelsSchemaReady()](/code/functions/assetLabelsSchemaReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [costCentreReady()](/code/functions/costCentreReady.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [t()](/code/functions/t.md), [tenancyColumnExists()](/code/functions/tenancyColumnExists.md)

