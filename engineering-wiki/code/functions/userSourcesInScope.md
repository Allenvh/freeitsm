# userSourcesInScope()

Every source the people in scope are linked to, with how many - what the Source filter offers. Counted inside the caller's company scope, so the list never reveals how many people another company has in a shared directory.

```php
function userSourcesInScope(PDO $conn, string $tenantSql, array $tenantArgs): array
```

Defined in [includes/users.php](/code/files/includes/users.php.md), line 220.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantSql` | `string` |  | `activeTenantFilter()` for alias `u` |
| `$tenantArgs` | `array` |  | the bound values that go with `$tenantSql` |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 224 | `userSourcesInScope` | read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |

## Called from

- [api/assets/get_people.php](/code/files/api/assets/get_people.php.md)
- [api/tickets/get_users.php](/code/files/api/tickets/get_users.php.md)

