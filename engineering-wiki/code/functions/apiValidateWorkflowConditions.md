# apiValidateWorkflowConditions()

Validate a conditions array. Returns it normalised (list of objects).

```php
function apiValidateWorkflowConditions($conditions): array
```

Defined in [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), line 147.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conditions` |  |  | the caller's conditions (untrusted) - a list of {field, op, value} |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md) - in [apiWorkflowsCreate()](/code/functions/apiWorkflowsCreate.md), [apiWorkflowsUpdate()](/code/functions/apiWorkflowsUpdate.md)

