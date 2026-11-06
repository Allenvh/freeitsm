# imapVerifyFolder()

Verify a folder exists in a basic IMAP mailbox and report its message counts.

⚠️ WHY THIS EXISTS. verify_mailbox_folder.php was written for Microsoft Graph
and gated on an OAuth token before doing anything else. A basic IMAP mailbox
never has one — it authenticates with the stored username + password — so
Verify answered "Mailbox is not authenticated" for EVERY IMAP mailbox on every
install, no matter how correct the credentials were, without making a single
network call.

The connection is opened through `imapStreamFor()` — the SAME helper the inbound
poll uses — so Verify cannot pass a folder that reading then rejects. That
divergence is exactly what GH #77 was on the Graph side.

A missing folder is reported separately from a failed login, and names the
folders that DO exist: on servers with an "INBOX." personal namespace the real
name is "INBOX.Support", and "Support" alone genuinely will not open.

```php
function imapVerifyFolder(array $mailbox, string $folderName): array
```

Defined in [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), line 684.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mailbox` | `array` |  | the target_mailboxes row, decrypted |
| `$folderName` | `string` |  | the folder as typed in the mailbox settings |

**Returns:** array{displayName:string, totalItemCount:int|null, unreadItemCount:int}

## Calls

[imapListFolders()](/code/functions/imapListFolders.md), [imapStreamFor()](/code/functions/imapStreamFor.md)

## Called from

- [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md)

