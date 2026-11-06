# logMailboxActivity()

Log mailbox activity (imports and rejections)

```php
function logMailboxActivity($conn, $mailboxId, $entries)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 1593.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailboxId` |  |  | the target_mailboxes id the activity belongs to |
| `$entries` |  |  | one row per email: action, from_address, from_name, subject, reason, ticket_id, processing_log |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1595 | `logMailboxActivity` | **insert** [mailbox_activity_log](/code/tables/mailbox_activity_log.md) (`mailbox_id`, `action`, `from_address`, `from_name`, `subject`, `reason`, `ticket_id`, `processing_log`, `created_datetime`) |  |

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)

