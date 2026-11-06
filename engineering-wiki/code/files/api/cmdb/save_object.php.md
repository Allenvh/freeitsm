# api/cmdb/save_object.php

> API endpoint - 29 lines

API: Create or update a CMDB object. Thin UI adapter over CmdbService. The UI sends property_values as an id-addressed list ([{property_id, value}]) — the service normalises that.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/save_object.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson) |
| Called from | [cmdb/browse.js](/code/files/cmdb/browse.js.md), [cmdb/object.js](/code/files/cmdb/object.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

