# projectRolesForSettings()

The roles list with how many members hold each - for the Roles tab.

```php
function projectRolesForSettings(PDO $conn): array
```

Defined in [api/projects/settings.php](/code/files/api/projects/settings.php.md), line 28.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 | `projectRolesForSettings` | read [project_members](/code/tables/project_members.md), read [project_roles](/code/tables/project_roles.md) |  |

## Called from

- [api/projects/settings.php](/code/files/api/projects/settings.php.md)

