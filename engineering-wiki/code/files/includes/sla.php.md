# includes/sla.php

> PHP - 396 lines

SLA Engine — Service Level Agreement computation.

Design: see docs/sla.md. The principle is **compute on read** — no stored
counters on the ticket, no background jobs, no drift. Each call walks the
ticket's status-change audit history, splits the lifetime into "running"
vs. "paused" intervals based on which statuses are flagged `pauses_sla`,
intersects each running interval with the priority's business calendar
(week pattern + holidays + timezone), and sums to get elapsed business
minutes.

Two public functions:
  sla_get_state(`$conn`, `$ticket_id`)   — returns the full SLA state of a ticket
  sla_business_minutes(`$start`, `$end`, `$calendar`)
                                      — pure function, the intersection helper

Both treat all DateTimes as UTC unless explicitly converted via the
calendar's timezone for day-walking. The DB stores everything in UTC.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sla_get_state()](/code/functions/sla_get_state.md) | 38 | Compute the SLA state of a single ticket. Returns: [ 'enabled' => bool, 'reason_disabled' => ?string, 'priority' => ?array, 'calendar' => ?array, 'response' => ?array (target_minutes, elapsed_minutes, remaining_minutes, percent, breached, achieved_at, achieved_minutes), 'resolution' => ?array (same shape as response, or null if no target), ] |
| [sla_compute_response()](/code/functions/sla_compute_response.md) | 168 | Compute response-time SLA state. The clock stops at the first response, where "first response" is defined per the sla_first_response_definition setting. v1 supports 'status_change' (first audit row that moves status away from the default) for all three options — outbound-email detection is deferred to a follow-up (see docs/sla.md). |
| [sla_compute_resolution()](/code/functions/sla_compute_resolution.md) | 211 | Compute resolution-time SLA state. The clock stops at ticket close time (read from tickets.closed_datetime or the first audit row that lands on a status with is_closed = 1). |
| [sla_elapsed_business_minutes()](/code/functions/sla_elapsed_business_minutes.md) | 255 | Walk the status timeline, summing business minutes in intervals where the status was NOT pausing. Bounded between [start, end]. |
| [sla_business_minutes()](/code/functions/sla_business_minutes.md) | 286 | Pure function: total business minutes in the interval [start, end] given the calendar's timezone, weekly working hours, and holiday list. |
| [sla_load_settings()](/code/functions/sla_load_settings.md) | 340 | Load the seven SLA system_settings rows into a plain array. |
| [sla_load_calendar()](/code/functions/sla_load_calendar.md) | 366 | Load a calendar with its hours + holidays nested. Returns null if not found. |
| [sla_format_minutes()](/code/functions/sla_format_minutes.md) | 388 | Format a minute count as a short human-friendly string: "1h 30m", "45m", "2h", "-15m" (negative). |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 | `sla_get_state` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 79 | `sla_get_state` | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 97 | `sla_get_state` | read [sla_calendars](/code/tables/sla_calendars.md) |  |
| 115 | `sla_get_state` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 118 | `sla_get_state` | read [ticket_audit](/code/tables/ticket_audit.md) |  |
| 127 | `sla_get_state` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 351 | `sla_load_settings` | read [system_settings](/code/tables/system_settings.md) |  |
| 367 | `sla_load_calendar` | read [sla_calendars](/code/tables/sla_calendars.md) |  |
| 372 | `sla_load_calendar` | read [sla_calendar_hours](/code/tables/sla_calendar_hours.md) |  |
| 376 | `sla_load_calendar` | read [sla_calendar_holidays](/code/tables/sla_calendar_holidays.md) |  |

## How it hangs together

**Included by:** [api/tickets/get_tickets_sla_batch.php](/code/files/api/tickets/get_tickets_sla_batch.php.md), [api/tickets/get_ticket_sla.php](/code/files/api/tickets/get_ticket_sla.php.md), [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md), [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md), [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md)

