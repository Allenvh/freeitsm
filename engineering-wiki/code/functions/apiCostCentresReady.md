# apiCostCentresReady()

Answers 503 and stops when the cost centre tables are not there yet (Database Verification has not been run).

```php
function apiCostCentresReady(PDO $conn): void
```

Defined in [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md), line 93.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md) - in [apiCostCentresList()](/code/functions/apiCostCentresList.md), [apiCostCentresGet()](/code/functions/apiCostCentresGet.md), [apiCostCentresCreate()](/code/functions/apiCostCentresCreate.md), [apiCostCentresUpdate()](/code/functions/apiCostCentresUpdate.md), [apiCostCentresDelete()](/code/functions/apiCostCentresDelete.md), [apiCostCentresSync()](/code/functions/apiCostCentresSync.md)

