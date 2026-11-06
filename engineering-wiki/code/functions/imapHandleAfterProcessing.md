# imapHandleAfterProcessing()

Apply the post-import (or post-reject) action to a message by UID. Mirrors `handleEmailAfterProcessing()`'s action vocabulary for Microsoft/Google.

```php
function imapHandleAfterProcessing(
    array $mailbox,
    int $uid,
    string $action,
    ?string $folderName = null
): int
```

Defined in [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), line 419.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mailbox` | `array` |  | the target_mailboxes row, decrypted |
| `$uid` | `int` |  | the message's IMAP UID |
| `$action` | `string` |  | 'mark_read', 'move_to_folder', 'move_to_deleted' or 'delete'; anything else deletes |
| `$folderName` | `?string` | `null` | the folder to move to for 'move_to_folder'; null or '' deletes instead |

**Returns:** `int`

## Calls

[imapStreamFor()](/code/functions/imapStreamFor.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [handleEmailAfterProcessing()](/code/functions/handleEmailAfterProcessing.md)

