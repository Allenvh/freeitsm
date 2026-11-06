# api/workflow/get.php

> API endpoint - 74 lines

API: Get a single workflow with its conditions + actions decoded, plus the latest execution rows so the editor can show a recent-runs sidebar.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/get.php` |
| Protected by | Module access: workflow (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 |  | read [workflows](/code/tables/workflows.md) |  |
| 56 |  | read [workflow_executions](/code/tables/workflow_executions.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [webhookDecrypt()](/code/functions/webhookDecrypt.md)

