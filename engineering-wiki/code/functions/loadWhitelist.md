# loadWhitelist()

Load whitelist entries for a mailbox

```php
function loadWhitelist($conn, $mailboxId)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 1541.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailboxId` |  |  | the target_mailboxes id whose whitelist is wanted |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1542 | `loadWhitelist` | read [mailbox_email_whitelist](/code/tables/mailbox_email_whitelist.md) |  |

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)

