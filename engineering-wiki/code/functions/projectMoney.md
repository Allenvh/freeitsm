# projectMoney()

A money amount from input: null for empty, else a number with at most two decimals.

```php
function projectMoney($v): ?float
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 541.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the amount as typed (untrusted); commas and spaces are ignored, null or '' gives null |

**Returns:** `?float`

## Called from

- [api/projects/settings.php](/code/files/api/projects/settings.php.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::saveChangeRequest`, `ProjectToolsService::saveBudgetLine`, `ProjectToolsService::addProjectRate`

