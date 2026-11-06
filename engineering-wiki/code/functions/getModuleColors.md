# getModuleColors()

Every module's colour pair, the built-in defaults overridden by any module_color_* system setting.

Cached per request; if the database cannot be read the defaults are returned.

```php
function getModuleColors()
```

Defined in [includes/module-colors.php](/code/files/includes/module-colors.php.md), line 57.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 71 | `getModuleColors` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md)

## Called from

- [includes/db_verify_modules.php](/code/files/includes/db_verify_modules.php.md) - in [dbVerifyModuleMeta()](/code/functions/dbVerifyModuleMeta.md)
- [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md)
- [index.php](/code/files/index.php.md)
- [watchtower/settings/index.php](/code/files/watchtower/settings/index.php.md)

