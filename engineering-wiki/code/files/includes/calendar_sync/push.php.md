# includes/calendar_sync/push.php

> PHP - 511 lines

Putting a scheduled ticket into its owner's calendar, and taking it out again (GH discussion #75, step 3).

🔑 RECONCILE, DON'T REACT. Every write path calls the SAME function, which
works out what SHOULD be in whose calendar and makes that true — rather than
each caller knowing whether its particular change means create, update, move
or delete. Scheduling, rescheduling, unscheduling, reassigning, closing,
trashing and opting out are then all one code path, and a combination nobody
thought of (reassigned AND rescheduled in one save) cannot fall through a gap
between two branches.

⚠️ NEVER FATAL. Scheduling a ticket must not fail because Microsoft was
unreachable — the schedule is FreeITSM's data and Outlook is a projection of
it. Failures are recorded against the analyst's enrolment and reported on
their settings; they never reach the analyst pressing Save.

⚠️ CALL AFTER COMMIT, never inside a transaction: this makes a network call,
and holding row locks open across one is how a slow third party becomes a
database problem.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [calendarSyncReconcileTicket()](/code/functions/calendarSyncReconcileTicket.md) | 34 | Make the calendar match the ticket. |
| [calendarSyncReconcileTask()](/code/functions/calendarSyncReconcileTask.md) | 172 | Make the calendar match a TASK (#75). |
| [calendarSyncEventFromTask()](/code/functions/calendarSyncEventFromTask.md) | 311 | One task, as a calendar event of the given kind. Null when the task has nothing to say for that kind. |
| [calendarSyncRemoveRow()](/code/functions/calendarSyncRemoveRow.md) | 385 | Remove one mapped event from the calendar it was written to, and forget it. |
| [calendarSyncRemoveAllForAnalyst()](/code/functions/calendarSyncRemoveAllForAnalyst.md) | 416 | Everything FreeITSM put in one analyst's calendar, removed. |
| [calendarSyncEventFromTicket()](/code/functions/calendarSyncEventFromTicket.md) | 435 | The canonical event shape (see CalendarSyncProvider) for one ticket row. |
| [calendarSyncRecordError()](/code/functions/calendarSyncRecordError.md) | 486 | Stores a sync error (cut to 500 characters) on the analyst's enrolment, creating an 'off' enrolment row when there is none; never throws. |
| [calendarSyncClearError()](/code/functions/calendarSyncClearError.md) | 502 | Clears the analyst's sync error and stamps the time of the last good sync; never throws. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 | `calendarSyncReconcileTicket` | read [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |
| 51 | `calendarSyncReconcileTicket` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [users](/code/tables/users.md) |  |
| 119 | `calendarSyncReconcileTicket` | **update** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`updated_datetime`) |  |
| 127 | `calendarSyncReconcileTicket` | **update** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`remote_event_id`, `remote_calendar`, `updated_datetime`) |  |
| 138 | `calendarSyncReconcileTicket` | **insert** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`ticket_id`, `analyst_id`, `connection_id`, `remote_event_id`, `remote_calendar`) |  |
| 177 | `calendarSyncReconcileTask` | read [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |
| 184 | `calendarSyncReconcileTask` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [task_priorities](/code/tables/task_priorities.md), read [teams](/code/tables/teams.md) |  |
| 267 | `calendarSyncReconcileTask` | **update** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`updated_datetime`) |  |
| 274 | `calendarSyncReconcileTask` | **update** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`remote_event_id`, `remote_calendar`, `updated_datetime`) |  |
| 285 | `calendarSyncReconcileTask` | **insert** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`task_id`, `kind`, `analyst_id`, `connection_id`, `remote_event_id`, `remote_calendar`) |  |
| 409 | `calendarSyncRemoveRow` | **delete** [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |
| 420 | `calendarSyncRemoveAllForAnalyst` | read [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |
| 490 | `calendarSyncRecordError` | **insert** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`analyst_id`, `mode`, `last_error`) |  |
| 506 | `calendarSyncClearError` | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`last_error`, `last_sync_datetime`) |  |

## How it hangs together

**Includes:** [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), [includes/session_security.php](/code/files/includes/session_security.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Included by:** [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md), [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md), [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [calendarSyncConnectionFor()](/code/functions/calendarSyncConnectionFor.md), [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md), [calendarSyncLoadConnection()](/code/functions/calendarSyncLoadConnection.md), [calendarSyncOnlyConnection()](/code/functions/calendarSyncOnlyConnection.md), [calendarSyncProviderFor()](/code/functions/calendarSyncProviderFor.md), [calendarSyncSchemaReady()](/code/functions/calendarSyncSchemaReady.md), [requestScheme()](/code/functions/requestScheme.md), [taskCalendarWantsDue()](/code/functions/taskCalendarWantsDue.md), [taskCalendarWantsWork()](/code/functions/taskCalendarWantsWork.md), [ticketSensitivityNormalise()](/code/functions/ticketSensitivityNormalise.md), [ticketSensitivitySelectSql()](/code/functions/ticketSensitivitySelectSql.md)

