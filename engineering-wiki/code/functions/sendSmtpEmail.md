# sendSmtpEmail()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md), [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md)

Send email via SMTP

```php
function sendSmtpEmail(
    $settings,
    $toEmail,
    $subject,
    $htmlBody,
    $pdfData = null,
    $pdfFilename = null
)
```

Defined in [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md), line 128.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$settings` |  |  | the email settings keyed by name (smtp_host, smtp_from_email, smtp_from_name, ...) |
| `$toEmail` |  |  | the recipient address |
| `$subject` |  |  | the email subject |
| `$htmlBody` |  |  | the email body as HTML |
| `$pdfData` |  | `null` | the PDF to attach, base64-encoded; null sends no attachment |
| `$pdfFilename` |  | `null` | the attachment's file name; only used with `$pdfData` |

## Called from

- [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md)
- [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md)

