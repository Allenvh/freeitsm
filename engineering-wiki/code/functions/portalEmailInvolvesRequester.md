# portalEmailInvolvesRequester()

Is this message part of the requester's own correspondence?

```php
function portalEmailInvolvesRequester(array $email, ?string $requesterEmail): bool
```

Defined in [includes/portal_visibility.php](/code/files/includes/portal_visibility.php.md), line 97.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$email` | `array` |  | needs: channel, direction, from_address, to_recipients, cc_recipients |
| `$requesterEmail` | `?string` |  | the ticket owner's address. NULL = they have NO mailbox (a directory user); '' = unknown. The two mean opposite things here. |

**Returns:** bool true = the requester's own conversation (always portal-visible)

## Called from

- [includes/portal_visibility.php](/code/files/includes/portal_visibility.php.md) - in [portalEmailVisibility()](/code/functions/portalEmailVisibility.md)

