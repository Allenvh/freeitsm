# api/morning-checks/get_statuses.php

> API endpoint - 50 lines

API: Get all configured morning-check statuses.

Returns the full list (active and inactive) ordered by SortOrder so
the settings page can show everything, and the dashboard can resolve
a historical result's label/colour even if the status has since been
deactivated. Dashboard then filters to IsActive=1 for the buttons.

## The endpoint

| | |
|---|---|
| URL | `api/morning-checks/get_statuses.php` |
| Protected by | Module access: morning-checks (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

