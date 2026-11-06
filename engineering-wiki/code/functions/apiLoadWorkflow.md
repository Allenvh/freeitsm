# apiLoadWorkflow()

Loads one workflow row, or answers 404 and stops.

```php
function apiLoadWorkflow(PDO $conn, int $workflowId): array
```

Defined in [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), line 89.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$workflowId` | `int` |  | the workflows id |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md), [apiWorkflowSelect()](/code/functions/apiWorkflowSelect.md)

## Called from

- [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md) - in [apiWorkflowsGet()](/code/functions/apiWorkflowsGet.md), [apiWorkflowsCreate()](/code/functions/apiWorkflowsCreate.md), [apiWorkflowsUpdate()](/code/functions/apiWorkflowsUpdate.md), [apiWorkflowsDelete()](/code/functions/apiWorkflowsDelete.md), [apiWorkflowsFire()](/code/functions/apiWorkflowsFire.md), [apiWorkflowExecutionsList()](/code/functions/apiWorkflowExecutionsList.md)

