# integrationsTicketAttachments()

The attachments on a ticket that are worth sending to a tracker.

⚠️ **Inline images are excluded, deliberately.** An HTML email carries every
signature logo, tracking pixel and marketing graphic as an inline attachment —
on this install the inline files run from 501 bytes upward and are almost all
exactly that. Pushing them would put a dozen junk images on every issue, and
an integration that cries wolf gets ignored. `is_inline = 0` means somebody
deliberately attached the file, which is precisely the signal we want.

The cost is a screenshot *pasted into* an email body rather than attached: it
arrives inline and will not travel. The description always links back to the
ticket, where it is visible.

⚠️ Internal notes have no attachments at all in this schema, so the rule that
internal content never crosses holds here for free rather than by a check.

```php
function integrationsTicketAttachments(
    PDO $conn,
    int $ticketId,
    int $maxFiles = INTEGRATION_ATTACH_MAX_FILES
): array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 732.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$maxFiles` | `int` | `INTEGRATION_ATTACH_MAX_FILES` | how many files may be sent; the rest are returned with skip_reason 'over_limit' |

**Returns:** array [['id','filename','content_type','size','path','skip_reason'], …] `skip_reason` set means: show it, do not send it.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 736 | `integrationsTicketAttachments` | read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |

## Calls

[integrationsAttachmentRoot()](/code/functions/integrationsAttachmentRoot.md)

## Called from

- [api/integrations/escalate_ticket.php](/code/files/api/integrations/escalate_ticket.php.md)
- [api/integrations/send_attachment.php](/code/files/api/integrations/send_attachment.php.md)
- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsEscalate()](/code/functions/integrationsEscalate.md)

