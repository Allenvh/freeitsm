# api/assets/save_asset_tag_settings.php

> API endpoint - 103 lines

API: save the asset tag numbering settings for one company.

POST { tenant_id?, enabled, format, start, scope, next_number? }
  -> { success, settings } | { success:false, error, problems? }

The format is checked by the same AssetTagsService::validateFormat() the
live preview uses, so what the preview refuses is refused here too.
next_number can only move the counter FORWARD: a number already handed out
must never be handed out again (a tag may be on a sticker on a laptop).

## The endpoint

| | |
|---|---|
| URL | `api/assets/save_asset_tag_settings.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_TAGS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 70 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |
| 77 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), [includes/capabilities.php](/code/files/includes/capabilities.php.md), [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [setTenantSetting()](/code/functions/setTenantSetting.md)

