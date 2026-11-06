# api/process-mapper/delete_step_type.php

> API endpoint - 42 lines

API Endpoint: Delete a process step type.

Built-in types cannot be deleted. Steps that still reference a deleted
type by its slug fall back to a plain rectangle in the editor — the
coupling is intentionally loose (process_steps.type is a free-text string,
not a foreign key), so a delete can never corrupt a saved diagram.

## The endpoint

| | |
|---|---|
| URL | `api/process-mapper/delete_step_type.php` |
| Protected by | Module access: process-mapper (requireModuleAccessJson); Permission: Cap::PROCESS_MAPPER_STEP_TYPES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | read [process_step_types](/code/tables/process_step_types.md) |  |
| 36 |  | **delete** [process_step_types](/code/tables/process_step_types.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

