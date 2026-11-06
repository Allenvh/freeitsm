# api/problem-management/delete_problem_status.php

> API endpoint - 32 lines

API: delete a problem status (Problems -> Settings).

Refused while any problem still uses it - set it inactive instead. Fires the
problem_status 'deleted' workflow event.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/delete_problem_status.php` |
| Protected by | Module access: problems (requireModuleAccessJson); Permission: Cap::PROBLEMS_STATUSES (requireCapabilityJson) |
| Input | id  the status (JSON body) |
| Output | {success} |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 24 |  | read [problems](/code/tables/problems.md) |  |
| 27 |  | read [problem_statuses](/code/tables/problem_statuses.md) |  |
| 28 |  | **delete** [problem_statuses](/code/tables/problem_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

