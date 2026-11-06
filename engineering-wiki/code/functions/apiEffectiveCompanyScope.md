# apiEffectiveCompanyScope()

The companies a key may really reach: its own list, narrowed to its analyst's.

single-company install                  -> null (nothing to narrow)
  analyst who may see every company       -> the key's own list (null = all)
  analyst limited to some companies       -> key list ∩ theirs (no list = theirs)

An empty result means the key reaches no company: reads find nothing, and
creating a record is refused (apiKeyDefaultTenantId).

```php
function apiEffectiveCompanyScope(PDO $conn, int $analystId, ?array $keyScope): ?array
```

Defined in [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md), line 186.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | the analyst the API key acts as - not whoever is signed in |
| `$keyScope` | `?array` |  | the company ids the key itself is limited to; null means no limit of its own |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 191 | `apiEffectiveCompanyScope` | read [tenants](/code/tables/tenants.md) |  |

## Calls

[getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) - in [apiAuthenticate()](/code/functions/apiAuthenticate.md)
- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpEffectiveScope()](/code/functions/mcpEffectiveScope.md)

