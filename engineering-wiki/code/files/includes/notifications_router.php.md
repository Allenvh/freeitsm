# includes/notifications_router.php

> PHP - 498 lines

Turns workflow events into notifications (discussion #55).

Hooked into WorkflowEngine::dispatch(), which is the single funnel every
event in the product already passes through. That is the whole reason this
feature is small: the events exist, 48 call sites already fire them, and this
file only has to answer two questions per event —

  1. who should be told?
  2. what does it say?

⚠️ ADDING A NEW NOTIFICATION TYPE SHOULD NOT MEAN EDITING CALL SITES. If an
event already dispatches, add it to NotificationsService::types() and give it
a case below. If it does not dispatch yet, add the dispatch — workflows get
the new trigger for free, which is the right trade either way.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [notificationsCurrentActor()](/code/functions/notificationsCurrentActor.md) | 33 | Who caused this? Resolved centrally from the session rather than threaded through 48 dispatch payloads. |
| [notificationsHandleEvent()](/code/functions/notificationsHandleEvent.md) | 49 | Entry point from WorkflowEngine::dispatch(). |
| [notificationsAudienceFor()](/code/functions/notificationsAudienceFor.md) | 140 | EVERYBODY who gets told about this event. |
| [notificationsRecipientFor()](/code/functions/notificationsRecipientFor.md) | 220 | The ONE person an event is about, for events that are about one person. |
| [notificationsEntityFor()](/code/functions/notificationsEntityFor.md) | 267 | Display data for the row: id, human reference, and what it is about. |
| [notificationsBodyFor()](/code/functions/notificationsBodyFor.md) | 395 | The one-line description. |
| [notificationsTaskAssignedEmail()](/code/functions/notificationsTaskAssignedEmail.md) | 421 | "A task has been assigned to you", by email - for an analyst who has turned on Preferences -> Notifications -> "Email me when a task is assigned to me" (user_preferences.task_assigned_email = 'on'; off unless they ask). |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 165 | `notificationsAudienceFor` | read [task_collaborators](/code/tables/task_collaborators.md) |  |
| 197 | `notificationsAudienceFor` | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 372 | `notificationsEntityFor` | read [tickets](/code/tables/tickets.md) |  |
| 430 | `notificationsTaskAssignedEmail` | read [user_preferences](/code/tables/user_preferences.md) |  |
| 438 | `notificationsTaskAssignedEmail` | read [analysts](/code/tables/analysts.md) |  |
| 446 | `notificationsTaskAssignedEmail` | read [tasks](/code/tables/tasks.md), read [task_priorities](/code/tables/task_priorities.md) |  |

## How it hangs together

**Includes:** [includes/services/notifications.php](/code/files/includes/services/notifications.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/public_url.php](/code/files/includes/public_url.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md)

**Included by:** [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [publicAbsoluteUrl()](/code/functions/publicAbsoluteUrl.md), [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md)

