# includes/ics.php

> PHP - 164 lines

iCalendar (RFC 5545) output, shared by every feed FreeITSM publishes.

There are two: the shared team calendar (api/calendar/feed.php) and an
analyst's own scheduled work (api/tickets/schedule_feed.php). They must agree
on escaping, line folding and — the fiddly one — how an all-day event is
expressed, so it is stated once here rather than copied.

⚠️ THE ALL-DAY RULE IS THE PART PEOPLE GET WRONG. An all-day VEVENT uses DATE
values, and DTEND is EXCLUSIVE: the day AFTER the last day. A single all-day
event on the 2nd is DTSTART 20260902 / DTEND 20260903. Get it wrong and every
all-day entry is either invisible or a day long in the wrong direction. (Graph
has the same rule with different spelling — see MicrosoftCalendarProvider.)

## Functions

| Function | Line | What it does |
|---|--:|---|
| [icsEscape()](/code/functions/icsEscape.md) | 22 | Escape a text value per RFC 5545 (backslash, newline, comma, semicolon). |
| [icsFold()](/code/functions/icsFold.md) | 41 | Fold a content line to <=75 octets; continuation lines start with a space. |
| [icsHeader()](/code/functions/icsHeader.md) | 62 | The VCALENDAR header lines. `$name` is what the client shows as the calendar's name. |
| [icsEvent()](/code/functions/icsEvent.md) | 100 | One VEVENT. |
| [icsRespond()](/code/functions/icsRespond.md) | 156 | Emit the finished calendar with the right headers. |

## How it hangs together

**Included by:** [api/calendar/feed.php](/code/files/api/calendar/feed.php.md), [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md), [includes/calendar_sync/CalDavCalendarProvider.php](/code/files/includes/calendar_sync/CalDavCalendarProvider.php.md)

