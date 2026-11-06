# mcpToolAllowed()

May this key run this tool? (permission is checked once, by the endpoint)

```php
function mcpToolAllowed(PDO $conn, array $apiKey, array $t): bool
```

Defined in [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md), line 170.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | the authenticated api_keys row, company_scope already narrowed by `mcpEffectiveScope()` |
| `$t` | `array` |  | the tool's entry from `mcpTools()` (module, capability, company_safe) |

**Returns:** `bool`

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystHasCapability()](/code/functions/analystHasCapability.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpToolsFor()](/code/functions/mcpToolsFor.md), [mcpRunTool()](/code/functions/mcpRunTool.md)

