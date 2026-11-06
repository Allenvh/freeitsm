# imapMailboxRef()

Build the IMAP connection reference string, e.g. {mail.example.com:993/imap/ssl}INBOX. Encryption: 'ssl' → implicit TLS, 'tls' → STARTTLS, anything else → plaintext.

```php
function imapMailboxRef(array $mailbox, ?string $folder = null): string
```

Defined in [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), line 35.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mailbox` | `array` |  | the target_mailboxes row (imap_server, imap_port, imap_encryption, email_folder) |
| `$folder` | `?string` | `null` | the folder to open; null uses the mailbox's email_folder, or INBOX; '' gives the server root |

**Returns:** `string`

## Called from

- [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md) - in [imapStreamFor()](/code/functions/imapStreamFor.md), [imapListFolders()](/code/functions/imapListFolders.md)

