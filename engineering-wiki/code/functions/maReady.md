# maReady()

Are the tables there? Before DB Verify there is nothing to show or change.

```php
function maReady(PDO $conn): bool
```

Defined in [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md), line 68.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 70 | `maReady` | read [manager_grants](/code/tables/manager_grants.md) |  |

## Called from

- [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md) - in [maState()](/code/functions/maState.md)

