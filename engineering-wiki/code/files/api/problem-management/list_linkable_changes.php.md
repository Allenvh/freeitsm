# api/problem-management/list_linkable_changes.php

> API endpoint - 46 lines

API: list changes that can be linked to a problem (the fix), excluding any already linked. Optional ?q search on title/id. Used by the "Link change" picker. Changes aren't tenant-scoped, so no company filter here (mirrors change list.php).

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/list_linkable_changes.php` |
| Protected by | Module access: problems (requireModuleAccessJson) |
| Reads | `GET problem_id`, `GET q` |
| Called from | [assets/js/problem-management.js](/code/files/assets/js/problem-management.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 24 |  | read [change_relations](/code/tables/change_relations.md) |  |
| 32 |  | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md), read [change_priorities](/code/tables/change_priorities.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessProblem()](/code/functions/analystCanAccessProblem.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

