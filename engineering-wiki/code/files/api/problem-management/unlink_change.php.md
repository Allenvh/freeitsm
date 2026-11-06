# api/problem-management/unlink_change.php

> API endpoint - 26 lines

API: unlink a change from a problem. Thin adapter over ProblemsService::unlinkChange.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/unlink_change.php` |
| Protected by | Module access: problems (requireModuleAccessJson) |
| Called from | [assets/js/problem-management.js](/code/files/assets/js/problem-management.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/problems.php](/code/files/includes/services/problems.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

