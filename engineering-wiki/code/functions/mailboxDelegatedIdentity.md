# mailboxDelegatedIdentity()

Who does a (delegated) access token belong to? Returns the lowercased email, or '' if it can't be determined. Used to back-fill authenticated_as for mailboxes that signed in before we started recording it.

Reads the token's own JWT claims first (offline, no permission needed); only
falls back to Graph /me if the token isn't a readable JWT (needs User.Read).

```php
function mailboxDelegatedIdentity($accessToken)
```

Defined in [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), line 108.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` |  |  | a delegated Graph access token |

## Calls

[mailboxIdentityFromToken()](/code/functions/mailboxIdentityFromToken.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

*Nothing calls this function by name.*

