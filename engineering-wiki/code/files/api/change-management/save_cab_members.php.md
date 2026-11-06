# api/change-management/save_cab_members.php

> API endpoint - 36 lines

API Endpoint: Sync CAB members for a change. Thin UI adapter over ChangesService::saveCab (diff-syncs the roster: adds, removes, and required/optional changes, each with an audit row).

## The endpoint

| | |
|---|---|
| URL | `api/change-management/save_cab_members.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/changes.php](/code/files/includes/services/changes.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

