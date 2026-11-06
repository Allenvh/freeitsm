# api/tickets/calendar_enrolment.php

> API endpoint - 500 lines

API: an analyst's OWN calendar choice (GH #75, CalDAV #133).

GET            -> my mode, my connection, where my work would go, and what is on offer
  POST mode=off|push|feed [connection_id]

CalDAV only - the analyst signs in as themselves, so they also choose where:
  POST action=caldav_discover  connection_id, username, password -> the calendars that sign-in can write to
  POST action=caldav_save      connection_id, username, password, calendar_url[, turn_on=1]
  POST action=caldav_forget                            -> take our events back, forget the sign-in

🔑 ANY NUMBER OF CONNECTIONS. An install can have Microsoft 365 and one or
more CalDAV servers side by side, and each analyst picks the one their work
goes to. connection_id may be left out while there is only one, or to mean
"the one I already use".

🔴 WITH MICROSOFT THIS ENDPOINT CANNOT SET AN ADDRESS. Only mode. The
application permission behind that push can write to any mailbox in the
tenant, so letting an analyst name their own target would let them fill a
colleague's calendar with their tickets. Where it goes is an administrator's
decision (System → Calendar sync); whether it happens at all is the analyst's.

🔑 WITH CALDAV IT CAN, AND MUST. Each calendar belongs to its owner's account
and FreeITSM signs in with the analyst's own password, so the only calendars
it can reach are ones they could already write to - and nobody else has the
password to choose for them. The calendar must still be on the server the
administrator configured (CalDavCalendarProvider::calendarUrl).

🔑 ONE MODE, NOT TWO SWITCHES. With a push and a subscribed feed both live you
see every scheduled ticket twice — once as a real event, once from the
subscription. A single value makes that unrepresentable.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/calendar_enrolment.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `POST connection_id`, `POST action`, `POST username`, `POST password`, `POST calendar_url`, `POST turn_on`, `POST mode`, `POST task_mode` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [enrolBackfillTickets()](/code/functions/enrolBackfillTickets.md) | 55 | Put what is already scheduled into a calendar that has just been switched on (or just changed), rather than only tickets touched from now on — an empty calendar after opting in reads as "it didn't work". |
| [enrolReconcileTasks()](/code/functions/enrolReconcileTasks.md) | 90 | Tasks, whenever the choice moved at all (#75). |
| [enrolSwitchConnection()](/code/functions/enrolSwitchConnection.md) | 129 | Move an analyst onto another connection. Returns true when anything moved. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 59 | `enrolBackfillTickets` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 94 | `enrolReconcileTasks` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |
| 135 | `enrolSwitchConnection` | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`connection_id`) |  |
| 155 | `enrolSwitchConnection` | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`connection_id`, `mode`, `calendar_address`, `credentials`, `delta_token`, `delta_synced_datetime`, `subscription_id`, `subscription_expires`, `subscription_secret`, `last_error`, `updated_datetime`) |  |
| 244 |  | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`mode`, `calendar_address`, `credentials`, `delta_token`, `delta_synced_datetime`, `last_error`, `updated_datetime`) |  |
| 334 |  | **insert** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`analyst_id`, `mode`, `connection_id`, `calendar_address`, `credentials`) |  |
| 401 |  | read [analysts](/code/tables/analysts.md) |  |
| 463 |  | **insert** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`analyst_id`, `mode`, `task_mode`, `connection_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md)

**Calls:** [calendarModeIsValid()](/code/functions/calendarModeIsValid.md), [calendarSyncAccount()](/code/functions/calendarSyncAccount.md), [calendarSyncConnectionFor()](/code/functions/calendarSyncConnectionFor.md), [calendarSyncConnectionIds()](/code/functions/calendarSyncConnectionIds.md), [calendarSyncDecodeCredentials()](/code/functions/calendarSyncDecodeCredentials.md), [calendarSyncEncodeCredentials()](/code/functions/calendarSyncEncodeCredentials.md), [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md), [calendarSyncLoadConnection()](/code/functions/calendarSyncLoadConnection.md), [calendarSyncProviderFor()](/code/functions/calendarSyncProviderFor.md), [calendarSyncReconcileTask()](/code/functions/calendarSyncReconcileTask.md), [calendarSyncReconcileTicket()](/code/functions/calendarSyncReconcileTicket.md), [calendarSyncRemoveAllForAnalyst()](/code/functions/calendarSyncRemoveAllForAnalyst.md), [calendarSyncSchemaReady()](/code/functions/calendarSyncSchemaReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [naive_now()](/code/functions/naive_now.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [scheduleFeedAllowed()](/code/functions/scheduleFeedAllowed.md), [taskCalendarModeIsValid()](/code/functions/taskCalendarModeIsValid.md)

