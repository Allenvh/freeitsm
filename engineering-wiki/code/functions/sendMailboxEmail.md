# sendMailboxEmail()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md), [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md)

Send email via Microsoft 365 mailbox using Graph API

```php
function sendMailboxEmail(
    $conn,
    $mailboxId,
    $toEmail,
    $subject,
    $htmlBody,
    $pdfData = null,
    $pdfFilename = null
)
```

Defined in [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md), line 182.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailboxId` |  |  | the target_mailboxes id to send from; empty fails with "No mailbox selected" |
| `$toEmail` |  |  | the recipient address |
| `$subject` |  |  | the email subject |
| `$htmlBody` |  |  | the email body as HTML |
| `$pdfData` |  | `null` | the PDF to attach, base64-encoded; null sends no attachment |
| `$pdfFilename` |  | `null` | the attachment's file name; only used with `$pdfData` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 188 | `sendMailboxEmail` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |

## Calls

[decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [getValidAccessToken()](/code/functions/getValidAccessToken.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md)
- [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md)

