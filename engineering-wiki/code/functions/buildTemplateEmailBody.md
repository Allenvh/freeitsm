# buildTemplateEmailBody()

Wraps a template email body in the house HTML, the "Please reply above this line" marker and the hidden SDREF tag that threads a reply back to the ticket.

Plain-text bodies are escaped and their newlines turned into <br>.

```php
function buildTemplateEmailBody(
    string $bodyContent,
    string $ticketNumber,
    ?bool $isHtml = null
): string
```

Defined in [includes/template_email.php](/code/files/includes/template_email.php.md), line 455.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$bodyContent` | `string` |  | the merged body - HTML, or plain text which is escaped here |
| `$ticketNumber` | `string` |  | the ticket number written into the hidden SDREF reply tag |
| `$isHtml` | `?bool` | `null` | Whether the TEMPLATE was HTML, decided before merge codes were substituted. Pass it whenever you know: sniffing the merged body means a data value containing a tag decides the question for the whole email. Null keeps the old sniff for callers that have no template to inspect. |

**Returns:** `string`

## Called from

- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [sendTemplateEmail()](/code/functions/sendTemplateEmail.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_send_email`

