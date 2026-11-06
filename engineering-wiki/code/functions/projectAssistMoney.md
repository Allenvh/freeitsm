# projectAssistMoney()

Formats an amount with its currency to two decimals; null shows as "-".

```php
function projectAssistMoney(?float $v, string $cur): string
```

Defined in [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md), line 88.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` | `?float` |  | the amount; null shows as "-" |
| `$cur` | `string` |  | the currency code shown before it, e.g. 'GBP' |

**Returns:** `string`

## Called from

- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpToolProjectBudget()](/code/functions/mcpToolProjectBudget.md)
- [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) - in [projectAssistOverview()](/code/functions/projectAssistOverview.md)

