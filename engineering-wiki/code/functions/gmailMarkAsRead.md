# gmailMarkAsRead()

Mark a Gmail message as read (remove UNREAD label).

```php
function gmailMarkAsRead(string $accessToken, string $messageId): void
```

Defined in [includes/gmail.php](/code/files/includes/gmail.php.md), line 554.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` | `string` |  | a valid Google OAuth access token for the mailbox |
| `$messageId` | `string` |  | the Gmail message id |

**Returns:** `void`

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [handleEmailAfterProcessing()](/code/functions/handleEmailAfterProcessing.md)

