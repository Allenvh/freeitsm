# api/tickets/remove_ticket_dashboard_widget.php

> API endpoint - 40 lines

API Endpoint: Remove a widget from analyst's ticket dashboard POST: { widget_id: int }

## The endpoint

| | |
|---|---|
| URL | `api/tickets/remove_ticket_dashboard_widget.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | **delete** [analyst_ticket_dashboard_widgets](/code/tables/analyst_ticket_dashboard_widgets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

