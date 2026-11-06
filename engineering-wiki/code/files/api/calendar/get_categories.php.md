# api/calendar/get_categories.php

> API endpoint - 48 lines

API Endpoint: Get Calendar Categories Returns all categories (or only active ones if specified)

## The endpoint

| | |
|---|---|
| URL | `api/calendar/get_categories.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |
| Reads | `GET active_only` |
| Called from | [assets/js/calendar-table.js](/code/files/assets/js/calendar-table.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 23 |  | read [calendar_categories](/code/tables/calendar_categories.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md)

