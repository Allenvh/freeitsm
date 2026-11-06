# api/tickets/reorder_ticket_dashboard_widgets.php

> API endpoint - 43 lines

API Endpoint: Reorder analyst's ticket dashboard widgets POST: { order: [widget_id, widget_id, ...] }

## The endpoint

| | |
|---|---|
| URL | `api/tickets/reorder_ticket_dashboard_widgets.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | **update** [analyst_ticket_dashboard_widgets](/code/tables/analyst_ticket_dashboard_widgets.md) (`sort_order`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

