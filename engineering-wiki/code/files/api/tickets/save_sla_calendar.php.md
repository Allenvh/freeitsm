# api/tickets/save_sla_calendar.php

> API endpoint - 123 lines

API: Create or update a business calendar including its weekly hours and holiday list, all in one transaction.

POST JSON: {
  id?:           int (omit to create)
  name:          string (required)
  timezone:      string (IANA zone, required, must validate)
  is_default:    bool (only one calendar can be default; setting this clears others)
  hours:         [{ weekday: 1-7, start_time: 'HH:MM', end_time: 'HH:MM' }]   — absence of a weekday = closed
  holidays:      [{ holiday_date: 'YYYY-MM-DD', name?: string }]
}

Hours and holidays are replaced wholesale (delete-then-insert in a transaction)
so the caller sends the complete desired state, not deltas.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_sla_calendar.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_SLA (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 86 |  | **update** [sla_calendars](/code/tables/sla_calendars.md) (`is_default`) |  |
| 90 |  | **update** [sla_calendars](/code/tables/sla_calendars.md) (`name`, `timezone`, `is_default`) |  |
| 93 |  | **insert** [sla_calendars](/code/tables/sla_calendars.md) (`name`, `timezone`, `is_default`) |  |
| 99 |  | **delete** [sla_calendar_hours](/code/tables/sla_calendar_hours.md) |  |
| 101 |  | **insert** [sla_calendar_hours](/code/tables/sla_calendar_hours.md) (`calendar_id`, `weekday`, `start_time`, `end_time`) |  |
| 108 |  | **delete** [sla_calendar_holidays](/code/tables/sla_calendar_holidays.md) |  |
| 110 |  | **insert** [sla_calendar_holidays](/code/tables/sla_calendar_holidays.md) (`calendar_id`, `holiday_date`, `name`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

