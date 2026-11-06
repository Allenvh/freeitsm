# api/cmdb/get_classes.php

> API endpoint - 59 lines

API: List CMDB classes (with property count per class)

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/get_classes.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson) |
| Called from | [assets/js/network-mapper.js](/code/files/assets/js/network-mapper.js.md), [cmdb/browse.js](/code/files/cmdb/browse.js.md), [cmdb/object.js](/code/files/cmdb/object.js.md), [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 |  | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md), read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 38 |  | read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_icons](/code/tables/cmdb_icons.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

