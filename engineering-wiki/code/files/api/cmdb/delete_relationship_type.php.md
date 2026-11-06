# api/cmdb/delete_relationship_type.php

> API endpoint - 44 lines

API: Delete a CMDB relationship type. Refuses if any relationships currently use this type.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/delete_relationship_type.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson); Permission: Cap::CMDB_RELATIONSHIP_TYPES (requireCapabilityJson) |
| Called from | [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) |  |
| 35 |  | read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |
| 36 |  | **delete** [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

