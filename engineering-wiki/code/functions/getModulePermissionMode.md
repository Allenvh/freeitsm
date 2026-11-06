# getModulePermissionMode()

Site-wide policy for combining module grants from several sources (issue #30): 'most' (default) — an analyst may use a module if ANY source grants it (union). 'least' — only if their own access AND every team allow it (intersection).

```php
function getModulePermissionMode(PDO $conn): string
```

Defined in [includes/functions.php](/code/files/includes/functions.php.md), line 95.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 99 | `getModulePermissionMode` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/system/get_effective_module_access.php](/code/files/api/system/get_effective_module_access.php.md)
- [api/system/get_module_access.php](/code/files/api/system/get_module_access.php.md)
- [includes/functions.php](/code/files/includes/functions.php.md) - in [getAnalystAllowedModules()](/code/functions/getAnalystAllowedModules.md)

