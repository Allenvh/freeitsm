# apiSerializeWorkflow()

Shapes a workflow row for the API - trigger, active flag, people, dates, last run and run count, plus the full conditions and actions when `$full` (only their counts otherwise).

```php
function apiSerializeWorkflow(array $r, bool $full): array
```

Defined in [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), line 54.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | the workflows row as `apiWorkflowSelect()` loads it (conditions and actions still JSON text) |
| `$full` | `bool` |  | true to include the conditions and actions in full; false gives only their counts |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md) - in [apiWorkflowsList()](/code/functions/apiWorkflowsList.md), [apiWorkflowsGet()](/code/functions/apiWorkflowsGet.md), [apiWorkflowsCreate()](/code/functions/apiWorkflowsCreate.md), [apiWorkflowsUpdate()](/code/functions/apiWorkflowsUpdate.md)

