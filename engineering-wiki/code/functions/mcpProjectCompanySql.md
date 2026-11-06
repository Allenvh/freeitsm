# mcpProjectCompanySql()

The company part of the MCP project filter - limits projects to the API key's companies.

Returns ['', []] when multi-tenancy is off or the key covers every company; a key with no
companies sees nothing. Projects with no company count as the Default company's.

```php
function mcpProjectCompanySql(PDO $conn, array $apiKey): array
```

Defined in [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md), line 251.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | the authenticated api_keys row; a null company_scope means every company |

**Returns:** `array`

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpProjectScopeSql()](/code/functions/mcpProjectScopeSql.md)

