# api/assets/get_asset_label_settings.php

> API endpoint - 58 lines

API Endpoint: Get physical asset label & QR branding configuration.

GET /api/assets/get_asset_label_settings.php

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_label_settings.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_LABELS (requireCapabilityJson) |
| Reads | `GET tenant_id` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), [includes/capabilities.php](/code/files/includes/capabilities.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [assetLabelAvailableFields()](/code/functions/assetLabelAvailableFields.md), [assetLabelPrintFields()](/code/functions/assetLabelPrintFields.md), [assetLabelSettings()](/code/functions/assetLabelSettings.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

