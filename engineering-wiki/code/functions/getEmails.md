# getEmails()

Retrieve emails from Microsoft Graph API

```php
function getEmails($accessToken, $mailbox)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 590.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` |  |  | the mailbox's Microsoft Graph access token, from `getValidAccessToken()` |
| `$mailbox` |  |  | the target_mailboxes row - email_folder and max_emails_per_check are read |

## Calls

[mailboxGraphBase()](/code/functions/mailboxGraphBase.md), [mailboxGraphGet()](/code/functions/mailboxGraphGet.md), [mailboxResolveFolderId()](/code/functions/mailboxResolveFolderId.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)

