# getOrCreateUser()

Get or create user by email address Returns the user ID

```php
function getOrCreateUser($conn, $email, $displayName)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 891.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$email` |  |  | the sender's address; lower-cased and trimmed before use |
| `$displayName` |  |  | the sender's name from the email; empty leaves an existing name alone |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 896 | `getOrCreateUser` | read [users](/code/tables/users.md) |  |
| 904 | `getOrCreateUser` | **update** [users](/code/tables/users.md) (`display_name`) |  |
| 912 | `getOrCreateUser` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `created_at`) |  |

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md)

