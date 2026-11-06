# includes/services/calendar.php

> PHP - 295 lines

CalendarService — the single home for the calendar module's write rules (events, shared by the UI + REST API; plus the UI-only categories).

Shared by the UI endpoints (api/calendar/*.php) and the REST API
(api/v1/resources/calendar.php). Each caller passes an ActorContext and
canonical input; this layer validates + writes and returns the affected id
(plus a created flag) or throws ServiceError. It never emits HTTP.

⚠️ Datetime semantics — DELIBERATELY naive server-local (unlike the rest of
the app, which stores UTC). The calendar stores exactly what the browser
sends, and the ICS feed reads it in the server timezone. So this service
does NOT stamp/convert UTC — it validates "YYYY-MM-DD HH:MM:SS" naive values
and REJECTS Z/offset designators, keeping the UI and API on one model.

🛡️ Generated events (source = 'asset_warranty' etc.) belong to their sync;
updates/deletes on them are refused (409) and the write is further guarded
with `WHERE source IS NULL`.

Canonical event input keys are the API's: title, description, category_id,
start_at, end_at, all_day, location, contract_id. The UI adapters map their
start_datetime/end_datetime to start_at/end_at.

## Classes

- [CalendarService](/code/classes/CalendarService.md) (class, line 29)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 61 | `CalendarService::saveEvent` | **update** [calendar_events](/code/tables/calendar_events.md) (`title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `location`, `contract_id`, `updated_at`) |  |
| 82 | `CalendarService::saveEvent` | **insert** [calendar_events](/code/tables/calendar_events.md) (`title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `location`, `contract_id`, `created_by`) |  |
| 104 | `CalendarService::deleteEvent` | **delete** [calendar_events](/code/tables/calendar_events.md) |  |
| 134 | `CalendarService::saveCategory` | **update** [calendar_categories](/code/tables/calendar_categories.md) (`name`, `color`, `description`, `is_active`, `updated_at`) |  |
| 141 | `CalendarService::saveCategory` | **insert** [calendar_categories](/code/tables/calendar_categories.md) (`name`, `color`, `description`, `is_active`) |  |
| 158 | `CalendarService::deleteCategory` | read [calendar_events](/code/tables/calendar_events.md) |  |
| 164 | `CalendarService::deleteCategory` | read [calendar_categories](/code/tables/calendar_categories.md) |  |
| 165 | `CalendarService::deleteCategory` | **delete** [calendar_categories](/code/tables/calendar_categories.md) |  |
| 185 | `CalendarService::loadEventRow` | read [calendar_events](/code/tables/calendar_events.md) |  |
| 240 | `CalendarService::collectEventFields` | read [calendar_categories](/code/tables/calendar_categories.md) |  |
| 253 | `CalendarService::collectEventFields` | read [contracts](/code/tables/contracts.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md)

**Included by:** [api/calendar/delete_category.php](/code/files/api/calendar/delete_category.php.md), [api/calendar/delete_event.php](/code/files/api/calendar/delete_event.php.md), [api/calendar/save_category.php](/code/files/api/calendar/save_category.php.md), [api/calendar/save_event.php](/code/files/api/calendar/save_event.php.md), [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md)

**Calls:** [contractVisibilitySqlForScope()](/code/functions/contractVisibilitySqlForScope.md)

