# api/change-management/delete.php

> API endpoint - 36 lines

API Endpoint: Delete a change record. Thin UI adapter over ChangesService::deleteChange (removes attachment files + rows, cascades children).

## The endpoint

| | |
|---|---|
| URL | `api/change-management/delete.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/changes.php](/code/files/includes/services/changes.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

