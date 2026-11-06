# api/cmdb/save_class_property.php

> API endpoint - 142 lines

API: Create or update a property definition on a class. Handles dropdown options inline (passed as `options` array; we wipe and re-insert).

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/save_class_property.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson); Permission: Cap::CMDB_CLASSES (requireCapabilityJson) |
| Called from | [cmdb/object.js](/code/files/cmdb/object.js.md), [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [slugifyProp()](/code/functions/slugifyProp.md) | 29 | Turns a property name into a lower-case key of letters, digits and underscores, falling back to 'property' when nothing is left. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 75 |  | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 84 |  | **insert** [cmdb_class_properties](/code/tables/cmdb_class_properties.md) (`class_id`, `property_key`, `label`, `property_type`, `target_class_id`, `is_required`, `spreads_impact`, `display_order`, `created_datetime`) |  |
| 92 |  | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 99 |  | **update** [cmdb_class_properties](/code/tables/cmdb_class_properties.md) (`property_key`, `label`, `property_type`, `target_class_id`, `is_required`, `spreads_impact`, `display_order`) |  |
| 111 |  | **delete** [cmdb_class_property_options](/code/tables/cmdb_class_property_options.md) |  |
| 112 |  | **insert** [cmdb_class_property_options](/code/tables/cmdb_class_property_options.md) (`property_id`, `option_value`, `colour`, `display_order`) |  |
| 132 |  | **delete** [cmdb_class_property_options](/code/tables/cmdb_class_property_options.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

