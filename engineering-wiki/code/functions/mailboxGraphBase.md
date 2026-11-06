# mailboxGraphBase()

Per-request Graph base path: '/me' (delegated) or '/users/<addr>' (app-only). Set once after the mailbox is loaded; the Graph helpers read it back.

⚠️ This is per-REQUEST state with a '/me' default, so a caller that forgets to
resolve silently gets '/me', and a loop over several mailboxes keeps the previous
one's base. Read the return value of `mailboxResolveGraphBase()` and pass it along
explicitly rather than relying on this being set — see `templateGraphContext()`.

```php
function mailboxGraphBase($set = null)
```

Defined in [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), line 254.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$set` |  | `null` | the new base path ('/me' or '/users/<address>'), or null just to read it |

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [getEmails()](/code/functions/getEmails.md), [deleteEmailFromMailbox()](/code/functions/deleteEmailFromMailbox.md), [moveEmailToFolder()](/code/functions/moveEmailToFolder.md), [markEmailAsRead()](/code/functions/markEmailAsRead.md), [fetchEmailAttachments()](/code/functions/fetchEmailAttachments.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [sendEmailViaGraph()](/code/functions/sendEmailViaGraph.md)
- [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md)
- [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md) - in [mailboxResolveGraphBase()](/code/functions/mailboxResolveGraphBase.md), [mailboxResolveFolderId()](/code/functions/mailboxResolveFolderId.md)

