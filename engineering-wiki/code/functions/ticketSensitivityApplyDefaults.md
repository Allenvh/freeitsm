# ticketSensitivityApplyDefaults()

Apply the automatic defaults to a ticket: its service-desk department's, and the mailbox it arrived through, if there was one. Call after every insert, and after every change of department.

```php
function ticketSensitivityApplyDefaults(PDO $conn, int $ticketId, ?int $mailboxId = null): bool
```

Defined in [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), line 112.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$mailboxId` | `?int` | `null` | the target_mailboxes id the ticket arrived through; null when it did not come by email |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 118 | `ticketSensitivityApplyDefaults` | read [tickets](/code/tables/tickets.md), read [departments](/code/tables/departments.md) |  |
| 129 | `ticketSensitivityApplyDefaults` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |

## Calls

[ticketSensitivityRaise()](/code/functions/ticketSensitivityRaise.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)

## Called from

- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/system/assign_ticket_department.php](/code/files/api/system/assign_ticket_department.php.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md)
- [api/tickets/save_department.php](/code/files/api/tickets/save_department.php.md)
- [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) - in [catalogueCreateTicketFromSubmission()](/code/functions/catalogueCreateTicketFromSubmission.md)
- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md)
- [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) - in `TicketsService::createTicket`, `TicketsService::updateTicket`
- [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) - in [mergeTickets()](/code/functions/mergeTickets.md)
- [includes/ticket_split.php](/code/files/includes/ticket_split.php.md) - in [splitTicket()](/code/functions/splitTicket.md)
- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatOpenTicket()](/code/functions/webchatOpenTicket.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_create_ticket`

