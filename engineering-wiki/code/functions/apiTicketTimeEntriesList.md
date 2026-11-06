# apiTicketTimeEntriesList()

GET /tickets/{id}/time-entries - the ticket's active time entries, oldest first. Refused when time tracking is off for the ticket.

```php
function apiTicketTimeEntriesList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md), line 458.

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
| 462 | `apiTicketTimeEntriesList` | read [ticket_time_entries](/code/tables/ticket_time_entries.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md), [apiLoadTicket()](/code/functions/apiLoadTicket.md), [apiRequireTimeTracking()](/code/functions/apiRequireTimeTracking.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*

