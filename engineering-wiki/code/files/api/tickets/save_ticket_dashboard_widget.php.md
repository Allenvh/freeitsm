# api/tickets/save_ticket_dashboard_widget.php

> API endpoint - 129 lines

API Endpoint: Create or update a ticket dashboard widget definition POST: { id?, title, description, chart_type, aggregate_property, series_property, is_status_filterable, date_range, department_filter, time_grouping }

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_ticket_dashboard_widget.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 97 |  | **update** [ticket_dashboard_widgets](/code/tables/ticket_dashboard_widgets.md) (`title`, `description`, `chart_type`, `aggregate_property`, `series_property`, `is_status_filterable`, `date_range`, `department_filter`, `time_grouping`) |  |
| 106 |  | read [ticket_dashboard_widgets](/code/tables/ticket_dashboard_widgets.md) |  |
| 109 |  | **insert** [ticket_dashboard_widgets](/code/tables/ticket_dashboard_widgets.md) (`title`, `description`, `chart_type`, `aggregate_property`, `series_property`, `is_status_filterable`, `date_range`, `department_filter`, `time_grouping`, `display_order`) |  |
| 119 |  | read [ticket_dashboard_widgets](/code/tables/ticket_dashboard_widgets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

