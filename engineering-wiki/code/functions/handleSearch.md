# handleSearch()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md), [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md)

Find people, teams and groups to add.

All four kinds in one search rather than a type picker first: you know the
NAME of who you want, not which of four tables they live in.

```php
function handleSearch(PDO $conn, array $src): void
```

Defined in [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md), line 352.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$src` | `array` |  | the query string (`$_GET`); q is the name to look for, at least 2 characters |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 369 | `handleSearch` | read [analysts](/code/tables/analysts.md) |  |
| 370 | `handleSearch` | read [teams](/code/tables/teams.md) |  |
| 371 | `handleSearch` | read [users](/code/tables/users.md) |  |
| 372 | `handleSearch` | read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |

## Called from

- [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md)
- [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md)

