# ssGetSendingMailbox()

First active, send-capable mailbox (has credentials), decrypted. Null if none.

```php
function ssGetSendingMailbox(PDO $conn): ?array
```

Defined in [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), line 15.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 17 | `ssGetSendingMailbox` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |

## Calls

[decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [mailboxCanSend()](/code/functions/mailboxCanSend.md)

## Called from

- [api/lms/reminder_settings.php](/code/files/api/lms/reminder_settings.php.md)
- [api/lms/tests.php](/code/files/api/lms/tests.php.md)
- [includes/self_service_email.php](/code/files/includes/self_service_email.php.md) - in [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md)

