# buildTicketMergeData()

Build merge data from ticket, analyst, and department tables.

```php
function buildTicketMergeData(PDO $conn, int $ticketId): ?array
```

Defined in [includes/template_email.php](/code/files/includes/template_email.php.md), line 349.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 350 | `buildTicketMergeData` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [users](/code/tables/users.md), read [analysts](/code/tables/analysts.md), read [departments](/code/tables/departments.md) |  |

## Calls

[fmt_local()](/code/functions/fmt_local.md), [publicAbsoluteUrl()](/code/functions/publicAbsoluteUrl.md)

## Called from

- [includes/reply_templates.php](/code/files/includes/reply_templates.php.md) - in [renderReplyTemplate()](/code/functions/renderReplyTemplate.md)
- [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md) - in `MandatoryFieldsService::notify`
- [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) - in [sla_send_breach_email()](/code/functions/sla_send_breach_email.md)
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [sendTemplateEmail()](/code/functions/sendTemplateEmail.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_send_email`

