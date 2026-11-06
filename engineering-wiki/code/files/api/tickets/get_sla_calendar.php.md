# api/tickets/get_sla_calendar.php

> API endpoint - 49 lines

API: Load a single calendar with its hours + holidays (for the edit modal).

GET ?id=N

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_sla_calendar.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 24 |  | read [sla_calendars](/code/tables/sla_calendars.md) |  |
| 30 |  | read [sla_calendar_hours](/code/tables/sla_calendar_hours.md) |  |
| 40 |  | read [sla_calendar_holidays](/code/tables/sla_calendar_holidays.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

