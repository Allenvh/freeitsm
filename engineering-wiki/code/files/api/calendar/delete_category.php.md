# api/calendar/delete_category.php

> API endpoint - 29 lines

API Endpoint: Delete Calendar Category. Thin UI adapter over CalendarService — only deletes if no events use it.

## The endpoint

| | |
|---|---|
| URL | `api/calendar/delete_category.php` |
| Protected by | Module access: calendar (requireModuleAccessJson); Permission: Cap::CALENDAR_CATEGORIES (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/calendar.php](/code/files/includes/services/calendar.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

