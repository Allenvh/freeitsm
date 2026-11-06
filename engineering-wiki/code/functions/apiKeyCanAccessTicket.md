# apiKeyCanAccessTicket()

May this key access this ticket (by its owning company)? Unknown id => false.

```php
function apiKeyCanAccessTicket(PDO $conn, array $apiKey, int $ticketId): bool
```

Defined in [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md), line 263.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md)

## Called from

- [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) - in [apiCmdbObjectTicketsLink()](/code/functions/apiCmdbObjectTicketsLink.md), [apiCmdbObjectTicketsUnlink()](/code/functions/apiCmdbObjectTicketsUnlink.md)
- [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) - in [apiTasksGet()](/code/functions/apiTasksGet.md)
- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiLoadTicket()](/code/functions/apiLoadTicket.md)

