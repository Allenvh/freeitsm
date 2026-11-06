# api/problem-management/save.php

> API endpoint - 38 lines

API: create or update a problem. Thin UI adapter over ProblemsService. The audit trail, PRB-##### numbering, status/closed_datetime transitions and validation live there, shared with the REST API's POST/PATCH /problems. On create the acting company is the analyst's active tenant (auth-adjacent), resolved here and passed in.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/save.php` |
| Protected by | Module access: problems (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md), [assets/js/problem-management.js](/code/files/assets/js/problem-management.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/problems.php](/code/files/includes/services/problems.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

