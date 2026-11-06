# imapStreamFor()

Open (and cache within the request) an IMAP stream for a mailbox/folder. The cache lets the inbound poll reuse one authenticated connection across the fetch + per-email post-actions rather than logging in repeatedly.

```php
function imapStreamFor(array $mailbox, ?string $folder = null)
```

Defined in [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), line 61.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mailbox` | `array` |  | the target_mailboxes row, decrypted (imap_* settings and password, id) |
| `$folder` | `?string` | `null` | the folder to open; null uses the mailbox's email_folder, or INBOX |

## Calls

[imapExtensionAvailable()](/code/functions/imapExtensionAvailable.md), [imapMailboxRef()](/code/functions/imapMailboxRef.md)

## Called from

- [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md) - in [imapGetEmails()](/code/functions/imapGetEmails.md), [imapHandleAfterProcessing()](/code/functions/imapHandleAfterProcessing.md), [imapVerifyFolder()](/code/functions/imapVerifyFolder.md)

