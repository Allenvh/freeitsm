# apiRequireTimeTracking()

Refuse if this ticket's company has switched time tracking off FOR THE API (discussion #72).

⚠️ Separate from the UI switch on purpose. Hiding the panel is about interface
clutter; silently emptying an endpoint breaks integrations belonging to people
who changed nothing — so an install can tidy its screens without breaking a
billing export, or switch both off, and the two are its own decision.

A refusal rather than an empty list: an empty list says "this ticket has no
time", which is a different and untrue statement.

```php
function apiRequireTimeTracking(PDO $conn, int $ticketId): void
```

Defined in [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md), line 447.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiError()](/code/functions/apiError.md), [ticketTenantId()](/code/functions/ticketTenantId.md), [timeTrackingApiOn()](/code/functions/timeTrackingApiOn.md)

## Called from

- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiTicketTimeEntriesList()](/code/functions/apiTicketTimeEntriesList.md), [apiTicketTimeEntriesCreate()](/code/functions/apiTicketTimeEntriesCreate.md), [apiTicketTimeEntriesDelete()](/code/functions/apiTicketTimeEntriesDelete.md)

