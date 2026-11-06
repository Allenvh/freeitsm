# ticketSensitivityReady()

Have the columns been added yet? New code reaches an install before System -> Database Verification runs, and every ticket path calls into here, so a missing column must mean "do nothing", never an error.

```php
function ticketSensitivityReady(PDO $conn): bool
```

Defined in [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), line 47.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 | `ticketSensitivityReady` | read [tickets](/code/tables/tickets.md) |  |
| 53 | `ticketSensitivityReady` | read [departments](/code/tables/departments.md) |  |
| 54 | `ticketSensitivityReady` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |

## Called from

- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md)
- [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md)
- [api/self-service/get_tickets.php](/code/files/api/self-service/get_tickets.php.md)
- [api/self-service/mark_confidential.php](/code/files/api/self-service/mark_confidential.php.md)
- [api/system/managers.php](/code/files/api/system/managers.php.md) - in [managersStatus()](/code/functions/managersStatus.md)
- [api/tickets/assign_ticket.php](/code/files/api/tickets/assign_ticket.php.md)
- [api/tickets/get_departments.php](/code/files/api/tickets/get_departments.php.md)
- [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md)
- [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md)
- [api/tickets/get_mailboxes.php](/code/files/api/tickets/get_mailboxes.php.md)
- [api/tickets/save_department.php](/code/files/api/tickets/save_department.php.md)
- [api/tickets/save_mailbox.php](/code/files/api/tickets/save_mailbox.php.md)
- [api/workflow/webhook_test.php](/code/files/api/workflow/webhook_test.php.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [managersActive()](/code/functions/managersActive.md), [portalTicketAccess()](/code/functions/portalTicketAccess.md)
- [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) - in `TicketsService::createTicket`, `TicketsService::updateTicket`
- [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md) - in [ticketSensitivityRaise()](/code/functions/ticketSensitivityRaise.md), [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md), [ticketSensitivityInherit()](/code/functions/ticketSensitivityInherit.md), [ticketIsConfidential()](/code/functions/ticketIsConfidential.md), [ticketAiExclusionSql()](/code/functions/ticketAiExclusionSql.md), [ticketAiSubjectSql()](/code/functions/ticketAiSubjectSql.md), [ticketSensitivitySelectSql()](/code/functions/ticketSensitivitySelectSql.md)

