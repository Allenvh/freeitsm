# api/workflow/executions.php

> API endpoint - 142 lines

API: the cross-workflow execution log.

Until now the only view of a run was the editor's "Recent runs" panel — the
last 20 runs of ONE workflow, no filtering, no search, no history. Which is
exactly the wrong shape for the question you actually ask ("what failed, and
why?"), because you don't know which workflow to open yet.

GET params (all optional):
  workflow_id  int      one workflow
  status       string   running|success|failed|skipped|aborted
  trigger      string   exact trigger event
  dry_run      0|1      exclude / show only dry runs
  from, to     Y-m-d    date range (inclusive), on started_datetime
  q            string   free text over workflow name + error message
  page         int      1-based
  per_page     int      default 25, max 100

Returns the page of rows, a total count, and the filter facets (workflow list
+ the trigger events that actually appear in the log) so the UI's dropdowns
only ever offer values that will match something.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/executions.php` |
| Protected by | Module access: workflow (requireModuleAccessJson) |
| Reads | `GET workflow_id`, `GET status`, `GET trigger`, `GET dry_run`, `GET from`, `GET to`, `GET q`, `GET page`, `GET per_page` |
| Called from | [assets/js/workflow-executions.js](/code/files/assets/js/workflow-executions.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 71 |  | read [workflow_executions](/code/tables/workflow_executions.md) |  |
| 77 |  | read [workflow_executions](/code/tables/workflow_executions.md), read [workflows](/code/tables/workflows.md) |  |
| 106 |  | read [workflow_executions](/code/tables/workflow_executions.md), read [workflows](/code/tables/workflows.md) |  |
| 115 |  | read [workflow_executions](/code/tables/workflow_executions.md) |  |
| 122 |  | read [workflow_executions](/code/tables/workflow_executions.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

