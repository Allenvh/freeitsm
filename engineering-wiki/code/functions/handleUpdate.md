# handleUpdate()

Renames an active user group and updates its description, refusing a name another group already has.

```php
function handleUpdate(PDO $conn, array $input): void
```

Defined in [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md), line 305.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$input` | `array` |  | the decoded JSON request: id, name and an optional description |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 313 | `handleUpdate` | read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |
| 321 | `handleUpdate` | **update** [knowledge_user_groups](/code/tables/knowledge_user_groups.md) (`name`, `description`, `updated_datetime`) |  |

## Called from

- [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md)

