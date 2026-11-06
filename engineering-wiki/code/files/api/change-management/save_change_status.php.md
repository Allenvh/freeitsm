# api/change-management/save_change_status.php

> API endpoint - 73 lines

API Endpoint: Save change status (create or update)

## The endpoint

| | |
|---|---|
| URL | `api/change-management/save_change_status.php` |
| Protected by | Module access: changes (requireModuleAccessJson); Permission: Cap::CHANGES_STATUSES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | **update** [change_statuses](/code/tables/change_statuses.md) (`is_default`) |  |
| 47 |  | **update** [change_statuses](/code/tables/change_statuses.md) (`name`, `colour`, `is_closed`, `is_default`, `display_order`, `is_active`) |  |
| 53 |  | **insert** [change_statuses](/code/tables/change_statuses.md) (`name`, `colour`, `is_closed`, `is_default`, `display_order`, `is_active`) |  |
| 59 |  | read [change_statuses](/code/tables/change_statuses.md) |  |
| 61 |  | **update** [change_statuses](/code/tables/change_statuses.md) (`is_default`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

