# apiTenantConfigList()

Shared list for the tenant-overridable config tables (add/hide model).

```php
function apiTenantConfigList(PDO $conn, array $apiKey, string $table, string $entityType): void
```

Defined in [api/v1/resources/reference.php](/code/files/api/v1/resources/reference.php.md), line 100.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the config table to list (e.g. 'ticket_types') - a fixed name from the caller, never input |
| `$entityType` | `string` |  | its key in tenant_config_hidden (e.g. 'ticket_type'), for the per-company overrides |

**Returns:** `void`

## Calls

[apiError()](/code/functions/apiError.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiRespond()](/code/functions/apiRespond.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md)

## Called from

- [api/v1/resources/reference.php](/code/files/api/v1/resources/reference.php.md) - in [apiTicketTypesList()](/code/functions/apiTicketTypesList.md), [apiOriginsList()](/code/functions/apiOriginsList.md)

