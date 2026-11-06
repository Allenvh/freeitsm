# enrolReconcileTasks()

Tasks, whenever the choice moved at all (#75).

🔑 RECONCILE, DON'T BRANCH. The same sweep handles switching tasks on,
switching them off, and narrowing 'both' to one kind — because
`calendarSyncReconcileTask()` works out what SHOULD be there and makes that
true.

```php
function enrolReconcileTasks(PDO $conn, int $analystId): void
```

Defined in [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md), line 90.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 94 | `enrolReconcileTasks` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |

## Calls

[calendarSyncReconcileTask()](/code/functions/calendarSyncReconcileTask.md)

## Called from

- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md)

