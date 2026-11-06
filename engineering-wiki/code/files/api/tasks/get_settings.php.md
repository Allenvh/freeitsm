# api/tasks/get_settings.php

> API endpoint - 122 lines

API: Tasks — Get module settings Returns tasks_* keys from system_settings (prefix stripped).

## The endpoint

| | |
|---|---|
| URL | `api/tasks/get_settings.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 21 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

