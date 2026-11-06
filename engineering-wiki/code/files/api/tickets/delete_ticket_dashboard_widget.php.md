# api/tickets/delete_ticket_dashboard_widget.php

> API endpoint - 41 lines

API Endpoint: Soft-delete a ticket dashboard widget POST: { id }

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_ticket_dashboard_widget.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | **delete** [analyst_ticket_dashboard_widgets](/code/tables/analyst_ticket_dashboard_widgets.md) |  |
| 33 |  | **update** [ticket_dashboard_widgets](/code/tables/ticket_dashboard_widgets.md) (`is_active`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

