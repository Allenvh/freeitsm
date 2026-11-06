# apiValidateWorkflowActions()

Validate an actions array against the engine's action catalogue.

```php
function apiValidateWorkflowActions($actions): array
```

Defined in [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), line 175.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$actions` |  |  | the caller's actions (untrusted) - a list of {type, args} |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md) - in [apiWorkflowsCreate()](/code/functions/apiWorkflowsCreate.md), [apiWorkflowsUpdate()](/code/functions/apiWorkflowsUpdate.md)

