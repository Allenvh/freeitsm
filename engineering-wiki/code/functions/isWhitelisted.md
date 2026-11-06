# isWhitelisted()

Check if a sender address is whitelisted

```php
function isWhitelisted($fromAddress, $whitelist)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 1567.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fromAddress` |  |  | the sender's address, any case |
| `$whitelist` |  |  | the lists from `loadWhitelist()`: ['domains' => [...], 'emails' => [...]], lower-case |

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)

