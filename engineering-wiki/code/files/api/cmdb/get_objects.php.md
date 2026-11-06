# api/cmdb/get_objects.php

> API endpoint - 78 lines

API: List CMDB objects, optionally filtered by class and/or text search. Returns lightweight rows for the browse page table.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/get_objects.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson) |
| Reads | `GET class_id`, `GET search` |
| Called from | [assets/js/network-mapper.js](/code/files/assets/js/network-mapper.js.md), [cmdb/browse.js](/code/files/cmdb/browse.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 44 |  | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 47 |  | read [cmdb_classes](/code/tables/cmdb_classes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

