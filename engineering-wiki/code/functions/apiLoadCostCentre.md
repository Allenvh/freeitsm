# apiLoadCostCentre()

The by-id gate: existence + company scope, both failing as 404.

```php
function apiLoadCostCentre(PDO $conn, array $apiKey, int $id): array
```

Defined in [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md), line 66.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the cost_centres id |

**Returns:** `array`

## Calls

[apiCostCentreSelect()](/code/functions/apiCostCentreSelect.md), [apiError()](/code/functions/apiError.md), [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md)

## Called from

- [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md) - in [apiCostCentresGet()](/code/functions/apiCostCentresGet.md), [apiCostCentresCreate()](/code/functions/apiCostCentresCreate.md), [apiCostCentresUpdate()](/code/functions/apiCostCentresUpdate.md), [apiCostCentresDelete()](/code/functions/apiCostCentresDelete.md)

