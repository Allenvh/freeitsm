# api/cmdb/delete_class.php

> API endpoint - 49 lines

API: Delete a CMDB class. Refuses if any objects exist for the class — analyst must reassign or delete them first.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/delete_class.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson); Permission: Cap::CMDB_CLASSES (requireCapabilityJson) |
| Called from | [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 |  | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 39 |  | read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 41 |  | **delete** [cmdb_classes](/code/tables/cmdb_classes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

