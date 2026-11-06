# mailboxIdentityMismatch()

Offline "is this reading the wrong inbox?" check for delegated Microsoft mailboxes. Returns an error message to show/throw, or null if it's fine. A configured target that matches ANY owned address (primary OR alias) passes; an unknown identity set (legacy mailboxes that predate alias capture) is grandfathered through.

```php
function mailboxIdentityMismatch($mailbox)
```

Defined in [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), line 204.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mailbox` |  |  | the target_mailboxes row, decrypted (provider, auth_mode, target_mailbox, authenticated_*) |

## Calls

[mailboxAcceptedSet()](/code/functions/mailboxAcceptedSet.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)
- [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md)

