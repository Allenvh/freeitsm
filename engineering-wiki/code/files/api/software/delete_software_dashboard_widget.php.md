# api/software/delete_software_dashboard_widget.php

> API endpoint - 40 lines

API Endpoint: Soft-delete a software dashboard widget POST: { id }

## The endpoint

| | |
|---|---|
| URL | `api/software/delete_software_dashboard_widget.php` |
| Protected by | Module access: software (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | **delete** [analyst_software_dashboard_widgets](/code/tables/analyst_software_dashboard_widgets.md) |  |
| 33 |  | **update** [software_dashboard_widgets](/code/tables/software_dashboard_widgets.md) (`is_active`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

