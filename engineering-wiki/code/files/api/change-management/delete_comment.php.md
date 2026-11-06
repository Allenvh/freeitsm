# api/change-management/delete_comment.php

> API endpoint - 36 lines

API Endpoint: Delete a comment from a change. Thin UI adapter over ChangesService::deleteComment (unscoped — by comment id alone, as this endpoint carries no change id).

## The endpoint

| | |
|---|---|
| URL | `api/change-management/delete_comment.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/changes.php](/code/files/includes/services/changes.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

