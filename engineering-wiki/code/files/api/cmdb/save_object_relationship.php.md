# api/cmdb/save_object_relationship.php

> API endpoint - 29 lines

API: Create a relationship between two CMDB objects. Thin UI adapter over CmdbService.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/save_object_relationship.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson) |
| Called from | [cmdb/object.js](/code/files/cmdb/object.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

