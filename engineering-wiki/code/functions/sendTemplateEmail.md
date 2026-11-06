# sendTemplateEmail()

Main entry point — send a template email for a ticket event. Returns silently if no active template exists or no mailbox is found. Never throws — errors go to `error_log()`.

`$extraMergeData` allows callers (e.g. the CSAT flow) to inject additional
placeholders like `csat_link` that aren't derivable from the ticket alone.

```php
function sendTemplateEmail(
    PDO $conn,
    int $ticketId,
    string $eventTrigger,
    array $extraMergeData = []
): void
```

Defined in [includes/template_email.php](/code/files/includes/template_email.php.md), line 47.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$eventTrigger` | `string` |  | the template event: 'new_ticket_email', 'ticket_assigned', 'analyst_assigned', 'ticket_closed', 'csat_request' or 'note_shared' |
| `$extraMergeData` | `array` | `[]` | more merge codes the ticket alone cannot supply, as [code => value], e.g. ['csat_link' => url] |

**Returns:** `void`

## Calls

[buildTemplateEmailBody()](/code/functions/buildTemplateEmailBody.md), [buildTicketMergeData()](/code/functions/buildTicketMergeData.md), [emailLogFailed()](/code/functions/emailLogFailed.md), [emailLogSent()](/code/functions/emailLogSent.md), [emailLogSkipped()](/code/functions/emailLogSkipped.md), [gmailGetValidAccessToken()](/code/functions/gmailGetValidAccessToken.md), [gmailSendEmail()](/code/functions/gmailSendEmail.md), [imapSmtpSend()](/code/functions/imapSmtpSend.md), [resolveMergeCodes()](/code/functions/resolveMergeCodes.md), [templateEventAudience()](/code/functions/templateEventAudience.md), [templateGetMailboxForTicket()](/code/functions/templateGetMailboxForTicket.md), [templateGraphContext()](/code/functions/templateGraphContext.md), [templateSaveSentEmail()](/code/functions/templateSaveSentEmail.md), [templateSelectForRecipient()](/code/functions/templateSelectForRecipient.md), [templateSendViaGraph()](/code/functions/templateSendViaGraph.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)
- [includes/csat.php](/code/files/includes/csat.php.md) - in [sendCsatSurvey()](/code/functions/sendCsatSurvey.md)
- [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) - in `TicketsService::updateTicket`, `TicketsService::createNote`

