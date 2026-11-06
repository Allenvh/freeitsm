# api/calendar/feed.php

> API endpoint - 96 lines

API Endpoint: Calendar subscription feed (iCalendar / .ics)

Read-only iCalendar feed for subscribing in Apple Calendar / Google Calendar /
Outlook. Authenticated by a per-analyst capability token (no session — a phone's
calendar app can't carry a login cookie), passed as ?token=. The token maps to
an analyst via user_preferences (preference_key = 'calendar_feed_token'); the
feed content is the shared team calendar (the same events every analyst sees).

Rotate the token from the calendar sidebar ("Reset link") to revoke an old URL.

## The endpoint

| | |
|---|---|
| URL | `api/calendar/feed.php` |
| Protected by | Signed-in analyst (session check) (via includes/timezone.php) |
| Reads | `GET token` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [feed_deny()](/code/functions/feed_deny.md) | 25 | Sends an HTTP status and a plain-text message, then stops - the feed's only error path, since a calendar app shows nothing richer. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [user_preferences](/code/tables/user_preferences.md) |  |
| 54 |  | read [calendar_events](/code/tables/calendar_events.md), read [calendar_categories](/code/tables/calendar_categories.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ics.php](/code/files/includes/ics.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [icsEvent()](/code/functions/icsEvent.md), [icsHeader()](/code/functions/icsHeader.md), [icsRespond()](/code/functions/icsRespond.md), [naive_now()](/code/functions/naive_now.md)

