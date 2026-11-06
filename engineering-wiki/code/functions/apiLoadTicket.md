# apiLoadTicket()

Load one ticket (with joins) enforcing the key's company scope; 404s if not visible.

```php
function apiLoadTicket(PDO $conn, array $apiKey, int $ticketId): array
```

Defined in [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md), line 109.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md), [apiKeyCanAccessTicket()](/code/functions/apiKeyCanAccessTicket.md), [apiTicketSelect()](/code/functions/apiTicketSelect.md)

## Called from

- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiTicketsGet()](/code/functions/apiTicketsGet.md), [apiTicketsCreate()](/code/functions/apiTicketsCreate.md), [apiTicketsUpdate()](/code/functions/apiTicketsUpdate.md), [apiTicketsRestore()](/code/functions/apiTicketsRestore.md), [apiTicketNotesList()](/code/functions/apiTicketNotesList.md), [apiTicketThreadList()](/code/functions/apiTicketThreadList.md), [apiTicketAuditList()](/code/functions/apiTicketAuditList.md), [apiTicketSlaGet()](/code/functions/apiTicketSlaGet.md), [apiTicketTimeEntriesList()](/code/functions/apiTicketTimeEntriesList.md)

