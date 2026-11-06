# api/tasks/move_to_company.php

> API endpoint - 62 lines

API: Tasks — move a task to another company (tenant).

Thin UI adapter over TasksService::moveTaskToCompany, which holds every rule
this has: both-ends access, the subtask and linked-record refusals, and
moving the whole subtree in one transaction. Nothing is decided here, so the
REST API and the board cannot end up disagreeing about what a move means.

The Tasks twin of api/change-management/move_to_company.php. No-op surface on
a single-company install — the button is not rendered, and the service
refuses anyway if something calls this directly.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/move_to_company.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

