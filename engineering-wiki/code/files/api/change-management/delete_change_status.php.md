# api/change-management/delete_change_status.php

> API endpoint - 54 lines

API Endpoint: Delete change status Refuses if the status is in use by any change/template, or if it's the default.

## The endpoint

| | |
|---|---|
| URL | `api/change-management/delete_change_status.php` |
| Protected by | Module access: changes (requireModuleAccessJson); Permission: Cap::CHANGES_STATUSES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | read [change_statuses](/code/tables/change_statuses.md) |  |
| 35 |  | read [changes](/code/tables/changes.md) |  |
| 43 |  | read [change_statuses](/code/tables/change_statuses.md) |  |
| 44 |  | **delete** [change_statuses](/code/tables/change_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

