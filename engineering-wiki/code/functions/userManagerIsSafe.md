# userManagerIsSafe()

Would setting `$managerId` on `$userId` create a loop?

A manages B manages A is not a hypothetical: it happens whenever two people are
each other's cover, and any code that walks the chain to find an approver would
then walk it forever. Checked here because the database cannot express it.

Returns true if the assignment is SAFE.

```php
function userManagerIsSafe(PDO $conn, int $userId, ?int $managerId): bool
```

Defined in [includes/users.php](/code/files/includes/users.php.md), line 403.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the person (users.id) being given a manager |
| `$managerId` | `?int` |  | the proposed manager's users.id; null (no manager) is always safe |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 410 | `userManagerIsSafe` | read [users](/code/tables/users.md) |  |

## Called from

- [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md)
- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [dsyncResolveManagers()](/code/functions/dsyncResolveManagers.md)

