# includes/module-colors.php

> PHP - 89 lines

Module Colours - shared helper Provides default and database-overridden module colours for dynamic CSS generation. Included by waffle-menu.php and index.php.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [getModuleColors()](/code/functions/getModuleColors.md) | 57 | Every module's colour pair, the built-in defaults overridden by any module_color_* system setting. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 71 | `getModuleColors` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md)

**Included by:** [includes/db_verify_modules.php](/code/files/includes/db_verify_modules.php.md), [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md), [system/colours/index.php](/code/files/system/colours/index.php.md), [watchtower/settings/index.php](/code/files/watchtower/settings/index.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

