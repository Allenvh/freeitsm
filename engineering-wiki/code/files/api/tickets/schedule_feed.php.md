# api/tickets/schedule_feed.php

> API endpoint - 254 lines

API Endpoint: an analyst's own scheduled work as an iCalendar feed (GH #75).

The universal half of calendar integration. A native push (Graph, later
Google) gives a real event in a real mailbox — busy time, instant updates,
nothing exposed. This gives everyone else something: a subscribe URL that
works with Google Calendar, Apple Calendar, Outlook, Thunderbird or anything
else that speaks iCalendar, with no provider code, no credentials and no
internet ingress. "We have not implemented your calendar" must never mean
"you get nothing".

── SECURITY: this is a CAPABILITY URL ───────────────────────────────────────

Authenticated by a 192-bit random token in the query string, because a
calendar client cannot carry a login cookie. Possession of the URL IS the
authentication — the same model Google ("Secret address in iCal format"),
Outlook.com and iCloud all use, with the same caveat.

🔴 THE CONTENT IS MORE SENSITIVE THAN THE SHARED CALENDAR'S. That one carries
maintenance windows; this carries TICKET SUBJECTS, which routinely name
customers and problems. Hence:
  - its own token, separate from calendar_feed_token, so revoking this cannot
    be dodged by someone still holding the other, and rotating it does not
    make everyone re-add the team calendar to their phone;
  - a per-analyst detail level, so a desk that does not want subjects leaving
    the login can publish times and ticket numbers alone;
  - an install-wide off switch, because that is a policy the organisation
    makes, not each analyst.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/schedule_feed.php` |
| Protected by | Signed-in analyst (session check) (via includes/timezone.php) |
| Reads | `GET token` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [feed_deny()](/code/functions/feed_deny.md) | 46 | Sends an HTTP status and a plain-text message, then stops - the feed's only error path, since a calendar app shows nothing richer. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 |  | read [user_preferences](/code/tables/user_preferences.md) |  |
| 83 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 122 |  | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [task_priorities](/code/tables/task_priorities.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ics.php](/code/files/includes/ics.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md)

**Calls:** [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [icsEvent()](/code/functions/icsEvent.md), [icsHeader()](/code/functions/icsHeader.md), [icsRespond()](/code/functions/icsRespond.md), [naive_now()](/code/functions/naive_now.md), [requestIsHttps()](/code/functions/requestIsHttps.md), [scheduleFeedAllowed()](/code/functions/scheduleFeedAllowed.md), [scheduleFeedDetail()](/code/functions/scheduleFeedDetail.md), [taskCalendarWantsDue()](/code/functions/taskCalendarWantsDue.md), [taskCalendarWantsWork()](/code/functions/taskCalendarWantsWork.md), [ticketSensitivityNormalise()](/code/functions/ticketSensitivityNormalise.md), [ticketSensitivitySelectSql()](/code/functions/ticketSensitivitySelectSql.md)

