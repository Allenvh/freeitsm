# imapSmtpSend()

Send an HTML email via SMTP (with auth). Minimal, dependency-free.

`$to` / `$cc` are semicolon/comma separated address strings. `$parts` is the list of
attachments and inline images, Graph-shaped — see includes/mime_message.php.
Before GH #158 there was no `$parts`, and an analyst's attachment never left.

```php
function imapSmtpSend(
    array $mailbox,
    string $to,
    string $cc,
    string $subject,
    string $htmlBody,
    array $parts = []
): void
```

Defined in [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), line 464.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mailbox` | `array` |  | the target_mailboxes row, decrypted (smtp_* settings, falling back to the imap_* login) |
| `$to` | `string` |  | recipient addresses, separated by ; or , |
| `$cc` | `string` |  | copy addresses, separated by ; or ,; '' for none |
| `$subject` | `string` |  | the subject line |
| `$htmlBody` | `string` |  | the HTML body |
| `$parts` | `array` | `[]` | attachments and inline images, Graph-shaped - see includes/mime_message.php |

**Returns:** `void`

## Calls

[imapSmtpClientHostname()](/code/functions/imapSmtpClientHostname.md), [imapSmtpCommand()](/code/functions/imapSmtpCommand.md), [imapSmtpExpect()](/code/functions/imapSmtpExpect.md), [imapSplitAddresses()](/code/functions/imapSplitAddresses.md), [mimeBuildMessage()](/code/functions/mimeBuildMessage.md)

## Called from

- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)
- [includes/mailbox_send.php](/code/files/includes/mailbox_send.php.md) - in [mailboxSendSimple()](/code/functions/mailboxSendSimple.md)
- [includes/self_service_email.php](/code/files/includes/self_service_email.php.md) - in [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md)
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [sendTemplateEmail()](/code/functions/sendTemplateEmail.md), [internalTicketEmail()](/code/functions/internalTicketEmail.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_send_email`

