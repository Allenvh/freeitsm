# apiWorkflowsCreate()

POST /workflows

```php
function apiWorkflowsCreate(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), line 250.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 260 | `apiWorkflowsCreate` | **insert** [workflows](/code/tables/workflows.md) (`name`, `description`, `trigger_event`, `conditions`, `actions`, `is_active`, `created_by`, `created_datetime`, `updated_datetime`) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiLoadWorkflow()](/code/functions/apiLoadWorkflow.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeWorkflow()](/code/functions/apiSerializeWorkflow.md), [apiValidateWorkflowActions()](/code/functions/apiValidateWorkflowActions.md), [apiValidateWorkflowConditions()](/code/functions/apiValidateWorkflowConditions.md), [apiValidateWorkflowTrigger()](/code/functions/apiValidateWorkflowTrigger.md)

*Nothing calls this function by name.*

