# api/tickets/get_schedule_feed_url.php

> API endpoint - 104 lines

API Endpoint: get (or reset) this analyst's scheduled-work subscription URL.

GET                -> the URL, minting a token if there is none yet
  POST action=reset  -> rotate the token, revoking every copy of the old URL
  POST action=revoke -> delete the token entirely; the feed 403s from then on

⚠️ A SEPARATE TOKEN FROM THE SHARED CALENDAR'S (calendar_feed_token). The two
feeds carry different things — that one is maintenance windows, this one is
ticket subjects — and they are gated on different module access. Sharing a
token would mean rotating your work feed forced you to re-add the team
calendar to your phone, and that an analyst with Tickets but not Calendar
could mint a token that also unlocks the Calendar feed.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_schedule_feed_url.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `POST action` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 |  | **delete** [user_preferences](/code/tables/user_preferences.md) |  |
| 61 |  | read [user_preferences](/code/tables/user_preferences.md) |  |
| 73 |  | **insert** [user_preferences](/code/tables/user_preferences.md) (`analyst_id`, `preference_key`, `preference_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requestIsHttps()](/code/functions/requestIsHttps.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [scheduleFeedAllowed()](/code/functions/scheduleFeedAllowed.md), [scheduleFeedDetail()](/code/functions/scheduleFeedDetail.md), [scheduleFeedMode()](/code/functions/scheduleFeedMode.md)

