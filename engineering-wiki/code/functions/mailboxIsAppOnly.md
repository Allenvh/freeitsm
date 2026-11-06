# mailboxIsAppOnly()

Is this a Microsoft mailbox authenticating as itself (client credentials)? There is no signed-in user in that mode, so '/me' is meaningless to Graph and every call has to name the mailbox: /users/<target_mailbox>. Google and IMAP mailboxes always behave as delegated.

```php
function mailboxIsAppOnly($mailbox)
```

Defined in [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), line 268.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mailbox` |  |  | the target_mailboxes row (provider, auth_mode) |

## Called from

- [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md) - in [mailboxResolveGraphBase()](/code/functions/mailboxResolveGraphBase.md), [mailboxCanSend()](/code/functions/mailboxCanSend.md)
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [templateGraphContext()](/code/functions/templateGraphContext.md)

