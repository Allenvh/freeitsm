# mcpToolsFor()

tools/list: what this key may use, in MCP's shape.

```php
function mcpToolsFor(PDO $conn, array $apiKey): array
```

Defined in [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md), line 184.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | the authenticated api_keys row, company_scope already narrowed |

**Returns:** `array`

## Calls

[mcpToolAllowed()](/code/functions/mcpToolAllowed.md), [mcpTools()](/code/functions/mcpTools.md)

## Called from

- [api/mcp/index.php](/code/files/api/mcp/index.php.md)

