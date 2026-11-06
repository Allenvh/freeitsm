# api/assets/get_asset_tag_settings.php

> API endpoint - 59 lines

API: the asset tag numbering settings for one company, and the number the next generated tag will carry.

GET ?tenant_id= -> { success, settings: {enabled, format, start, scope, next_number, examples} }

Enabled, format and start may differ per company; scope is install-wide.

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_tag_settings.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_TAGS (requireCapabilityJson) |
| Reads | `GET tenant_id` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/capabilities.php](/code/files/includes/capabilities.php.md), [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

