# api/system/check_demo_core.php

> API endpoint - 47 lines

API Endpoint: Which demo data modules have been imported. Returns a row count per module so the UI can reflect their state.

This used to ask "does the table have any rows at all?" for most modules, so
an installation with real tickets or real assets was told its demo data was
already imported. It now counts only rows marked is_demo = 1 (#1297), which
is the same mark the importer uses to decide what it may delete, and it
covers every module rather than the five that happened to be listed here.

## The endpoint

| | |
|---|---|
| URL | `api/system/check_demo_core.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/demo_data.php](/code/files/includes/demo_data.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [demoHasUntaggedImport()](/code/functions/demoHasUntaggedImport.md), [demoRowCount()](/code/functions/demoRowCount.md)

