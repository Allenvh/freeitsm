# api/tasks/checklists.php

> API endpoint - 132 lines

API: Tasks — checklists on a task (discussion #138, round three).

GET  ?task_id=N                 -> { enabled, checklists, templates }
GET  ?task_id=N&check=complete  -> { enabled, blocking, warning }   what completing it now would hit
POST { action: attach,  task_id, template_id }
POST { action: toggle,  item_id, completed, response_value }
POST { action: remove,  checklist_id }

Thin UI adapter over ChecklistsService, which holds every rule. Same shape as
collaborators.php beside it.

🔒 Every action is gated on the TASK, including the two addressed by a child
id (a step, a checklist): resolve the child to its task first, then ask
`analystCanAccessTask()`. A child id with no gate of its own is exactly how
another company's data leaks - the ticket-side endpoint had that hole until
it was fixed alongside this one. Out of scope answers "not found", never
"not yours".

⚠️ While Tasks → Settings → Checklists is OFF, GET says so and every write is
refused: the panel shows nothing, and nothing can be attached behind its back.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/checklists.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |
| Reads | `GET task_id`, `GET check` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [checklistTaskGate()](/code/functions/checklistTaskGate.md) | 39 | Refuse unless this analyst can reach the task. Same wording for "gone" and "not yours". |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/checklists.php](/code/files/includes/services/checklists.php.md)

**Calls:** [analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

