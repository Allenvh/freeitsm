# apiCmdbObjectTicketsList()

Ticket links — company-scoped on both the CI and the tickets

```php
function apiCmdbObjectTicketsList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md), line 510.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 518 | `apiCmdbObjectTicketsList` | read [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## Calls

[apiCmdbLoadObject()](/code/functions/apiCmdbLoadObject.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*

