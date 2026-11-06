# apiKeyTicketFilter()

Back-compat name used by the tickets resource — delegates to the generic filter.

```php
function apiKeyTicketFilter(PDO $conn, array $apiKey, string $alias = 't'): array
```

Defined in [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md), line 306.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` | `'t'` | the alias the query gives the tickets table |

**Returns:** `array`

## Calls

[apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md)

## Called from

- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiTicketsList()](/code/functions/apiTicketsList.md)
- [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) - in [apiUsersGet()](/code/functions/apiUsersGet.md)

