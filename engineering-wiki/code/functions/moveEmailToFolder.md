# moveEmailToFolder()

Move an email to a folder via Microsoft Graph API

```php
function moveEmailToFolder($accessToken, $messageId, $folderName)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 694.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` |  |  | the mailbox's Microsoft Graph access token, from `getValidAccessToken()` |
| `$messageId` |  |  | the Graph message id to move |
| `$folderName` |  |  | the destination folder name, or a well-known alias such as 'deleteditems' |

## Calls

[mailboxGraphBase()](/code/functions/mailboxGraphBase.md), [resolveMailFolderId()](/code/functions/resolveMailFolderId.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [handleEmailAfterProcessing()](/code/functions/handleEmailAfterProcessing.md)

