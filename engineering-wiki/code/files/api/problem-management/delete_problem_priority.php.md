# api/problem-management/delete_problem_priority.php

> API endpoint - 32 lines

API: delete a problem priority (Problems -> Settings).

Refused while any problem still uses it - set it inactive instead. Fires the
problem_priority 'deleted' workflow event.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/delete_problem_priority.php` |
| Protected by | Module access: problems (requireModuleAccessJson); Permission: Cap::PROBLEMS_PRIORITIES (requireCapabilityJson) |
| Input | id  the priority (JSON body) |
| Output | {success} |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 24 |  | read [problems](/code/tables/problems.md) |  |
| 27 |  | read [problem_priorities](/code/tables/problem_priorities.md) |  |
| 28 |  | **delete** [problem_priorities](/code/tables/problem_priorities.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

