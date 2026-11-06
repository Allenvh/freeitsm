# api/cmdb/get_relationship_types.php

> API endpoint - 38 lines

API: List CMDB relationship types.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/get_relationship_types.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson) |
| Called from | [cmdb/object.js](/code/files/cmdb/object.js.md), [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 24 |  | read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

