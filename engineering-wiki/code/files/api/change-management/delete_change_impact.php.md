# api/change-management/delete_change_impact.php

> API endpoint - 53 lines

API Endpoint: Delete change impact

## The endpoint

| | |
|---|---|
| URL | `api/change-management/delete_change_impact.php` |
| Protected by | Module access: changes (requireModuleAccessJson); Permission: Cap::CHANGES_IMPACTS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 29 |  | read [change_impacts](/code/tables/change_impacts.md) |  |
| 34 |  | read [changes](/code/tables/changes.md) |  |
| 42 |  | read [change_impacts](/code/tables/change_impacts.md) |  |
| 43 |  | **delete** [change_impacts](/code/tables/change_impacts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

