# api/change-management/save_comment.php

> API endpoint - 32 lines

API Endpoint: Add a comment to a change. Thin UI adapter over ChangesService::createComment (append internal comment + audit-trail preview row).

## The endpoint

| | |
|---|---|
| URL | `api/change-management/save_comment.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/changes.php](/code/files/includes/services/changes.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

