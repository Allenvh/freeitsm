# api/cmdb/delete_object_relationship.php

> API endpoint - 28 lines

API: Delete a single object relationship by its row id. Thin UI adapter over CmdbService (unscoped delete, by relationship id).

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/delete_object_relationship.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson) |
| Called from | [cmdb/object.js](/code/files/cmdb/object.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

