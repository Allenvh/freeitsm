# assets/js/schedule.js

> JavaScript - 91 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Ticket Calendar Sync Developer Guide](https://github.com/edmozley/freeitsm/wiki/Ticket-Calendar-Sync-Developer-Guide)

Scheduling maths, shared by every screen that can schedule a ticket.

There are now two: the inbox (its Schedule modal, reached from the ticket or
the right-click menu) and the tickets calendar (clicking an event). They must
agree exactly on what a duration means, what an all-day ticket is stored as,
and how a naive wall-clock value is parsed — so it is stated once here rather
than copied into both and left to drift. A third caller is coming when
scheduled work is pushed to Outlook.

⚠️ SCHEDULING VALUES ARE NAIVE WALL-CLOCK. "2pm" means 2pm to every analyst in
every timezone; they are stored without a zone and must never be round-tripped
through Date.toISOString(), which converts to UTC first and moved a 00:30
ticket to the previous DAY for anyone east of UTC (#1161).

## Functions

| Function | Line | What it does |
|---|--:|---|
| `parseNaive(value)` | 29 | Pull a naive "YYYY-MM-DD HH:MM:SS" apart without letting Date touch it. |
| `formatNaive(d)` | 40 | A naive stamp from local date parts — never via `toISOString()`. |
| `p(n)` | 42 | Pads a number to two digits. |
| `toStoredRange(date, time, minutes, allDay)` | 59 | Turn what the form holds into what the server stores. |
| `durationMinutes(start, end)` | 74 | Recover the duration from a stored start/end pair. |

## Loaded by

- [tickets/calendar.php](/code/files/tickets/calendar.php.md)
- [tickets/index.php](/code/files/tickets/index.php.md)

