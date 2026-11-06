# includes/services/asset_import.php

> PHP - 1146 lines

AssetImportService — bringing assets in from a spreadsheet.

🔑 CSV AND API ARE THE SAME FEATURE. A source produces rows of
[column => value]; everything after that is shared:

    source ->  rows  ->  map  ->  reconcile  ->  validate  ->  apply  ->  log
    \_ differs _/       \_______________ all of this file _______________/

Only `readCsv()` knows what a file is. When the API puller arrives it supplies
rows the same shape and nothing below changes.

WHAT MAKES THIS HARD IS NOT THE MAPPING
---------------------------------------
The mapping screen is the easy half. These are the parts that quietly ruin a
scheduled import, and each has a deliberate answer here:

  - RECONCILIATION (§6.4): what makes an incoming row "the same printer"?
    Declared per profile as an ORDERED list. Three outcomes, never two:
    no match -> create, exactly one -> update, MORE THAN ONE -> conflict,
    log it, touch nothing. Guess at the third and run two silently
    duplicates 400 printers.
  - PREVIEW (§6.5): the same run, stopped before it writes.
  - THE HOLDING AREA: a row that cannot be imported is KEPT, with its source
    line, to be corrected and retried. Not dropped (data and reason both
    lost), not auto-created (which invents an asset type called "Televsion"
    the first time somebody typos a spreadsheet). Ed's call, and the right one.
  - ROWS THAT VANISH from the source next time (§6.6): an explicit setting.

- See: [docs/design/flexible-asset-fields.md](/code/files/docs/design/flexible-asset-fields.md.md)
## Classes

- [AssetImportService](/code/classes/AssetImportService.md) (class, line 39)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 92 | `AssetImportService::availableMatchKeys` | read [assets](/code/tables/assets.md) |  |
| 110 | `AssetImportService::schemaReady` | read [asset_import_runs](/code/tables/asset_import_runs.md) |  |
| 595 | `AssetImportService::findMatch` | read [assets](/code/tables/assets.md) |  |
| 772 | `AssetImportService::resolveOption` | read [asset_field_options](/code/tables/asset_field_options.md) |  |
| 781 | `AssetImportService::resolveOption` | **insert** [asset_field_options](/code/tables/asset_field_options.md) (`field_id`, `option_value`, `display_order`) |  |
| 819 | `AssetImportService::handleMissing` | read [asset_import_run_entries](/code/tables/asset_import_run_entries.md), read [asset_import_runs](/code/tables/asset_import_runs.md) |  |
| 841 | `AssetImportService::handleMissing` | **update** [assets](/code/tables/assets.md) (`asset_status_id`) |  |
| 864 | `AssetImportService::inactiveStatusId` | read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 889 | `AssetImportService::unresolved` | read [asset_import_run_entries](/code/tables/asset_import_run_entries.md), read [asset_import_runs](/code/tables/asset_import_runs.md) |  |
| 918 | `AssetImportService::resolveEntry` | **update** [asset_import_run_entries](/code/tables/asset_import_run_entries.md) (`resolved_datetime`) |  |
| 940 | `AssetImportService::startRun` | **insert** [asset_import_runs](/code/tables/asset_import_runs.md) (`profile_id`, `mode`, `status`, `source_name`, `stored_file`, `triggered_by_analyst_id`, `tenant_id`, `started_datetime`) |  |
| 965 | `AssetImportService::finishRun` | **update** [asset_import_runs](/code/tables/asset_import_runs.md) (`status`, `finished_datetime`, `seen_count`, `created_count`, `updated_count`, `unchanged_count`, `conflict_count`, `skipped_count`, `error_count`) |  |
| 985 | `AssetImportService::loadRun` | read [asset_import_runs](/code/tables/asset_import_runs.md) |  |
| 1007 | `AssetImportService::runEntries` | read [asset_import_run_entries](/code/tables/asset_import_run_entries.md) |  |
| 1039 | `AssetImportService::logEntry` | **insert** [asset_import_run_entries](/code/tables/asset_import_run_entries.md) (`run_id`, `row_number`, `action`, `asset_id`, `source_ref`, `display_name`, `detail`, `raw_row`) |  |
| 1063 | `AssetImportService::loadCore` | read [assets](/code/tables/assets.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md), [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md)

**Included by:** [api/assets/import_history.php](/code/files/api/assets/import_history.php.md), [api/assets/import_resolve.php](/code/files/api/assets/import_resolve.php.md), [api/assets/import_run.php](/code/files/api/assets/import_run.php.md), [api/assets/import_upload.php](/code/files/api/assets/import_upload.php.md)

**Calls:** [costCentreByCode()](/code/functions/costCentreByCode.md), [costCentreRecordTenant()](/code/functions/costCentreRecordTenant.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

