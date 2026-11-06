# api/calendar/save_event.php

> API endpoint - 46 lines

API Endpoint: Save Calendar Event. Thin UI adapter over CalendarService — creates or updates an event.

## The endpoint

| | |
|---|---|
| URL | `api/calendar/save_event.php` |
| Protected by | Module access: calendar (requireModuleAccessJson) |
| Called from | [assets/js/calendar-table.js](/code/files/assets/js/calendar-table.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/calendar.php](/code/files/includes/services/calendar.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

