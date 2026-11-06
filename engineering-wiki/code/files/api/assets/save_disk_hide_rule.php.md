# api/assets/save_disk_hide_rule.php

> API endpoint - 114 lines

API: hide or show a physical drive (#97).

POST { asset_id, model, size_bytes, hidden, everywhere } -> { success, hidden }

  hidden:     true to hide, false to show again
  everywhere: true  -> one estate-wide rule (asset_id NULL)
              false -> a rule for this asset only

SHOWING IS DELIBERATELY BROADER THAN HIDING. Hiding writes one rule of the
scope you asked for; showing deletes EVERY rule that matches the drive,
estate-wide ones included. The alternative — an "unhide" exception layered
over a hide rule — is a second, invisible set of rules to reason about, and
"Show" that leaves the drive hidden is a button that does not work. The
screen says what it is about to do, using the count, before it does it.

🔴 The rule describes the DRIVE (model + exact size), never the disk row:
asset_physical_disks is cleared and rewritten on every agent report and the
ids are reissued. See includes/asset_disk_visibility.php.

## The endpoint

| | |
|---|---|
| URL | `api/assets/save_disk_hide_rule.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 85 |  | **insert** [asset_disk_hide_rules](/code/tables/asset_disk_hide_rules.md) (`tenant_id`, `asset_id`, `model`, `size_bytes`, `created_by_analyst_id`, `created_datetime`) |  |
| 106 |  | **delete** [asset_disk_hide_rules](/code/tables/asset_disk_hide_rules.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/asset_disk_visibility.php](/code/files/includes/asset_disk_visibility.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [assetDiskHideRulesFor()](/code/functions/assetDiskHideRulesFor.md), [assetDiskHideSchemaReady()](/code/functions/assetDiskHideSchemaReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

