# api/checklists/save_lookup.php

> API endpoint - 32 lines

API Endpoint: create or rename a checklist category or suggested role.

Thin UI adapter over ChecklistsService::saveLookup — the rules (name required,
length cap, uniqueness, and carrying referencing rows across a rename) live in
the service so the settings screen and anything added later cannot disagree.

POST { kind: "category"|"role", id?: int, name: string }

## The endpoint

| | |
|---|---|
| URL | `api/checklists/save_lookup.php` |
| Protected by | Module access: checklists (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/checklists.php](/code/files/includes/services/checklists.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

