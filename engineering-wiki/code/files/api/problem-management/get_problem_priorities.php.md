# api/problem-management/get_problem_priorities.php

> API endpoint - 20 lines

API: the problem priorities, in display order.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/get_problem_priorities.php` |
| Protected by | Module access: problems (requireModuleAccessJson) |
| Reads | `GET manage` |
| Input | manage  optional - when set, inactive priorities are included too (the settings screen) |
| Output | {success, priorities: [...]} |
| Called from | [assets/js/problem-management.js](/code/files/assets/js/problem-management.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 16 |  | read [problem_priorities](/code/tables/problem_priorities.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

