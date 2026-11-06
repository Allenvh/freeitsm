# calendarSyncReconcileTask()

Make the calendar match a TASK (#75).

🔑 Same reconcile-don't-react shape as the ticket above: work out what SHOULD
be in whose calendar and make that true. Scheduling, unscheduling, changing a
due date, reassigning, completing, deleting and opting out are then one path.

⚠️ A TASK CAN PRODUCE TWO EVENTS — the work window and the due date — and
they are reconciled INDEPENDENTLY, keyed by `kind`. An analyst who wants only
due dates must not get the work slot as well, and someone switching from
'both' to 'due' must LOSE the work events rather than keep them orphaned.
That is why the wanted set is computed per kind rather than as one flag.

```php
function calendarSyncReconcileTask(PDO $conn, int $taskId, bool $gone = false): void
```

Defined in [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md), line 172.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$taskId` | `int` |  | The id of the task. *([standard](/handbook/standard-parameters.md))* |
| `$gone` | `bool` | `false` | true when the task is being deleted and its row may already be unreadable. |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 177 | `calendarSyncReconcileTask` | read [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |
| 184 | `calendarSyncReconcileTask` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [task_priorities](/code/tables/task_priorities.md), read [teams](/code/tables/teams.md) |  |
| 267 | `calendarSyncReconcileTask` | **update** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`updated_datetime`) |  |
| 274 | `calendarSyncReconcileTask` | **update** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`remote_event_id`, `remote_calendar`, `updated_datetime`) |  |
| 285 | `calendarSyncReconcileTask` | **insert** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`task_id`, `kind`, `analyst_id`, `connection_id`, `remote_event_id`, `remote_calendar`) |  |

## Calls

[calendarSyncClearError()](/code/functions/calendarSyncClearError.md), [calendarSyncConnectionFor()](/code/functions/calendarSyncConnectionFor.md), [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md), [calendarSyncEventFromTask()](/code/functions/calendarSyncEventFromTask.md), [calendarSyncProviderFor()](/code/functions/calendarSyncProviderFor.md), [calendarSyncRecordError()](/code/functions/calendarSyncRecordError.md), [calendarSyncRemoveRow()](/code/functions/calendarSyncRemoveRow.md), [calendarSyncSchemaReady()](/code/functions/calendarSyncSchemaReady.md), [taskCalendarWantsDue()](/code/functions/taskCalendarWantsDue.md), [taskCalendarWantsWork()](/code/functions/taskCalendarWantsWork.md)

## Called from

- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md) - in [enrolReconcileTasks()](/code/functions/enrolReconcileTasks.md)
- [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) - in `TasksService::calendarOnTaskChanged`

