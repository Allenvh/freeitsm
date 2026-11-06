# api/forms/get_settings.php

> API endpoint - 39 lines

API Endpoint: Get forms module settings

## The endpoint

| | |
|---|---|
| URL | `api/forms/get_settings.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

