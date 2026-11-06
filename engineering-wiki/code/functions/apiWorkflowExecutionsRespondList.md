# apiWorkflowExecutionsRespondList()

Shared list plumbing for both execution list routes.

```php
function apiWorkflowExecutionsRespondList(PDO $conn, array $where, array $args): void
```

Defined in [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), line 361.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$where` | `array` |  | SQL conditions on workflow_executions (alias we) from the route, e.g. ['1=1'] or a workflow_id filter; the query-string filters are added to it |
| `$args` | `array` |  | the bound values for the ? placeholders in `$where`, in order |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 378 | `apiWorkflowExecutionsRespondList` | read [workflow_executions](/code/tables/workflow_executions.md) |  |

## Calls

[apiPagination()](/code/functions/apiPagination.md), [apiParseDate()](/code/functions/apiParseDate.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeWorkflowExecution()](/code/functions/apiSerializeWorkflowExecution.md), [apiWorkflowExecutionSelect()](/code/functions/apiWorkflowExecutionSelect.md)

## Called from

- [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md) - in [apiWorkflowExecutionsList()](/code/functions/apiWorkflowExecutionsList.md), [apiWorkflowExecutionsListAll()](/code/functions/apiWorkflowExecutionsListAll.md)

