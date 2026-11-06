# api/intune/list_app_sync_jobs.php

> API endpoint - 59 lines

API: list recent InTune app-sync jobs for the settings page.

## The endpoint

| | |
|---|---|
| URL | `api/intune/list_app_sync_jobs.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_INTUNE (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [intune_app_sync_jobs](/code/tables/intune_app_sync_jobs.md) |  |
| 39 |  | read [assets](/code/tables/assets.md), read [intune_devices](/code/tables/intune_devices.md), read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

