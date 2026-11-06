# api/checklists/delete_lookup.php

> API endpoint - 34 lines

API Endpoint: delete a checklist category or suggested role.

Thin UI adapter over ChecklistsService::deleteLookup.

POST { kind: "category"|"role", id: int }

## The endpoint

| | |
|---|---|
| URL | `api/checklists/delete_lookup.php` |
| Protected by | Module access: checklists (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/checklists.php](/code/files/includes/services/checklists.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

