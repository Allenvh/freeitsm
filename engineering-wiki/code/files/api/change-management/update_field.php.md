# api/change-management/update_field.php

> API endpoint - 47 lines

API Endpoint: Update a single field on a change record (Table view inline edit). Thin UI adapter over ChangesService::updateChange — one whitelisted field maps to one canonical key, producing exactly one audit row. Status is intentionally excluded so the approval/CAB paths can't be bypassed from a cell.

## The endpoint

| | |
|---|---|
| URL | `api/change-management/update_field.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Called from | [assets/js/change-table.js](/code/files/assets/js/change-table.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/changes.php](/code/files/includes/services/changes.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

