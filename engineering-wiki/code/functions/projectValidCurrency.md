# projectValidCurrency()

ISO 4217 code check (three letters).

```php
function projectValidCurrency(string $c): bool
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 554.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `string` |  | the currency code to check, e.g. 'GBP' (must already be upper case) |

**Returns:** `bool`

## Called from

- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::setCurrency`

