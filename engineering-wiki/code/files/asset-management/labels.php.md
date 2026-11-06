# asset-management/labels.php

> PHP - 512 lines

QR asset labels — printable PDF/screen sheet generator.

Designed to print accurately onto standard commercial A4 label stock.

Physical Label Layout (Constrained 5-Zone Model):
 1. Optional Header Text (e.g. Company Name / Department)
 2. QR Code (with optional centre logo badge and H error-correction capacity)
 3. Asset Tag (Always prominent primary ITAM identifier)
 4. Configurable Ordered Fields (Hostname, Serial, Model, Custom Fields, etc.)
 5. Optional Footer Text (e.g. Helpdesk Extension)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 73 |  | read [assets](/code/tables/assets.md), read [tenants](/code/tables/tenants.md) |  |
| 104 |  | read [assets](/code/tables/assets.md), read [tenants](/code/tables/tenants.md), read [asset_types](/code/tables/asset_types.md), read [asset_locations](/code/tables/asset_locations.md) |  |
| 129 |  | read [asset_field_values](/code/tables/asset_field_values.md), read [asset_fields](/code/tables/asset_fields.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), [includes/capabilities.php](/code/files/includes/capabilities.php.md), [includes/public_url.php](/code/files/includes/public_url.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md)

**Loads scripts:** [assets/js/qrcode.min.js](/code/files/assets/js/qrcode.min.js.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [assetEnsureToken()](/code/functions/assetEnsureToken.md), [assetLabelAvailableFields()](/code/functions/assetLabelAvailableFields.md), [assetLabelPrintFields()](/code/functions/assetLabelPrintFields.md), [assetLabelQrEcLevel()](/code/functions/assetLabelQrEcLevel.md), [assetLabelSettings()](/code/functions/assetLabelSettings.md), [assetLabelSheetSpecs()](/code/functions/assetLabelSheetSpecs.md), [assetLabelsSchemaReady()](/code/functions/assetLabelsSchemaReady.md), [assetLabelUrl()](/code/functions/assetLabelUrl.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

