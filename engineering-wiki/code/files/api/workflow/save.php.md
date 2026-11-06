# api/workflow/save.php

> API endpoint - 120 lines

API: Save (create or update) a workflow.

Body shape:
  { id?, name, description, trigger_event, conditions: [...], actions: [...], is_active }

conditions and actions are stored JSON-encoded — see workflow/includes/engine.php
for the contract each entry must obey.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/save.php` |
| Protected by | Module access: workflow (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 99 |  | **update** [workflows](/code/tables/workflows.md) (`name`, `description`, `trigger_event`, `conditions`, `actions`, `is_active`) |  |
| 106 |  | **insert** [workflows](/code/tables/workflows.md) (`name`, `description`, `trigger_event`, `conditions`, `actions`, `is_active`, `created_by`, `created_datetime`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [webhookEncrypt()](/code/functions/webhookEncrypt.md)

