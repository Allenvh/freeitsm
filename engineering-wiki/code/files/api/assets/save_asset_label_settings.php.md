# api/assets/save_asset_label_settings.php

> API endpoint - 184 lines

API Endpoint: Save physical asset label & QR branding configuration.

POST /api/assets/save_asset_label_settings.php

## The endpoint

| | |
|---|---|
| URL | `api/assets/save_asset_label_settings.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_LABELS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 144 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), [includes/capabilities.php](/code/files/includes/capabilities.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/branding.php](/code/files/includes/branding.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [assetLabelAvailableFields()](/code/functions/assetLabelAvailableFields.md), [assetLabelPrintFields()](/code/functions/assetLabelPrintFields.md), [assetLabelSettings()](/code/functions/assetLabelSettings.md), [brandingPathIsSafe()](/code/functions/brandingPathIsSafe.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [setTenantSetting()](/code/functions/setTenantSetting.md), [tenantSetting()](/code/functions/tenantSetting.md), [tenantSettingForget()](/code/functions/tenantSettingForget.md), [uploadPrepareWebServableDir()](/code/functions/uploadPrepareWebServableDir.md), [uploadStoreFile()](/code/functions/uploadStoreFile.md)

