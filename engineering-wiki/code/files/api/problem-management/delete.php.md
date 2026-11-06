# api/problem-management/delete.php

> API endpoint - 29 lines

API: delete a problem (cascades problem_tickets + problem_audit via FKs). Thin UI adapter over ProblemsService::deleteProblem.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/delete.php` |
| Protected by | Module access: problems (requireModuleAccessJson) |
| Called from | [assets/js/problem-management.js](/code/files/assets/js/problem-management.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/problems.php](/code/files/includes/services/problems.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

