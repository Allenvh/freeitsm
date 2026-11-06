# projectCurrencyOf()

A project's currency: its own, or - not yet stamped - the install's.

```php
function projectCurrencyOf(PDO $conn, array $p): string
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 78.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$p` | `array` |  | the projects row - its currency column is read |

**Returns:** `string`

## Calls

[projectInstallCurrency()](/code/functions/projectInstallCurrency.md)

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiSerializeProject()](/code/functions/apiSerializeProject.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)
- [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) - in [projectAssistOverview()](/code/functions/projectAssistOverview.md)
- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectLabour()](/code/functions/projectLabour.md), [projectBudgetTotals()](/code/functions/projectBudgetTotals.md), [projectLabourToCome()](/code/functions/projectLabourToCome.md), [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::setCurrency`

