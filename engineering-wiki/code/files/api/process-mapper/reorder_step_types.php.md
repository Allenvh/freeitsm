# api/process-mapper/reorder_step_types.php

> API endpoint - 40 lines

API Endpoint: Reorder process step types. POST { order: [typeId, typeId, ...] } — rewrites display_order to match.

## The endpoint

| | |
|---|---|
| URL | `api/process-mapper/reorder_step_types.php` |
| Protected by | Module access: process-mapper (requireModuleAccessJson); Permission: Cap::PROCESS_MAPPER_STEP_TYPES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 |  | **update** [process_step_types](/code/tables/process_step_types.md) (`display_order`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

