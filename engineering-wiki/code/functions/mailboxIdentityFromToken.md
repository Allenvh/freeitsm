# mailboxIdentityFromToken()

Pull the signed-in user's address straight out of a Graph access token. The token is a JWT whose payload carries the user's UPN / email — so we can read it offline, with NO Graph permission. This matters because the usual mailbox scopes (Mail.*) do NOT include User.Read, so a /me call 403s and can't tell us who signed in. Returns a lowercased email, or '' if the token has no usable claim.

```php
function mailboxIdentityFromToken($jwt)
```

Defined in [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), line 83.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$jwt` |  |  | a Graph access token (a JWT); its payload is read without checking the signature |

## Called from

- [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md) - in [mailboxDelegatedIdentity()](/code/functions/mailboxDelegatedIdentity.md), [mailboxIdentityRecord()](/code/functions/mailboxIdentityRecord.md)

