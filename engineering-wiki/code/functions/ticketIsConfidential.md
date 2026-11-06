# ticketIsConfidential()

Is this ticket confidential? False before Database Verification (no column).

```php
function ticketIsConfidential(PDO $conn, int $ticketId): bool
```

Defined in [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), line 169.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 173 | `ticketIsConfidential` | read [tickets](/code/tables/tickets.md) |  |

## Calls

[ticketSensitivityNormalise()](/code/functions/ticketSensitivityNormalise.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)

## Called from

- [api/integrations/escalate_ticket.php](/code/files/api/integrations/escalate_ticket.php.md)
- [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md) - in [ticketAiAllowed()](/code/functions/ticketAiAllowed.md), [ticketRedactForOutbound()](/code/functions/ticketRedactForOutbound.md), [ticketEmailRecipientsAllowed()](/code/functions/ticketEmailRecipientsAllowed.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_escalate_to_tracker`, `WorkflowEngine::action_send_note_to_tracker`

