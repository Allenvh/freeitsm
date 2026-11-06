# integrationsTicketPayload()

The ticket block every tracker.* event carries, in the same shape the tickets service dispatches — so `{{ticket.requester_email}}` means the same thing whichever trigger a workflow hangs off.

```php
function integrationsTicketPayload(PDO $conn, int $ticketId): array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 854.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 869 | `integrationsTicketPayload` | read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |

## Called from

- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsEmitTrackerEvent()](/code/functions/integrationsEmitTrackerEvent.md)

