# api/tickets/get_ticket_widget_library.php

> API endpoint - 34 lines

API Endpoint: Get all active ticket dashboard widgets from the library

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_ticket_widget_library.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [ticket_dashboard_widgets](/code/tables/ticket_dashboard_widgets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

