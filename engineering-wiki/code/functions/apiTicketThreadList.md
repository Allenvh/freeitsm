# apiTicketThreadList()

Thread (emails / channel messages)

```php
function apiTicketThreadList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md), line 371.

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
| 374 | `apiTicketThreadList` | read [emails](/code/tables/emails.md) |  |

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md), [apiLoadTicket()](/code/functions/apiLoadTicket.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*

