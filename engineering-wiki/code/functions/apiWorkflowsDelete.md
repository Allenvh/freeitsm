# apiWorkflowsDelete()

DELETE /workflows/{id} — executions survive (the module's audit-trail intent) but are detached explicitly: name snapshot filled, workflow_id NULLed. Explicit rather than FK-reliant so grown installs that haven't re-run Database Verification behave identically.

```php
function apiWorkflowsDelete(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), line 316.

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
| 321 | `apiWorkflowsDelete` | **update** [workflow_executions](/code/tables/workflow_executions.md) (`workflow_name`, `workflow_id`) |  |
| 325 | `apiWorkflowsDelete` | **delete** [workflows](/code/tables/workflows.md) |  |

## Calls

[apiLoadWorkflow()](/code/functions/apiLoadWorkflow.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*

