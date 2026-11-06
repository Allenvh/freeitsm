# api/process-mapper/save_step_type.php

> API endpoint - 67 lines

API Endpoint: Save a process step type (create or update).

On create, a unique slug is generated from the name; the slug never changes
afterwards because existing process_steps.type strings reference it.

## The endpoint

| | |
|---|---|
| URL | `api/process-mapper/save_step_type.php` |
| Protected by | Module access: process-mapper (requireModuleAccessJson); Permission: Cap::PROCESS_MAPPER_STEP_TYPES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | **update** [process_step_types](/code/tables/process_step_types.md) (`name`, `shape`, `color`, `is_active`) |  |
| 50 |  | read [process_step_types](/code/tables/process_step_types.md) |  |
| 57 |  | read [process_step_types](/code/tables/process_step_types.md) |  |
| 58 |  | **insert** [process_step_types](/code/tables/process_step_types.md) (`name`, `slug`, `shape`, `color`, `display_order`, `is_active`, `is_builtin`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

