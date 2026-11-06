# api/workflow/delete.php

> API endpoint - 34 lines

API: Delete a workflow. Execution rows for the deleted workflow stay in workflow_executions (no FK cascade configured) so the audit trail survives — they just become orphaned on workflow_id. The list query filters them by joining only with workflows that still exist.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/delete.php` |
| Protected by | Module access: workflow (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | **delete** [workflows](/code/tables/workflows.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

