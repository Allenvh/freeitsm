# mailboxCanSend()

Is this mailbox capable of sending right now?

"Has non-empty token_data" is the DELEGATED test and is wrong for app-only: a
client-credentials mailbox that has never polled holds no cached token, but can
mint one from its client secret on demand. Testing for a stored token therefore
hides a perfectly working app-only mailbox — which is why a clean app-only
install reported "no email mailbox is configured" when one plainly was.

```php
function mailboxCanSend($mailbox)
```

Defined in [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), line 296.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mailbox` |  |  | the target_mailboxes row, decrypted |

## Calls

[mailboxIsAppOnly()](/code/functions/mailboxIsAppOnly.md)

## Called from

- [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md)
- [includes/self_service_email.php](/code/files/includes/self_service_email.php.md) - in [ssGetSendingMailbox()](/code/functions/ssGetSendingMailbox.md)

