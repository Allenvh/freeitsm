# gmailSendEmail()

Send an email via the Gmail API.

`$to`       - recipient email address
`$subject`  - email subject
`$htmlBody` - HTML body content
`$from`     - sender email address (the mailbox address)
`$cc`       - CC addresses, semicolon/comma separated
`$parts`    - attachments and inline images, Graph-shaped (includes/mime_message.php)

GH #158: `$cc` and `$parts` are new. Before them an analyst's attachments, pasted
images and CC list were all silently dropped on a Google mailbox.

```php
function gmailSendEmail(
    string $accessToken,
    string $to,
    string $subject,
    string $htmlBody,
    string $from = '',
    string $cc = '',
    array $parts = []
): void
```

Defined in [includes/gmail.php](/code/files/includes/gmail.php.md), line 109.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` | `string` |  | a valid Google OAuth access token for the mailbox |
| `$to` | `string` |  | recipient addresses, semicolon/comma separated; invalid ones are dropped |
| `$subject` | `string` |  | the email subject |
| `$htmlBody` | `string` |  | the HTML body |
| `$from` | `string` | `''` | the sender address (the mailbox address) |
| `$cc` | `string` | `''` | CC addresses, semicolon/comma separated; invalid ones are dropped |
| `$parts` | `array` | `[]` | attachments and inline images, Graph-shaped (includes/mime_message.php) |

**Returns:** `void`

## Calls

[mimeBuildMessage()](/code/functions/mimeBuildMessage.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)
- [includes/mailbox_send.php](/code/files/includes/mailbox_send.php.md) - in [mailboxSendSimple()](/code/functions/mailboxSendSimple.md)
- [includes/self_service_email.php](/code/files/includes/self_service_email.php.md) - in [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md)
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [sendTemplateEmail()](/code/functions/sendTemplateEmail.md), [internalTicketEmail()](/code/functions/internalTicketEmail.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_send_email`

