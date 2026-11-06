# gmailGetEmails()

Fetch unread emails from Gmail. Returns an array of messages normalised to the same shape as Graph API results.

```php
function gmailGetEmails(string $accessToken, array $mailbox): array
```

Defined in [includes/gmail.php](/code/files/includes/gmail.php.md), line 261.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` | `string` |  | a valid Google OAuth access token for the mailbox |
| `$mailbox` | `array` |  | the target_mailboxes row - email_folder (the label) and max_emails_per_check (default 10) |

**Returns:** `array`

## Calls

[gmailGetMessage()](/code/functions/gmailGetMessage.md), [gmailResolveListLabelId()](/code/functions/gmailResolveListLabelId.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)

