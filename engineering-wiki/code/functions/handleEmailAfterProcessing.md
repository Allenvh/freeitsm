# handleEmailAfterProcessing()

Handle email after processing based on action setting Actions: 'delete' (permanent), 'move_to_deleted' (Deleted Items), 'mark_read' (leave in inbox), 'move_to_folder' (custom folder)

```php
function handleEmailAfterProcessing(
    $accessToken,
    $messageId,
    $action,
    $folderName = null,
    $provider = 'microsoft',
    $mailbox = null
)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 777.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` |  |  | the provider access token (Graph or Gmail); unused for IMAP |
| `$messageId` |  |  | the provider's message id - the IMAP UID for a basic mailbox |
| `$action` |  |  | 'delete', 'move_to_deleted', 'mark_read' or 'move_to_folder' |
| `$folderName` |  | `null` | the folder for 'move_to_folder'; without one the email is deleted instead (Microsoft) |
| `$provider` |  | `'microsoft'` | 'microsoft', 'google' or 'imap' |
| `$mailbox` |  | `null` | the target_mailboxes row; needed only for IMAP |

## Calls

[deleteEmailFromMailbox()](/code/functions/deleteEmailFromMailbox.md), [gmailMarkAsRead()](/code/functions/gmailMarkAsRead.md), [gmailTrashMessage()](/code/functions/gmailTrashMessage.md), [imapHandleAfterProcessing()](/code/functions/imapHandleAfterProcessing.md), [markEmailAsRead()](/code/functions/markEmailAsRead.md), [moveEmailToFolder()](/code/functions/moveEmailToFolder.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)

