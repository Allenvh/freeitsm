# mailboxResolveGraphBase()

Resolve the base path for a mailbox from its auth_mode (Microsoft only). Google mailboxes always behave as delegated.

```php
function mailboxResolveGraphBase($mailbox)
```

Defined in [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), line 279.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mailbox` |  |  | the target_mailboxes row, decrypted (provider, auth_mode, target_mailbox) |

## Calls

[mailboxGraphBase()](/code/functions/mailboxGraphBase.md), [mailboxIsAppOnly()](/code/functions/mailboxIsAppOnly.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)
- [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md)
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [templateGraphContext()](/code/functions/templateGraphContext.md)

