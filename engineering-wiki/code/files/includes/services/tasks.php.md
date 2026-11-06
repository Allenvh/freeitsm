# includes/services/tasks.php

> PHP - 1792 lines

TasksService — the shared write rules for the kanban Tasks module: task create/update, the kanban move (re-pack a column), delete (subtask tree) and comment create.

Shared by the UI endpoints (api/tasks/*.php) and the REST API
(api/v1/resources/tasks.php). Each caller passes an ActorContext + canonical
input; this layer validates + writes and returns the affected id(s) or throws
ServiceError. It never emits HTTP.

SCOPE: covers the cleanly-duplicated overlaps. The UI's drag endpoint
(reorder.php) sends client-computed positions whereas the API's /move
re-packs server-side — a structural mismatch — so reorder.php stays on its own
code; the subtask-toggle convenience and the lookup SETTINGS (statuses /
priorities / tags) are UI-only (no API twin) and stay put too.

Canonical behaviour = the API resource's: only title is required; status /
priority resolve by name or id (strict 422 on unknown, else the To Do /
Medium default); analyst / team / tags / links are validated; a task's
completed_datetime is stamped on entering a closed status and cleared on
reopening; a PATCH (not a move) that closes an open task fires task.completed.

🔒 Company scope: tasks now carry their own `tenant_id` (GH #83 groundwork).
A task is SCOPED DATA like a ticket or an asset, so `NULL` means "the Default
company's", NOT "shared with everyone" — see the Multi-Tenancy Developer Guide
on the three meanings of NULL, because two of them are opposites.

Enforcement sits in `loadTaskRow()`, which every by-id path (update, move,
delete, comment) already funnelled through, so the gate is in one place rather
than four. Ticket links are still validated separately: a task and the ticket
it points at must both be reachable by the actor.

Before this, tasks had no tenant_id at all and the only isolation was that
ticket-link check — a workaround for the missing column rather than scope.

Canonical input keys: title, description, status / status_id, priority /
priority_id, assigned_analyst_id, assigned_team_id, start_date, due_date,
parent_task_id, ticket_id, change_id, contract_id, board_position, tags[],
estimate_hours (3.3.0; hours, up to two decimals, NULL = not estimated).

## Classes

- [TasksService](/code/classes/TasksService.md) (class, line 48)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 118 | `TasksService::createTask` | read [tasks](/code/tables/tasks.md) |  |
| 124 | `TasksService::createTask` | read [tasks](/code/tables/tasks.md) |  |
| 137 | `TasksService::createTask` | **insert** [tasks](/code/tables/tasks.md) (`title`, `description`, `status_id`, `priority_id`, `start_date`, `due_date`, `assigned_analyst_id`, `assigned_team_id`, `parent_task_id`, `ticket_id`, `change_id`, `contract_id`) |  |
| 152 | `TasksService::createTask` | **update** [tasks](/code/tables/tasks.md) (`estimate_hours`) |  |
| 155 | `TasksService::createTask` | **update** [tasks](/code/tables/tasks.md) (`assigned_supplier_id`, `assigned_contact_id`) |  |
| 380 | `TasksService::updateTask` | **update** [tasks](/code/tables/tasks.md) |  |
| 384 | `TasksService::updateTask` | **update** [tasks](/code/tables/tasks.md) (`updated_datetime`) |  |
| 444 | `TasksService::moveTask` | **update** [tasks](/code/tables/tasks.md) (`status_id`, `completed_datetime`) |  |
| 451 | `TasksService::moveTask` | read [tasks](/code/tables/tasks.md) |  |
| 461 | `TasksService::moveTask` | **update** [tasks](/code/tables/tasks.md) (`board_position`) |  |
| 540 | `TasksService::deleteTask` | read [tasks](/code/tables/tasks.md) |  |
| 559 | `TasksService::deleteTask` | **delete** [task_comments](/code/tables/task_comments.md) |  |
| 560 | `TasksService::deleteTask` | **delete** [task_tag_map](/code/tables/task_tag_map.md) |  |
| 565 | `TasksService::deleteTask` | **delete** [tasks](/code/tables/tasks.md) |  |
| 588 | `TasksService::createComment` | **insert** [task_comments](/code/tables/task_comments.md) (`task_id`, `analyst_id`, `comment`, `created_datetime`) |  |
| 591 | `TasksService::createComment` | **update** [tasks](/code/tables/tasks.md) (`updated_datetime`) |  |
| 614 | `TasksService::taskEventDispatch` | read [tasks](/code/tables/tasks.md) |  |
| 660 | `TasksService::collaboratorCompletionEnabled` | read [system_settings](/code/tables/system_settings.md) |  |
| 715 | `TasksService::collaboratorsForMany` | read [task_collaborators](/code/tables/task_collaborators.md), read [analysts](/code/tables/analysts.md) |  |
| 757 | `TasksService::addCollaborator` | read [task_collaborators](/code/tables/task_collaborators.md) |  |
| 764 | `TasksService::addCollaborator` | **insert** [task_collaborators](/code/tables/task_collaborators.md) (`task_id`, `analyst_id`, `added_by_id`, `added_datetime`) |  |
| 767 | `TasksService::addCollaborator` | **update** [tasks](/code/tables/tasks.md) (`updated_datetime`) |  |
| 777 | `TasksService::removeCollaborator` | **delete** [task_collaborators](/code/tables/task_collaborators.md) |  |
| 782 | `TasksService::removeCollaborator` | **update** [tasks](/code/tables/tasks.md) (`updated_datetime`) |  |
| 812 | `TasksService::setCollaboratorDone` | **update** [task_collaborators](/code/tables/task_collaborators.md) (`is_completed`, `completed_datetime`) |  |
| 820 | `TasksService::setCollaboratorDone` | read [task_collaborators](/code/tables/task_collaborators.md) |  |
| 826 | `TasksService::setCollaboratorDone` | **update** [tasks](/code/tables/tasks.md) (`updated_datetime`) |  |
| 834 | `TasksService::collaboratorsOutstanding` | read [task_collaborators](/code/tables/task_collaborators.md) |  |
| 906 | `TasksService::collaboratorDispatch` | read [tasks](/code/tables/tasks.md) |  |
| 939 | `TasksService::loadTaskRow` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 1086 | `TasksService::moveTaskToCompany` | **update** [tasks](/code/tables/tasks.md) (`tenant_id`, `updated_datetime`) |  |
| 1093 | `TasksService::moveTaskToCompany` | **insert** [task_audit](/code/tables/task_audit.md) (`task_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `source`, `created_datetime`) |  |
| 1155 | `TasksService::taskAndDescendantIds` | read [tasks](/code/tables/tasks.md) |  |
| 1177 | `TasksService::parentTaskTenant` | read [tasks](/code/tables/tasks.md) |  |
| 1234 | `TasksService::resolveNewTaskTenant` | read [tickets](/code/tables/tickets.md) |  |
| 1349 | `TasksService::validateTeam` | read [teams](/code/tables/teams.md) |  |
| 1377 | `TasksService::validateLink` | read [changes](/code/tables/changes.md) |  |
| 1383 | `TasksService::validateLink` | read [contracts](/code/tables/contracts.md) |  |
| 1386 | `TasksService::validateLink` | read [tasks](/code/tables/tasks.md) |  |
| 1409 | `TasksService::ticketAccessible` | read [tickets](/code/tables/tickets.md) |  |
| 1447 | `TasksService::resolveTags` | read [task_tags](/code/tables/task_tags.md) |  |
| 1450 | `TasksService::resolveTags` | read [task_tags](/code/tables/task_tags.md) |  |
| 1469 | `TasksService::syncTags` | **delete** [task_tag_map](/code/tables/task_tag_map.md) |  |
| 1470 | `TasksService::syncTags` | **insert** [task_tag_map](/code/tables/task_tag_map.md) (`task_id`, `tag_id`) |  |
| 1494 | `TasksService::timeScope` | read [system_settings](/code/tables/system_settings.md) |  |
| 1545 | `TasksService::createTimeEntry` | **insert** [task_time_entries](/code/tables/task_time_entries.md) (`task_id`, `analyst_id`, `notes`, `time_spent_minutes`, `entry_datetime`) |  |
| 1558 | `TasksService::deleteTimeEntry` | read [task_time_entries](/code/tables/task_time_entries.md) |  |
| 1568 | `TasksService::deleteTimeEntry` | **update** [task_time_entries](/code/tables/task_time_entries.md) (`is_active`) |  |
| 1587 | `TasksService::timeEntriesFor` | read [task_time_entries](/code/tables/task_time_entries.md), read [analysts](/code/tables/analysts.md) |  |
| 1603 | `TasksService::timeEntriesFor` | read [task_time_entries](/code/tables/task_time_entries.md), read [tasks](/code/tables/tasks.md) |  |
| 1657 | `TasksService::assignedDispatch` | read [tasks](/code/tables/tasks.md) |  |
| 1680 | `TasksService::completedDispatch` | read [tasks](/code/tables/tasks.md) |  |
| 1699 | `TasksService::resolveAnalyst` | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/checklists.php](/code/files/includes/services/checklists.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md), [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md)

**Included by:** [api/tasks/collaborators.php](/code/files/api/tasks/collaborators.php.md), [api/tasks/delete.php](/code/files/api/tasks/delete.php.md), [api/tasks/delete_time_entry.php](/code/files/api/tasks/delete_time_entry.php.md), [api/tasks/get.php](/code/files/api/tasks/get.php.md), [api/tasks/get_time_entries.php](/code/files/api/tasks/get_time_entries.php.md), [api/tasks/list.php](/code/files/api/tasks/list.php.md), [api/tasks/move_to_company.php](/code/files/api/tasks/move_to_company.php.md), [api/tasks/save.php](/code/files/api/tasks/save.php.md), [api/tasks/save_comment.php](/code/files/api/tasks/save_comment.php.md), [api/tasks/save_time_entry.php](/code/files/api/tasks/save_time_entry.php.md), [api/tickets/get_ticket_tasks.php](/code/files/api/tickets/get_ticket_tasks.php.md), [api/tickets/save_ticket_task.php](/code/files/api/tickets/save_ticket_task.php.md), [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md), [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [calendarSyncReconcileTask()](/code/functions/calendarSyncReconcileTask.md), [contractVisibilitySqlForScope()](/code/functions/contractVisibilitySqlForScope.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [tasksContractorEmail()](/code/functions/tasksContractorEmail.md), [tasksContractorReady()](/code/functions/tasksContractorReady.md), [tasksContractorValidate()](/code/functions/tasksContractorValidate.md), [tenancyDegradeAllowed()](/code/functions/tenancyDegradeAllowed.md)

