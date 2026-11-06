# apiSerializeBudgetLine()

Budget

```php
function apiSerializeBudgetLine(array $l): array
```

Defined in [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), line 551.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$l` | `array` |  | one entry of `projectBudgetDetail()`'s 'lines' list, already worked out (actual source, contract, forecast) |

**Returns:** `array`

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectBudgetLinesCreate()](/code/functions/apiProjectBudgetLinesCreate.md), [apiProjectBudgetLinesUpdate()](/code/functions/apiProjectBudgetLinesUpdate.md)

