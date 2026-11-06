# api/change-management/delete_change_priority.php

> API endpoint - 53 lines

API Endpoint: Delete change priority

## The endpoint

| | |
|---|---|
| URL | `api/change-management/delete_change_priority.php` |
| Protected by | Module access: changes (requireModuleAccessJson); Permission: Cap::CHANGES_PRIORITIES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 29 |  | read [change_priorities](/code/tables/change_priorities.md) |  |
| 34 |  | read [changes](/code/tables/changes.md) |  |
| 42 |  | read [change_priorities](/code/tables/change_priorities.md) |  |
| 43 |  | **delete** [change_priorities](/code/tables/change_priorities.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

