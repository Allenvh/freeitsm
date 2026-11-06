# api/assets/sync_warranty_calendar.php

> API endpoint - 32 lines

API Endpoint: Resync asset warranty events to the calendar.

Called after the warranty-alert setting changes so the calendar reflects the
new choice immediately (populate when 'calendar'/'both' is selected, clear
otherwise). The work lives in includes/asset_warranty_calendar.php.

## The endpoint

| | |
|---|---|
| URL | `api/assets/sync_warranty_calendar.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_WARRANTY (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [syncAssetWarrantyCalendar()](/code/functions/syncAssetWarrantyCalendar.md)

