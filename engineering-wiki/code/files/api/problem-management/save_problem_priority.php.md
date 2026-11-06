# api/problem-management/save_problem_priority.php

> API endpoint - 48 lines

API: create or update a problem priority (Problems -> Settings).

Making one the default clears the default flag on every other priority. Fires the
problem_priority 'created' or 'updated' workflow event.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/save_problem_priority.php` |
| Protected by | Module access: problems (requireModuleAccessJson); Permission: Cap::PROBLEMS_PRIORITIES (requireCapabilityJson) |
| Input | id             the priority to update - omit to create one |
| Input | name           required |
| Input | colour         optional |
| Input | is_default     1 to make this the default |
| Input | display_order  sort position |
| Input | is_active      1 or 0 (default 1) |
| Output | {success, id} |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | **update** [problem_priorities](/code/tables/problem_priorities.md) (`is_default`) |  |
| 36 |  | **update** [problem_priorities](/code/tables/problem_priorities.md) (`name`, `colour`, `is_default`, `display_order`, `is_active`) |  |
| 41 |  | **insert** [problem_priorities](/code/tables/problem_priorities.md) (`name`, `colour`, `is_default`, `display_order`, `is_active`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

