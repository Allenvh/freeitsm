# fetchEmailAttachments()

Fetch attachments from Graph API

```php
function fetchEmailAttachments($accessToken, $emailId)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 925.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` |  |  | the mailbox's Microsoft Graph access token, from `getValidAccessToken()` |
| `$emailId` |  |  | the Graph message id whose attachments are wanted |

## Calls

[mailboxGraphBase()](/code/functions/mailboxGraphBase.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md), [oversizeMeasure()](/code/functions/oversizeMeasure.md)

