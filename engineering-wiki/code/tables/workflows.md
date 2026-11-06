# workflows

Workflows module — automation engine with cross-module triggers. conditions and actions are JSON-in-TEXT so the rule shape can evolve without a schema migration each time the engine grows new operators or action kinds.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(255) NOT NULL` |  |
| `description` | `TEXT NULL` |  |
| `trigger_event` | `VARCHAR(100) NOT NULL` |  |
| `conditions` | `TEXT NULL` |  |
| `actions` | `TEXT NOT NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_by` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` |  |
| `last_run_datetime` | `DATETIME NULL` |  |
| `last_run_status` | `VARCHAR(20) NULL` |  |
| `run_count` | `INT NOT NULL DEFAULT 0` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3179 |  | update | `created_by` |  |
| [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md):260 | `apiWorkflowsCreate` | insert | `name`, `description`, `trigger_event`, `conditions`, `actions`, `is_active`, `created_by`, `created_datetime`, `updated_datetime` |  |
| [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md):303 | `apiWorkflowsUpdate` | update | `name`, `description`, `trigger_event`, `conditions`, `actions`, `is_active`, `updated_datetime` |  |
| [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md):325 | `apiWorkflowsDelete` | delete |  |  |
| [api/workflow/create_from_template.php](/code/files/api/workflow/create_from_template.php.md):44 |  | insert | `name`, `description`, `trigger_event`, `conditions`, `actions`, `is_active`, `created_by`, `created_datetime`, `updated_datetime` |  |
| [api/workflow/delete.php](/code/files/api/workflow/delete.php.md):28 |  | delete |  |  |
| [api/workflow/save.php](/code/files/api/workflow/save.php.md):99 |  | update | `name`, `description`, `trigger_event`, `conditions`, `actions`, `is_active` |  |
| [api/workflow/save.php](/code/files/api/workflow/save.php.md):106 |  | insert | `name`, `description`, `trigger_event`, `conditions`, `actions`, `is_active`, `created_by`, `created_datetime`, `updated_datetime` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):1623 | `WorkflowEngine::recordAbortedRun` | update | `last_run_datetime`, `last_run_status` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):1729 | `WorkflowEngine::runInner` | update | `last_run_datetime`, `last_run_status`, `run_count` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3167, L3169, L3187 |  |
| [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md) | apiWorkflowSelect L41, apiWorkflowExecutionSelect L106 |  |
| [api/workflow/delete_format.php](/code/files/api/workflow/delete_format.php.md) | L50 |  |
| [api/workflow/deliveries.php](/code/files/api/workflow/deliveries.php.md) | L41 |  |
| [api/workflow/execution_detail.php](/code/files/api/workflow/execution_detail.php.md) | L31 |  |
| [api/workflow/executions.php](/code/files/api/workflow/executions.php.md) | L77, L106 |  |
| [api/workflow/get.php](/code/files/api/workflow/get.php.md) | L26 |  |
| [api/workflow/list.php](/code/files/api/workflow/list.php.md) | L19 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L934 |  |
| [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md) | workflowEmitOnce L68 |  |
| [system/webhooks/index.php](/code/files/system/webhooks/index.php.md) | L143 |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) | WorkflowEngine::dispatch L1506, WorkflowEngine::manualFire L1534 |  |

