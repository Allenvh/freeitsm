# mailboxSendSimple()

Sends one HTML email from a mailbox through its own provider (IMAP/SMTP, Gmail or Microsoft Graph) and logs it.

Returns null on success, or the error message when sending failed.

```php
function mailboxSendSimple(
    PDO $conn,
    array $mailbox,
    string $to,
    string $subject,
    string $htmlBody,
    string $route,
    ?int $ticketId = null
): ?string
```

Defined in [includes/mailbox_send.php](/code/files/includes/mailbox_send.php.md), line 29.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailbox` | `array` |  | the target_mailboxes row, decrypted; its provider ('imap', 'google', else Microsoft) picks the route |
| `$to` | `string` |  | one recipient address |
| `$subject` | `string` |  | the subject line |
| `$htmlBody` | `string` |  | the HTML body |
| `$route` | `string` |  | what the send log records it as, e.g. 'oversize_alert' or 'oversize_reply' |
| `$ticketId` | `?int` | `null` | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?string`

## Calls

[emailLogFailed()](/code/functions/emailLogFailed.md), [emailLogSent()](/code/functions/emailLogSent.md), [gmailGetValidAccessToken()](/code/functions/gmailGetValidAccessToken.md), [gmailSendEmail()](/code/functions/gmailSendEmail.md), [imapSmtpSend()](/code/functions/imapSmtpSend.md), [templateGraphContext()](/code/functions/templateGraphContext.md), [templateSendViaGraph()](/code/functions/templateSendViaGraph.md)

## Called from

- [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md) - in [oversizeNotify()](/code/functions/oversizeNotify.md)

