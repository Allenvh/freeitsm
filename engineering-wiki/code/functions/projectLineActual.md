# projectLineActual()

One line's actual and where it came from: [amount|null, 'typed'|'contract'|null, mismatch?]. A typed actual wins; otherwise a linked contract's value - only in the same currency.

```php
function projectLineActual(array $r, string $currency): array
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 228.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | a project_budget_lines row, with the linked contract's contract_value and contract_currency |
| `$currency` | `string` |  | the project's currency code, e.g. 'GBP' |

**Returns:** `array`

## Called from

- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectBudgetTotals()](/code/functions/projectBudgetTotals.md), [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)

