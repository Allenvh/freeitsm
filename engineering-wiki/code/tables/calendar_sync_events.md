# calendar_sync_events

One row per event we have written into somebody's calendar.  ⚠️ ticket_id is NULLABLE — a row belongs to a ticket OR a task, never both. It is declared NULL here so a fresh install is right; an install that predates tasks is relaxed by the probed MODIFY in db_verify.php, the same shape used for users.email.  🔑 `kind` exists because ONE TASK CAN PRODUCE TWO EVENTS: its scheduled work window and its due date. Without it the second write would find the first row and overwrite it, so an analyst who asked for both would get whichever was reconciled last. Tickets only ever have a work window, which is why 'work' is the default and existing rows need no migration.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ticket_id` | `INT NULL` |  |
| `task_id` | `INT NULL` |  |
| `kind` | `VARCHAR(16) NOT NULL DEFAULT 'work'` | work \| due |
| `analyst_id` | `INT NOT NULL` |  |
| `connection_id` | `INT NULL` |  |
| `remote_event_id` | `VARCHAR(500) NOT NULL` |  |
| `remote_calendar` | `VARCHAR(255) NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md):122 | `csAdoptOrphans` | update | `connection_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3921 |  | delete |  |  |
| [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md):199 | `calendarSyncPullForAnalyst` | delete |  |  |
| [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md):227 | `calendarPullApply` | update | `updated_datetime` |  |
| [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md):119 | `calendarSyncReconcileTicket` | update | `updated_datetime` |  |
| [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md):127 | `calendarSyncReconcileTicket` | update | `remote_event_id`, `remote_calendar`, `updated_datetime` |  |
| [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md):138 | `calendarSyncReconcileTicket` | insert | `ticket_id`, `analyst_id`, `connection_id`, `remote_event_id`, `remote_calendar` |  |
| [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md):267 | `calendarSyncReconcileTask` | update | `updated_datetime` |  |
| [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md):274 | `calendarSyncReconcileTask` | update | `remote_event_id`, `remote_calendar`, `updated_datetime` |  |
| [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md):285 | `calendarSyncReconcileTask` | insert | `task_id`, `kind`, `analyst_id`, `connection_id`, `remote_event_id`, `remote_calendar` |  |
| [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md):409 | `calendarSyncRemoveRow` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md) | csUsage L55, csTakeBack L78 |  |
| [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md) | enrolReconcileTasks L94 |  |
| [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) | calendarSyncPullForAnalyst L117, calendarSyncPullForAnalyst L127 |  |
| [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) | calendarSyncReconcileTicket L39, calendarSyncReconcileTask L177, calendarSyncRemoveAllForAnalyst L420 |  |

