# api/tickets/get_ticket_dashboard.php

> API endpoint - 39 lines

API Endpoint: Get analyst's saved ticket dashboard widgets

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_ticket_dashboard.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 22 |  | read [analyst_ticket_dashboard_widgets](/code/tables/analyst_ticket_dashboard_widgets.md), read [ticket_dashboard_widgets](/code/tables/ticket_dashboard_widgets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

