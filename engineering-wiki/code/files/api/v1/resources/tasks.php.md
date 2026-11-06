# api/v1/resources/tasks.php

> API endpoint - 505 lines

FreeITSM REST API v1 — tasks resource (kanban Tasks module).

Mirrors the module's internal endpoints so a task touched via the API is
indistinguishable from one touched in the UI:
  - create mirrors api/tasks/save.php: only title is required, defaults
    To Do / Medium (falling back to the is_default rows), board_position
    appends to the end of the target status column.
  - status changes drive completed_datetime exactly like the UI
    (COALESCE-stamped on entering a closed status, cleared on reopening),
    and a PATCH that moves an open task into a closed status fires the
    task.completed workflow event with save.php's exact payload.
  - POST /tasks/{id}/move mirrors reorder.php: status + position change
    with the column re-packed — and, like the UI's drag, it does NOT fire
    the workflow event (that asymmetry is the product's, preserved for
    parity).
  - comments are create-only (no edit/delete exists in the product);
    DELETE is a hard delete that cascades subtasks/comments/tags.

Tasks have no audit trail in the product — none is invented here.
Task tables carry no tenant_id (install-wide), BUT a task can link to a
ticket, and tickets ARE company-scoped: the API validates ticket links
against the key's company scope and only reveals linked-ticket details the
key could read directly — tighter than the UI, which doesn't check.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/tasks.php` |
| Protected by | REST API key |
| Reads | `GET parent_task_id`, `GET state`, `GET collaborator_id`, `GET involved_analyst_id`, `GET unassigned`, `GET tag`, `GET q`, `GET overdue`, `GET sort` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiTaskSelect()](/code/functions/apiTaskSelect.md) | 41 | The SELECT for tasks, with status, priority, analyst, team and creator names. |
| [apiTaskContractor()](/code/functions/apiTaskContractor.md) | 57 | Contractors (3.3.0): the supplier and the person there, by name - includes/task_contractors.php. |
| [apiSerializeTask()](/code/functions/apiSerializeTask.md) | 68 | Shapes a task row for the API, with its tags and subtask progress (total and done) read alongside. |
| [apiLoadTask()](/code/functions/apiLoadTask.md) | 138 | Loads one task row, or answers 404 and stops - also for a task in a company the key cannot reach, when the key is given. |
| [apiTasksList()](/code/functions/apiTasksList.md) | 160 | GET /tasks |
| [apiTasksGet()](/code/functions/apiTasksGet.md) | 302 | GET /tasks/{id} |
| [apiTasksCreate()](/code/functions/apiTasksCreate.md) | 382 | POST /tasks |
| [apiTasksUpdate()](/code/functions/apiTasksUpdate.md) | 392 | PATCH /tasks/{id} |
| [apiTasksMove()](/code/functions/apiTasksMove.md) | 402 | POST /tasks/{id}/move — kanban move (mirrors reorder.php: NO workflow event) |
| [apiTasksDelete()](/code/functions/apiTasksDelete.md) | 415 | DELETE /tasks/{id} — hard delete. Children removed explicitly, not via FK cascade: installs grown via Database Verify were missing the parent and comments cascade FKs, which orphaned subtasks/comments (same fix as the UI's delete.php). |
| [apiTaskCommentsList()](/code/functions/apiTaskCommentsList.md) | 425 | Comments — create-only, like the UI |
| [apiTaskCommentsCreate()](/code/functions/apiTaskCommentsCreate.md) | 445 | POST /tasks/{id}/comments - adds a comment to the task through TasksService. |
| [apiTaskStatusesList()](/code/functions/apiTaskStatusesList.md) | 455 | Reference lookups |
| [apiTaskPrioritiesList()](/code/functions/apiTaskPrioritiesList.md) | 474 | GET /task-priorities - the task priorities, in display order. |
| [apiTaskTagsList()](/code/functions/apiTaskTagsList.md) | 490 | GET /task-tags - the task tags, with how many tasks carry each. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 | `apiTaskSelect` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [task_priorities](/code/tables/task_priorities.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md) |  |
| 74 | `apiSerializeTask` | read [task_tag_map](/code/tables/task_tag_map.md), read [task_tags](/code/tables/task_tags.md) |  |
| 80 | `apiSerializeTask` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 212 | `apiTasksList` | read [task_collaborators](/code/tables/task_collaborators.md) |  |
| 216 | `apiTasksList` | read [task_collaborators](/code/tables/task_collaborators.md) |  |
| 231 | `apiTasksList` | read [task_tag_map](/code/tables/task_tag_map.md), read [task_tags](/code/tables/task_tags.md) |  |
| 277 | `apiTasksList` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [task_priorities](/code/tables/task_priorities.md) |  |
| 309 | `apiTasksGet` | read [tasks](/code/tables/tasks.md) |  |
| 319 | `apiTasksGet` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 338 | `apiTasksGet` | read [tickets](/code/tables/tickets.md) |  |
| 351 | `apiTasksGet` | read [changes](/code/tables/changes.md) |  |
| 362 | `apiTasksGet` | read [task_comments](/code/tables/task_comments.md), read [analysts](/code/tables/analysts.md) |  |
| 429 | `apiTaskCommentsList` | read [task_comments](/code/tables/task_comments.md), read [analysts](/code/tables/analysts.md) |  |
| 457 | `apiTaskStatusesList` | read [task_statuses](/code/tables/task_statuses.md) |  |
| 476 | `apiTaskPrioritiesList` | read [task_priorities](/code/tables/task_priorities.md) |  |
| 492 | `apiTaskTagsList` | read [task_tag_map](/code/tables/task_tag_map.md), read [task_tags](/code/tables/task_tags.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/task_contractors.php](/code/files/includes/task_contractors.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md), [apiKeyCanAccessTicket()](/code/functions/apiKeyCanAccessTicket.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiPagination()](/code/functions/apiPagination.md), [apiParseDateOnly()](/code/functions/apiParseDateOnly.md), [apiRespond()](/code/functions/apiRespond.md), [naive_today_sql()](/code/functions/naive_today_sql.md), [tasksContractors()](/code/functions/tasksContractors.md)

