# analystCanAccessTask()

May this analyst see/act on this task? (GH #83 groundwork.)

Same shape as the asset/problem/change twins: single-company → true, unknown
id → false, NULL tenant → the Default company. A task is SCOPED DATA, so NULL
means "the Default company's", never "shared with everyone".

⚠️ Guard the CHILD writes with this too, not just get/save — a task's comments
and subtasks are addressed by task id, and were reachable across companies
until each got the gate. That is exactly how the asset disks/software/history
endpoints were missed the first time round.

```php
function analystCanAccessTask(PDO $conn, int $analystId, $taskId): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 587.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$taskId` |  |  | the task id, any form; cast to int, and 0 or less is false |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 596 | `analystCanAccessTask` | read [tasks](/code/tables/tasks.md) |  |

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [tenancyDegradeAllowed()](/code/functions/tenancyDegradeAllowed.md)

## Called from

- [api/tasks/checklists.php](/code/files/api/tasks/checklists.php.md) - in [checklistTaskGate()](/code/functions/checklistTaskGate.md)
- [api/tasks/get.php](/code/files/api/tasks/get.php.md)
- [api/tasks/reorder.php](/code/files/api/tasks/reorder.php.md)
- [api/tasks/reorder_subtasks.php](/code/files/api/tasks/reorder_subtasks.php.md)
- [api/tasks/toggle_subtask.php](/code/files/api/tasks/toggle_subtask.php.md)
- [api/tickets/delete_ticket_task.php](/code/files/api/tickets/delete_ticket_task.php.md)
- [api/tickets/save_ticket_task.php](/code/files/api/tickets/save_ticket_task.php.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)
- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreviewTask()](/code/functions/recordPreviewTask.md)

