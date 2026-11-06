# api/tickets/add_ticket_dashboard_widget.php

> API endpoint - 49 lines

API Endpoint: Add a widget to analyst's ticket dashboard POST: { widget_id: int }

## The endpoint

| | |
|---|---|
| URL | `api/tickets/add_ticket_dashboard_widget.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [analyst_ticket_dashboard_widgets](/code/tables/analyst_ticket_dashboard_widgets.md) |  |
| 35 |  | **insert** [analyst_ticket_dashboard_widgets](/code/tables/analyst_ticket_dashboard_widgets.md) (`analyst_id`, `widget_id`, `sort_order`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

