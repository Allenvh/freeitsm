# api/change-management/save_change_impact.php

> API endpoint - 72 lines

API Endpoint: Save change impact (create or update)

## The endpoint

| | |
|---|---|
| URL | `api/change-management/save_change_impact.php` |
| Protected by | Module access: changes (requireModuleAccessJson); Permission: Cap::CHANGES_IMPACTS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 |  | **update** [change_impacts](/code/tables/change_impacts.md) (`is_default`) |  |
| 46 |  | **update** [change_impacts](/code/tables/change_impacts.md) (`name`, `colour`, `is_default`, `display_order`, `is_active`) |  |
| 52 |  | **insert** [change_impacts](/code/tables/change_impacts.md) (`name`, `colour`, `is_default`, `display_order`, `is_active`) |  |
| 58 |  | read [change_impacts](/code/tables/change_impacts.md) |  |
| 60 |  | **update** [change_impacts](/code/tables/change_impacts.md) (`is_default`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

