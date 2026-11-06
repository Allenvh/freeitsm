# api/change-management/delete_change_type.php

> API endpoint - 53 lines

API Endpoint: Delete change type

## The endpoint

| | |
|---|---|
| URL | `api/change-management/delete_change_type.php` |
| Protected by | Module access: changes (requireModuleAccessJson); Permission: Cap::CHANGES_TYPES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 29 |  | read [change_types](/code/tables/change_types.md) |  |
| 34 |  | read [changes](/code/tables/changes.md) |  |
| 42 |  | read [change_types](/code/tables/change_types.md) |  |
| 43 |  | **delete** [change_types](/code/tables/change_types.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

