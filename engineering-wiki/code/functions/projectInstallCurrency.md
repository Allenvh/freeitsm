# projectInstallCurrency()

The install's currency (Projects -> Settings -> Budget).

```php
function projectInstallCurrency(PDO $conn): string
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 68.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[projectSetting()](/code/functions/projectSetting.md)

## Called from

- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectCurrencyOf()](/code/functions/projectCurrencyOf.md), [projectStampCurrency()](/code/functions/projectStampCurrency.md), [projectLabour()](/code/functions/projectLabour.md), [projectLabourToCome()](/code/functions/projectLabourToCome.md), [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)

