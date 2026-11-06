# api/calendar/delete_event.php

> API endpoint - 27 lines

API Endpoint: Delete Calendar Event. Thin UI adapter over CalendarService.

## The endpoint

| | |
|---|---|
| URL | `api/calendar/delete_event.php` |
| Protected by | Module access: calendar (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/calendar.php](/code/files/includes/services/calendar.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

