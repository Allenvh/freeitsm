# sendCsatSurvey()

Create a CSAT response row and send the survey email. Returns an array: ['result' => <CSAT_RESULT_*>, 'response_id' => int|null]

Pass `$force` = true to bypass the one-per-ticket guard when the analyst
deliberately re-requests feedback.

Pre-flight checks (mode, template existence, mailbox availability) all run
BEFORE the row is inserted, so failed sends don't leave orphan tokens behind.

```php
function sendCsatSurvey(PDO $conn, int $ticketId, ?int $analystId, bool $force = false): array
```

Defined in [includes/csat.php](/code/files/includes/csat.php.md), line 306.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `?int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$force` | `bool` | `false` | true to send even when one-survey-per-ticket is on and one already exists |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 314 | `sendCsatSurvey` | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |
| 336 | `sendCsatSurvey` | read [ticket_email_templates](/code/tables/ticket_email_templates.md) |  |
| 353 | `sendCsatSurvey` | **insert** [ticket_csat_responses](/code/tables/ticket_csat_responses.md) (`ticket_id`, `token`, `sent_datetime`, `analyst_id`, `created_at`) |  |

## Calls

[csatBuildUrl()](/code/functions/csatBuildUrl.md), [csatGenerateToken()](/code/functions/csatGenerateToken.md), [csatGetSetting()](/code/functions/csatGetSetting.md), [csatSendInChannel()](/code/functions/csatSendInChannel.md), [csatTicketChannel()](/code/functions/csatTicketChannel.md), [sendTemplateEmail()](/code/functions/sendTemplateEmail.md), [templateGetMailboxForTicket()](/code/functions/templateGetMailboxForTicket.md)

## Called from

- [api/tickets/request_csat.php](/code/files/api/tickets/request_csat.php.md)
- [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) - in `TicketsService::updateTicket`

