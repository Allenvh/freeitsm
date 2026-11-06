# mailboxAcceptedSet()

The set of addresses a (delegated) mailbox is known to own, read back from the stored columns (authenticated_addresses JSON + authenticated_as). Offline, lowercased. Empty means "we don't know yet" — callers treat that as grandfathered, not a mismatch.

```php
function mailboxAcceptedSet($mailbox)
```

Defined in [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), line 182.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mailbox` |  |  | the target_mailboxes row (authenticated_addresses JSON, authenticated_as) |

## Called from

- [api/tickets/get_mailboxes.php](/code/files/api/tickets/get_mailboxes.php.md)
- [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md) - in [mailboxIdentityMismatch()](/code/functions/mailboxIdentityMismatch.md)

