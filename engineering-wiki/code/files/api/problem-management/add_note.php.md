# api/problem-management/add_note.php

> API endpoint - 28 lines

API: add a free-text journal note to a problem. Thin UI adapter over ProblemsService::addNote.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/add_note.php` |
| Protected by | Module access: problems (requireModuleAccessJson) |
| Called from | [assets/js/problem-management.js](/code/files/assets/js/problem-management.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/problems.php](/code/files/includes/services/problems.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

