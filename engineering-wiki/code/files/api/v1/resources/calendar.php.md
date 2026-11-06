# api/v1/resources/calendar.php

> API endpoint - 249 lines

FreeITSM REST API v1 — calendar resource (the standalone team calendar).

Mirrors the module's internal endpoints:
  - the list uses get_events.php's exact three-branch window-overlap logic
    (start inclusive, end exclusive);
  - create/update mirror save_event.php (title + start mandatory, end
    defaults to start, all_day 0/1) with friendlier validation: category /
    contract ids get a 422 instead of a raw FK error, and contract links
    honour nothing tenant-y because the calendar is install-wide.

⚠️ Datetime semantics — DIFFERENT from the rest of the API, deliberately:
calendar events are stored as NAIVE SERVER-LOCAL datetimes (the UI writes
what the browser sends, no timezone conversion; the ICS feed interprets
them in the server timezone). To stay pixel-identical with the UI, this
resource accepts and returns naive "YYYY-MM-DD HH:MM:SS" values with NO
timezone conversion, and REJECTS values carrying Z/offset designators so a
consumer can't accidentally mix models.

🛡️ Generated events: the asset-warranty sync owns every row with
source = 'asset_warranty' (it wipes and regenerates them wholesale). The
API exposes `source` on reads (the internal endpoints don't!) and refuses
updates/deletes on generated rows with a 409 — tighter than the UI, which
currently lets you edit one only for the change to evaporate on the next
sync. Consumers can never set `source` on create.

Event WRITES are delegated to CalendarService (includes/services/calendar.php)
so the UI and this API share one code path; the read handlers, serializers +
the naive-datetime parser (also used by the list filters) stay here.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/calendar.php` |
| Protected by | REST API key |
| Reads | `GET from`, `GET to`, `GET all`, `GET contract_id`, `GET category_id`, `GET categories`, `GET source`, `GET q` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiParseNaiveDatetime()](/code/functions/apiParseNaiveDatetime.md) | 48 | Parse a NAIVE local datetime ("YYYY-MM-DD HH:MM[:SS]", T separator OK, date-only OK). 422s on timezone designators — this resource is naive-local by design (see header). |
| [apiCalendarEventSelect()](/code/functions/apiCalendarEventSelect.md) | 70 | The SELECT for calendar events, with the category's name and colour and the creator's name. |
| [apiSerializeCalendarEvent()](/code/functions/apiSerializeCalendarEvent.md) | 85 | Shapes a calendar event row for the API - times stay naive server-local, source says whether it was generated (null = typed in), and created_by is null for generated events. |
| [apiLoadCalendarEvent()](/code/functions/apiLoadCalendarEvent.md) | 114 | Loads one calendar event row (apiCalendarEventSelect), or answers 404 and stops. |
| [apiCalendarEventsList()](/code/functions/apiCalendarEventsList.md) | 127 | GET /calendar/events |
| [apiCalendarEventsGet()](/code/functions/apiCalendarEventsGet.md) | 195 | GET /calendar/events/{id} - one calendar event. |
| [apiCalendarEventsCreate()](/code/functions/apiCalendarEventsCreate.md) | 202 | POST /calendar/events |
| [apiCalendarEventsUpdate()](/code/functions/apiCalendarEventsUpdate.md) | 212 | PATCH /calendar/events/{id} |
| [apiCalendarEventsDelete()](/code/functions/apiCalendarEventsDelete.md) | 222 | DELETE /calendar/events/{id} |
| [apiCalendarCategoriesList()](/code/functions/apiCalendarCategoriesList.md) | 232 | GET /calendar-categories (reference) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 71 | `apiCalendarEventSelect` | read [calendar_events](/code/tables/calendar_events.md), read [calendar_categories](/code/tables/calendar_categories.md), read [analysts](/code/tables/analysts.md) |  |
| 180 | `apiCalendarEventsList` | read [calendar_events](/code/tables/calendar_events.md) |  |
| 234 | `apiCalendarCategoriesList` | read [calendar_events](/code/tables/calendar_events.md), read [calendar_categories](/code/tables/calendar_categories.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/calendar.php](/code/files/includes/services/calendar.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiPagination()](/code/functions/apiPagination.md), [apiRespond()](/code/functions/apiRespond.md)

