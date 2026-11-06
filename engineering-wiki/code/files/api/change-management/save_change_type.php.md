# api/change-management/save_change_type.php

> API endpoint - 72 lines

API Endpoint: Save change type (create or update)

## The endpoint

| | |
|---|---|
| URL | `api/change-management/save_change_type.php` |
| Protected by | Module access: changes (requireModuleAccessJson); Permission: Cap::CHANGES_TYPES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 |  | **update** [change_types](/code/tables/change_types.md) (`is_default`) |  |
| 46 |  | **update** [change_types](/code/tables/change_types.md) (`name`, `colour`, `is_default`, `display_order`, `is_active`) |  |
| 52 |  | **insert** [change_types](/code/tables/change_types.md) (`name`, `colour`, `is_default`, `display_order`, `is_active`) |  |
| 58 |  | read [change_types](/code/tables/change_types.md) |  |
| 60 |  | **update** [change_types](/code/tables/change_types.md) (`is_default`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

