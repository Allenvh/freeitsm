# includes/calendar_sync/pull.php

> PHP - 547 lines

Reading changes back OUT of an analyst's calendar (GH #75, bi-directional).

The case this exists for, in Ed's words: you are on the train, you look at
your phone, and the work in your calendar turns out not to be needed. You
delete it there, and by the time you sit down at your desk FreeITSM is
already clean. Moving something you have been asked to do later works the
same way.

── THE THREE GUARDS, AND WHY EACH ONE EXISTS ───────────────────────────────

1. 🔴 NOTHING IS APPLIED ON A BASELINE. A provider that has lost its place —
   an expired delta token, a moved mailbox, a revoked permission — answers
   with everything or with nothing. Code that read "absent" as "deleted"
   would unschedule an entire service desk because a token expired. Absence
   of history is not evidence of deletion.

2. 🔴 A CAP ON DELETIONS PER RUN. More than a handful at once is a symptom,
   not an instruction. We stop, change nothing, and record it — because the
   difference between "somebody cleared their week" and "something has gone
   wrong" cannot be told apart from here, and only one of those is safe to
   act on.

3. 🔴 EVERY CHANGE IS AUDITED. An unschedule that arrived from a phone on a
   train is otherwise a ticket that changed with no record of why. The audit
   row names the calendar it came from.

⚠️ ECHO SUPPRESSION IS BY COMPARISON, NOT BY MARKER. Our own push updates the
event, which comes back in the next delta as a change. Rather than tagging
our writes and hoping the tag survives, we simply compare what the calendar
says to what the ticket already says: equal means nothing to do. That is
immune to a lost marker, and it also means a change we somehow missed gets
picked up rather than suppressed.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [calendarAcceptDeletes()](/code/functions/calendarAcceptDeletes.md) | 50 | Off by default: whether a personal tidy-up may reach shared data is an organisation's call, and the safe answer is the one that changes nothing. |
| [calendarSyncPullForAnalyst()](/code/functions/calendarSyncPullForAnalyst.md) | 66 | Poll one analyst's calendar and apply what came back. |
| [calendarPullApply()](/code/functions/calendarPullApply.md) | 217 | Apply a moved event to its ticket. |
| [calendarPullUnschedule()](/code/functions/calendarPullUnschedule.md) | 236 | Clear a ticket's schedule because its event was deleted. |
| [calendarPullAudit()](/code/functions/calendarPullAudit.md) | 257 | A ticket_audit row, best effort. |
| [calendarPullApplyTaskWork()](/code/functions/calendarPullApplyTaskWork.md) | 284 | A task's work window, moved in the calendar. |
| [calendarPullApplyTaskDue()](/code/functions/calendarPullApplyTaskDue.md) | 308 | A task's DUE DATE, moved in the calendar. |
| [calendarPullUnscheduleTask()](/code/functions/calendarPullUnscheduleTask.md) | 323 | The event was deleted from the calendar: take the matching field away. |
| [calendarPullTaskAudit()](/code/functions/calendarPullTaskAudit.md) | 349 | One line of task history, marked as having come from a calendar. |
| [calendarSyncPullAll()](/code/functions/calendarSyncPullAll.md) | 364 | Every enrolled analyst. Returns one report per person. |
| [calendarNotifyUrl()](/code/functions/calendarNotifyUrl.md) | 394 | The configured notification endpoint, or '' when notifications are off. |
| [calendarSyncEnsureSubscription()](/code/functions/calendarSyncEnsureSubscription.md) | 417 | Bring one analyst's subscription into line: create, renew or remove. |
| [calendarStoreSubscription()](/code/functions/calendarStoreSubscription.md) | 503 | Records an analyst's change-notification subscription (id, expiry, secret) and clears any stored error, since subscribing succeeded. |
| [calendarClearSubscription()](/code/functions/calendarClearSubscription.md) | 523 | Forgets an analyst's change-notification subscription. |
| [calendarSyncEnsureAllSubscriptions()](/code/functions/calendarSyncEnsureAllSubscriptions.md) | 536 | Every enrolled analyst's subscription, plus any left behind by someone who has since opted out. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 | `calendarAcceptDeletes` | read [system_settings](/code/tables/system_settings.md) |  |
| 90 | `calendarSyncPullForAnalyst` | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`delta_token`, `delta_synced_datetime`) |  |
| 117 | `calendarSyncPullForAnalyst` | read [calendar_sync_events](/code/tables/calendar_sync_events.md), read [tickets](/code/tables/tickets.md) |  |
| 127 | `calendarSyncPullForAnalyst` | read [calendar_sync_events](/code/tables/calendar_sync_events.md), read [tasks](/code/tables/tasks.md) |  |
| 199 | `calendarSyncPullForAnalyst` | **delete** [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |
| 220 | `calendarPullApply` | **update** [tickets](/code/tables/tickets.md) (`work_start_datetime`, `work_end_datetime`, `work_all_day`) |  |
| 227 | `calendarPullApply` | **update** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`updated_datetime`) |  |
| 238 | `calendarPullUnschedule` | read [tickets](/code/tables/tickets.md) |  |
| 240 | `calendarPullUnschedule` | **update** [tickets](/code/tables/tickets.md) (`work_start_datetime`, `work_end_datetime`, `work_all_day`) |  |
| 261 | `calendarPullAudit` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |
| 287 | `calendarPullApplyTaskWork` | **update** [tasks](/code/tables/tasks.md) (`work_start_datetime`, `work_end_datetime`, `work_all_day`, `updated_datetime`) |  |
| 310 | `calendarPullApplyTaskDue` | **update** [tasks](/code/tables/tasks.md) (`due_date`, `updated_datetime`) |  |
| 327 | `calendarPullUnscheduleTask` | **update** [tasks](/code/tables/tasks.md) (`due_date`, `updated_datetime`) |  |
| 334 | `calendarPullUnscheduleTask` | **update** [tasks](/code/tables/tasks.md) (`work_start_datetime`, `work_end_datetime`, `updated_datetime`) |  |
| 353 | `calendarPullTaskAudit` | **insert** [task_audit](/code/tables/task_audit.md) (`task_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `source`, `created_datetime`) |  |
| 367 | `calendarSyncPullAll` | read [calendar_enrolments](/code/tables/calendar_enrolments.md) |  |
| 397 | `calendarNotifyUrl` | read [system_settings](/code/tables/system_settings.md) |  |
| 512 | `calendarStoreSubscription` | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`subscription_id`, `subscription_expires`, `subscription_secret`, `last_error`) |  |
| 526 | `calendarClearSubscription` | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`subscription_id`, `subscription_expires`, `subscription_secret`) |  |
| 540 | `calendarSyncEnsureAllSubscriptions` | read [calendar_enrolments](/code/tables/calendar_enrolments.md) |  |

## How it hangs together

**Includes:** [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md)

**Included by:** [api/calendar/graph_notify.php](/code/files/api/calendar/graph_notify.php.md), [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md), [cron/calendar_sync_pull.php](/code/files/cron/calendar_sync_pull.php.md)

**Calls:** [calendarSyncConnectionFor()](/code/functions/calendarSyncConnectionFor.md), [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md), [calendarSyncProviderFor()](/code/functions/calendarSyncProviderFor.md), [calendarSyncRecordError()](/code/functions/calendarSyncRecordError.md), [calendarSyncSchemaReady()](/code/functions/calendarSyncSchemaReady.md)

