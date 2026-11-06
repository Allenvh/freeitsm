# apiWorkflowExecutionSelect()

The SELECT for workflow executions, keeping the workflow's name from the snapshot when the workflow has since been deleted.

```php
function apiWorkflowExecutionSelect(): string
```

Defined in [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), line 103.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 106 | `apiWorkflowExecutionSelect` | read [workflow_executions](/code/tables/workflow_executions.md), read [workflows](/code/tables/workflows.md) |  |

## Called from

- [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md) - in [apiWorkflowExecutionsRespondList()](/code/functions/apiWorkflowExecutionsRespondList.md), [apiWorkflowExecutionsGet()](/code/functions/apiWorkflowExecutionsGet.md)

