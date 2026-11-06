# api/tasks/collaborators.php

> API endpoint - 123 lines

API: Tasks — the other people on a task ("Involved", GH #89).

GET  ?task_id=N            -> { collaborators, candidates, completion_enabled }
POST { task_id, analyst_id, action: add | remove | done | undone }

Thin UI adapter over TasksService, which holds every rule: the owner is never
a collaborator, collaborators are top-level tasks only, and an analyst can
only be added if they can reach the task's company.

🔒 THE CANDIDATE LIST IS THE SAME QUESTION AS THE SAVE, so it is answered the
same way. A picker offering analysts from other companies is a disclosure on
its own, before anybody is added and whether or not the save would refuse it.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/collaborators.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |
| Reads | `GET task_id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [collaboratorCandidates()](/code/functions/collaboratorCandidates.md) | 93 | Who may be offered as a collaborator on this task: active analysts, minus the owner, minus anybody already on it, and — on a multi-company install — only those who can reach the task's own company. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 95 | `collaboratorCandidates` | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

