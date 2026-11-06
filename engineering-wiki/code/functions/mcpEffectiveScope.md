# mcpEffectiveScope()

🔴 THE KEY'S EFFECTIVE COMPANIES (security review, 2026-10-09). A key's own company_scope is null when an administrator left it on "all companies" - and the REST API reads null as EVERY company, even when the analyst the key acts as is limited to one. The MCP server promises "you see what that analyst may see", so it intersects: the key's companies AND the analyst's.

single-company install                     -> null (nothing to narrow)
  analyst who may see every company          -> the key's own scope (null = all)
  analyst limited to some companies          -> key scope ∩ theirs (null key = theirs)

The endpoint stores the result back in `$apiKey`['company_scope'] straight after
authenticating, so every tool and helper below reads the narrowed value.

```php
function mcpEffectiveScope(PDO $conn, array $apiKey): ?array
```

Defined in [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md), line 156.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | the authenticated api_keys row (analyst_id, company_scope) |

**Returns:** `?array`

## Calls

[apiEffectiveCompanyScope()](/code/functions/apiEffectiveCompanyScope.md)

## Called from

- [api/mcp/index.php](/code/files/api/mcp/index.php.md)

