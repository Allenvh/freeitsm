# includes/services/asset_fields.php

> PHP - 941 lines

AssetFieldsService — user-defined fields on assets.

The `assets` table describes a Windows PC, because that is what the agent
reports. This is how an install records everything else: printers, webcams,
headsets, televisions — with whichever columns that particular customer
cares about, declared through the UI rather than by a schema change.

WHAT LIVES WHERE
----------------
- The TYPE rules (what a number accepts, how a date parses, what a reference
  may point at) are in includes/typed_fields.php, shared with the CMDB.
- WHICH fields a given asset has is this file's job, and it is the one thing
  that could not be shared: the CMDB resolves properties from a class, where
  an asset resolves them from the field sets attached to its TYPE plus any
  attached to that ONE asset.
- Authorisation stays with the caller. Nothing here decides who may see an
  asset; it decides what an asset's fields are.

🔑 A field is defined ONCE, install-wide (asset_fields), and reused by every
set that wants it. That is what makes "find the thing with serial X" and
"every IP address in the estate" answerable at all — fourteen private copies
of "Serial Number" could never be queried together.

- See: [docs/design/flexible-asset-fields.md](/code/files/docs/design/flexible-asset-fields.md.md)
## Classes

- [AssetFieldsService](/code/classes/AssetFieldsService.md) (class, line 33)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 69 | `AssetFieldsService::valueSchema` | read [asset_field_options](/code/tables/asset_field_options.md) |  |
| 84 | `AssetFieldsService::schemaReady` | read [asset_field_values](/code/tables/asset_field_values.md) |  |
| 119 | `AssetFieldsService::setsForAsset` | read [asset_type_field_sets](/code/tables/asset_type_field_sets.md), read [asset_field_sets](/code/tables/asset_field_sets.md) |  |
| 133 | `AssetFieldsService::setsForAsset` | read [asset_field_set_assets](/code/tables/asset_field_set_assets.md), read [asset_field_sets](/code/tables/asset_field_sets.md) |  |
| 184 | `AssetFieldsService::fieldsForSets` | read [asset_field_set_fields](/code/tables/asset_field_set_fields.md), read [asset_fields](/code/tables/asset_fields.md), read [asset_field_sets](/code/tables/asset_field_sets.md) |  |
| 364 | `AssetFieldsService::assertUnique` | read [asset_field_values](/code/tables/asset_field_values.md), read [assets](/code/tables/assets.md) |  |
| 403 | `AssetFieldsService::logChanges` | **insert** [asset_history](/code/tables/asset_history.md) (`asset_id`, `analyst_id`, `field_name`, `old_value`, `new_value`) |  |
| 449 | `AssetFieldsService::attachSetToAsset` | **insert** [asset_field_set_assets](/code/tables/asset_field_set_assets.md) (`asset_id`, `set_id`, `created_by_analyst_id`) |  |
| 469 | `AssetFieldsService::detachSetFromAsset` | **delete** [asset_field_set_assets](/code/tables/asset_field_set_assets.md) |  |
| 522 | `AssetFieldsService::saveField` | **update** [asset_fields](/code/tables/asset_fields.md) (`label`, `field_type`, `config`, `help_text`, `is_unique`, `is_searchable`, `show_in_list`) |  |
| 546 | `AssetFieldsService::saveField` | **insert** [asset_fields](/code/tables/asset_fields.md) (`field_key`, `label`, `field_type`, `config`, `help_text`, `is_unique`, `is_searchable`, `show_in_list`, `tenant_id`) |  |
| 576 | `AssetFieldsService::deleteField` | **update** [asset_fields](/code/tables/asset_fields.md) (`is_deleted`) |  |
| 577 | `AssetFieldsService::deleteField` | **delete** [asset_field_set_fields](/code/tables/asset_field_set_fields.md) |  |
| 597 | `AssetFieldsService::saveSet` | **update** [asset_field_sets](/code/tables/asset_field_sets.md) (`name`, `description`, `display_order`) |  |
| 602 | `AssetFieldsService::saveSet` | **insert** [asset_field_sets](/code/tables/asset_field_sets.md) (`name`, `description`, `display_order`, `tenant_id`) |  |
| 619 | `AssetFieldsService::deleteSet` | **update** [asset_field_sets](/code/tables/asset_field_sets.md) (`is_deleted`) |  |
| 620 | `AssetFieldsService::deleteSet` | **delete** [asset_type_field_sets](/code/tables/asset_type_field_sets.md) |  |
| 621 | `AssetFieldsService::deleteSet` | **delete** [asset_field_set_assets](/code/tables/asset_field_set_assets.md) |  |
| 642 | `AssetFieldsService::setSetFields` | **delete** [asset_field_set_fields](/code/tables/asset_field_set_fields.md) |  |
| 644 | `AssetFieldsService::setSetFields` | **insert** [asset_field_set_fields](/code/tables/asset_field_set_fields.md) (`set_id`, `field_id`, `sort_order`, `is_required`, `default_value`) |  |
| 685 | `AssetFieldsService::setTypeSets` | **delete** [asset_type_field_sets](/code/tables/asset_type_field_sets.md) |  |
| 687 | `AssetFieldsService::setTypeSets` | **insert** [asset_type_field_sets](/code/tables/asset_type_field_sets.md) (`asset_type_id`, `set_id`, `sort_order`) |  |
| 761 | `AssetFieldsService::assertKeyFree` | read [asset_fields](/code/tables/asset_fields.md) |  |
| 765 | `AssetFieldsService::assertKeyFree` | read [asset_fields](/code/tables/asset_fields.md) |  |
| 806 | `AssetFieldsService::saveOptions` | **delete** [asset_field_options](/code/tables/asset_field_options.md) |  |
| 809 | `AssetFieldsService::saveOptions` | **delete** [asset_field_options](/code/tables/asset_field_options.md) |  |
| 811 | `AssetFieldsService::saveOptions` | **insert** [asset_field_options](/code/tables/asset_field_options.md) (`field_id`, `option_value`, `colour`, `display_order`) |  |
| 832 | `AssetFieldsService::fieldHasValues` | read [asset_field_values](/code/tables/asset_field_values.md) |  |
| 844 | `AssetFieldsService::loadField` | read [asset_fields](/code/tables/asset_fields.md) |  |
| 907 | `AssetFieldsService::loadAsset` | read [assets](/code/tables/assets.md) |  |
| 923 | `AssetFieldsService::loadSet` | read [asset_field_sets](/code/tables/asset_field_sets.md) |  |
| 935 | `AssetFieldsService::tenantOfAsset` | read [assets](/code/tables/assets.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/typed_fields.php](/code/files/includes/typed_fields.php.md)

**Included by:** [api/assets/delete_asset_field.php](/code/files/api/assets/delete_asset_field.php.md), [api/assets/delete_asset_field_set.php](/code/files/api/assets/delete_asset_field_set.php.md), [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md), [api/assets/get_asset_custom_fields.php](/code/files/api/assets/get_asset_custom_fields.php.md), [api/assets/get_asset_fields.php](/code/files/api/assets/get_asset_fields.php.md), [api/assets/save_asset_custom_fields.php](/code/files/api/assets/save_asset_custom_fields.php.md), [api/assets/save_asset_field.php](/code/files/api/assets/save_asset_field.php.md), [api/assets/save_asset_field_set.php](/code/files/api/assets/save_asset_field_set.php.md), [api/assets/save_type_field_sets.php](/code/files/api/assets/save_type_field_sets.php.md), [api/assets/set_asset_field_set.php](/code/files/api/assets/set_asset_field_set.php.md), [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md), [asset-management/table.php](/code/files/asset-management/table.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md)

**Calls:** [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

