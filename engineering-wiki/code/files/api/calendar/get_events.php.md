# api/calendar/get_events.php

> API endpoint - 86 lines

API Endpoint: Get Calendar Events Returns events within a date range, optionally filtered by category

## The endpoint

| | |
|---|---|
| URL | `api/calendar/get_events.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |
| Reads | `GET start`, `GET end`, `GET categories`, `GET contract_id`, `GET all` |
| Called from | [assets/js/calendar-table.js](/code/files/assets/js/calendar-table.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | read [calendar_events](/code/tables/calendar_events.md), read [calendar_categories](/code/tables/calendar_categories.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md)

