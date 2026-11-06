# apiCostCentreSelect()

The SELECT for cost centres, with the parent's code and name, the company name and the number of child cost centres.

```php
function apiCostCentreSelect(): string
```

Defined in [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md), line 32.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 | `apiCostCentreSelect` | read [cost_centres](/code/tables/cost_centres.md), read [tenants](/code/tables/tenants.md) |  |

## Called from

- [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md) - in [apiLoadCostCentre()](/code/functions/apiLoadCostCentre.md), [apiCostCentresList()](/code/functions/apiCostCentresList.md)

