# rewriteCidReferences()

Rewrite CID references in email body

```php
function rewriteCidReferences($bodyContent, $dbEmailId, $attachments)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 1040.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$bodyContent` |  |  | the email's HTML body |
| `$dbEmailId` |  |  | the emails.id the attachments were saved under |
| `$attachments` |  |  | the saved attachment rows; each content_id is pointed at get_attachment.php |

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md)

