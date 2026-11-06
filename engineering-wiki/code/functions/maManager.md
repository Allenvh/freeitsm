# maManager()

The manager, if this analyst may see them.

```php
function maManager(PDO $conn, int $id): ?array
```

Defined in [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md), line 54.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the users.id of the manager (a portal user); 0 or less gives null |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 58 | `maManager` | read [users](/code/tables/users.md), read [tenants](/code/tables/tenants.md) |  |

## Calls

[analystCanAccessUser()](/code/functions/analystCanAccessUser.md)

## Called from

- [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md)

