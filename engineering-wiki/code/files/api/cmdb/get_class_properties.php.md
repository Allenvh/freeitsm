# api/cmdb/get_class_properties.php

> API endpoint - 71 lines

API: List property definitions for a single class, including dropdown options.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/get_class_properties.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson) |
| Reads | `GET class_id` |
| Called from | [cmdb/browse.js](/code/files/cmdb/browse.js.md), [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 42 |  | read [cmdb_class_property_options](/code/tables/cmdb_class_property_options.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

