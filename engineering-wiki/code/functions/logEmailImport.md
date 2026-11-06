# logEmailImport()

Writes one email_import row to system_logs with the mailbox id added to the details. A logging failure goes to the PHP error log and never stops the import.

```php
function logEmailImport($conn, $mailboxId, $details)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 1523.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailboxId` |  |  | the target_mailboxes id the email came in through |
| `$details` |  |  | what to log, as an array; stored as JSON with mailbox_id added |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1526 | `logEmailImport` | **insert** [system_logs](/code/tables/system_logs.md) (`log_type`, `analyst_id`, `details`, `created_datetime`) |  |

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md)

