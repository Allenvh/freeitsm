# workflow_executions

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `workflow_id` | `INT NULL` |  |
| `workflow_name` | `VARCHAR(255) NULL` |  |
| `trigger_event` | `VARCHAR(100) NOT NULL` |  |
| `trigger_payload` | `TEXT NULL` |  |
| `status` | `VARCHAR(20) NOT NULL` |  |
| `is_dry_run` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `started_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `finished_datetime` | `DATETIME NULL` |  |
| `step_log` | `TEXT NULL` |  |
| `error_message` | `TEXT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3167 |  | update | `workflow_name` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3169 |  | update | `workflow_id` |  |
| [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md):321 | `apiWorkflowsDelete` | update | `workflow_name`, `workflow_id` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):1611 | `WorkflowEngine::recordAbortedRun` | insert | `workflow_id`, `workflow_name`, `trigger_event`, `trigger_payload`, `status`, `is_dry_run`, `started_datetime`, `finished_datetime`, `step_log`, `error_message` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):1685 | `WorkflowEngine::runInner` | insert | `workflow_id`, `workflow_name`, `trigger_event`, `trigger_payload`, `status`, `is_dry_run`, `started_datetime` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):1719 | `WorkflowEngine::runInner` | update | `status`, `finished_datetime`, `step_log`, `error_message` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):2037 | `WorkflowEngine::runActionList` | insert | `workflow_id`, `workflow_name`, `trigger_event`, `trigger_payload`, `status`, `is_dry_run`, `started_datetime` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):2048 | `WorkflowEngine::runActionList` | update | `status`, `finished_datetime`, `step_log`, `error_message` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md) | apiWorkflowExecutionSelect L106, apiWorkflowExecutionsRespondList L378 |  |
| [api/workflow/execution_detail.php](/code/files/api/workflow/execution_detail.php.md) | L31 |  |
| [api/workflow/executions.php](/code/files/api/workflow/executions.php.md) | L71, L77, L106, L115, L122 |  |
| [api/workflow/get.php](/code/files/api/workflow/get.php.md) | L56 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L921, getWatchtowerData L926, getWatchtowerData L934 |  |

