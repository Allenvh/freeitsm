# api/calendar/get_event.php

> API endpoint - 56 lines

API Endpoint: Get Single Calendar Event

## The endpoint

| | |
|---|---|
| URL | `api/calendar/get_event.php` |
| Protected by | Module access: calendar (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [calendar_events](/code/tables/calendar_events.md), read [calendar_categories](/code/tables/calendar_categories.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

