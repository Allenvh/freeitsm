# sla_get_first_active_mailbox()

The active mailbox with the lowest id, decrypted; null when there is none.

```php
function sla_get_first_active_mailbox(PDO $conn): ?array
```

Defined in [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md), line 408.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 409 | `sla_get_first_active_mailbox` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |

## Calls

[decryptMailboxRow()](/code/functions/decryptMailboxRow.md)

*Nothing calls this function by name.*

