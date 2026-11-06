# api/problem-management/save_problem_status.php

> API endpoint - 50 lines

API: create or update a problem status (Problems -> Settings).

Making one the default clears the default flag on every other status. Fires the
problem_status 'created' or 'updated' workflow event.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/save_problem_status.php` |
| Protected by | Module access: problems (requireModuleAccessJson); Permission: Cap::PROBLEMS_STATUSES (requireCapabilityJson) |
| Input | id             the status to update - omit to create one |
| Input | name           required |
| Input | is_closed      1 when a problem in this status counts as closed |
| Input | colour         optional |
| Input | is_default     1 to make this the default |
| Input | display_order  sort position |
| Input | is_active      1 or 0 (default 1) |
| Output | {success, id} |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 |  | **update** [problem_statuses](/code/tables/problem_statuses.md) (`is_default`) |  |
| 38 |  | **update** [problem_statuses](/code/tables/problem_statuses.md) (`name`, `is_closed`, `colour`, `is_default`, `display_order`, `is_active`) |  |
| 43 |  | **insert** [problem_statuses](/code/tables/problem_statuses.md) (`name`, `is_closed`, `colour`, `is_default`, `display_order`, `is_active`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

