# api/tickets/get_sla_settings.php

> API endpoint - 87 lines

API: Get all data for the SLA settings tab in one round-trip.

Returns:
  settings:    { sla_enforce_from, sla_priority_change_behaviour, ... }
  priorities:  [{ id, name, sla_response_minutes, sla_resolution_minutes, sla_calendar_id }]
  calendars:   [{ id, name, timezone, is_default, hours: [...], holiday_count }]

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_sla_settings.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 44 |  | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 49 |  | read [sla_calendars](/code/tables/sla_calendars.md) |  |
| 56 |  | read [sla_calendar_hours](/code/tables/sla_calendar_hours.md) |  |
| 63 |  | read [sla_calendar_holidays](/code/tables/sla_calendar_holidays.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

