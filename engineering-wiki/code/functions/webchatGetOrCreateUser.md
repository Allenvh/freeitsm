# webchatGetOrCreateUser()

Find-or-create the requester for a web chat conversation, keyed by the real email the visitor gave. Returns the user id, or null if the users table is unavailable.

```php
function webchatGetOrCreateUser(PDO $conn, string $email, string $name): ?int
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 174.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$email` | `string` |  | the email the visitor gave - unverified; '' gives null |
| `$name` | `string` |  | the name the visitor gave; fills display_name only when it is empty |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 181 | `webchatGetOrCreateUser` | read [users](/code/tables/users.md) |  |
| 186 | `webchatGetOrCreateUser` | **update** [users](/code/tables/users.md) (`display_name`) |  |
| 191 | `webchatGetOrCreateUser` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `created_at`) |  |

## Called from

- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatOpenTicket()](/code/functions/webchatOpenTicket.md)

