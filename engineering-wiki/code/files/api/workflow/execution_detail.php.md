# api/workflow/execution_detail.php

> API endpoint - 82 lines

API: one execution, in full — the step-by-step log plus the trigger payload snapshot. This is the "why did it do that?" view.

The payload snapshot is the genuinely valuable bit: it's exactly what the
conditions were evaluated against and what the {{variables}} resolved from,
captured at run time. Without it you're guessing at what the event looked
like when it fired.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/execution_detail.php` |
| Protected by | Module access: workflow (requireModuleAccessJson) |
| Reads | `GET id` |
| Called from | [assets/js/workflow-executions.js](/code/files/assets/js/workflow-executions.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [workflow_executions](/code/tables/workflow_executions.md), read [workflows](/code/tables/workflows.md) |  |
| 48 |  | read [webhook_deliveries](/code/tables/webhook_deliveries.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

