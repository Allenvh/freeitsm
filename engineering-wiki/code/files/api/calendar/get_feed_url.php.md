# api/calendar/get_feed_url.php

> API endpoint - 90 lines

API Endpoint: Get (or reset) the current analyst's calendar subscription URL.

GET                -> returns the analyst's feed URL, creating a token if none exists.
  POST action=reset  -> rotates the token (revokes the old URL), returns the new URL.

The token is a per-analyst capability stored in user_preferences
(preference_key = 'calendar_feed_token'). It authorises the read-only .ics feed
served by feed.php (which can't use the session, as a phone has no login cookie).

## The endpoint

| | |
|---|---|
| URL | `api/calendar/get_feed_url.php` |
| Protected by | Module access: calendar (requireModuleAccessJson) |
| Reads | `POST action` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | read [user_preferences](/code/tables/user_preferences.md) |  |
| 45 |  | **insert** [user_preferences](/code/tables/user_preferences.md) (`analyst_id`, `preference_key`, `preference_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requestIsHttps()](/code/functions/requestIsHttps.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

