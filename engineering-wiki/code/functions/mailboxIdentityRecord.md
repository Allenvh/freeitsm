# mailboxIdentityRecord()

Build the identity record for a freshly-authenticated delegated token: ['primary' => <display address>, 'addresses' => [every address this mailbox owns]]. Combines the token's own claim (always available, offline) with the Graph alias list (when User.Read is granted). 'primary' is what we show; 'addresses' is what we match the configured target against.

```php
function mailboxIdentityRecord($accessToken)
```

Defined in [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), line 167.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` |  |  | a freshly issued delegated Graph access token |

## Calls

[mailboxFetchAddresses()](/code/functions/mailboxFetchAddresses.md), [mailboxIdentityFromToken()](/code/functions/mailboxIdentityFromToken.md)

## Called from

- [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md) - in [mailboxBackfillIdentity()](/code/functions/mailboxBackfillIdentity.md)

