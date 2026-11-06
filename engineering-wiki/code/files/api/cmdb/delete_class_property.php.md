# api/cmdb/delete_class_property.php

> API endpoint - 44 lines

API: Delete a property definition. Refuses if any objects have a value set for this property — analyst must clear values first.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/delete_class_property.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson); Permission: Cap::CMDB_CLASSES (requireCapabilityJson) |
| Called from | [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [cmdb_object_properties](/code/tables/cmdb_object_properties.md) |  |
| 35 |  | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 36 |  | **delete** [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

