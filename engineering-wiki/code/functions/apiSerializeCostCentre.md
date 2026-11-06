# apiSerializeCostCentre()

Shapes a cost centre row for the API - code, name, description, parent, active flag, company, child count and dates.

```php
function apiSerializeCostCentre(array $r): array
```

Defined in [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md), line 46.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | the cost_centres row as `apiCostCentreSelect()` loads it, with parent code/name, company name and child_count |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md) - in [apiCostCentresGet()](/code/functions/apiCostCentresGet.md), [apiCostCentresCreate()](/code/functions/apiCostCentresCreate.md), [apiCostCentresUpdate()](/code/functions/apiCostCentresUpdate.md)

