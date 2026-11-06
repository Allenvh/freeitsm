# apiWorkflowSelect()

The SELECT for workflows, with the creator's name.

```php
function apiWorkflowSelect(): string
```

Defined in [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), line 40.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 | `apiWorkflowSelect` | read [workflows](/code/tables/workflows.md), read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md) - in [apiLoadWorkflow()](/code/functions/apiLoadWorkflow.md), [apiWorkflowsList()](/code/functions/apiWorkflowsList.md)

