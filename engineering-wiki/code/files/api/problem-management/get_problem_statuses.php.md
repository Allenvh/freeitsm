# api/problem-management/get_problem_statuses.php

> API endpoint - 20 lines

API: the problem statuses, in display order.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/get_problem_statuses.php` |
| Protected by | Module access: problems (requireModuleAccessJson) |
| Reads | `GET manage` |
| Input | manage  optional - when set, inactive statuses are included too (the settings screen) |
| Output | {success, statuses: [...]} |
| Called from | [assets/js/problem-management.js](/code/files/assets/js/problem-management.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 16 |  | read [problem_statuses](/code/tables/problem_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

