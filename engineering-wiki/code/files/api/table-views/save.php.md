# api/table-views/save.php

> API endpoint - 44 lines

API: create or update a saved view (discussion #96).

POST { table_key, name, description?, visibility, team_id?, config }  create
POST { id, ...same }                                                  update

⚠️ Only the owner may update. A view shared with a team is readable by that
team and writable by nobody else — see includes/table_views.php.

## The endpoint

| | |
|---|---|
| URL | `api/table-views/save.php` |
| Protected by | Module access: ? (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/table_views.php](/code/files/includes/table_views.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [tableViewSave()](/code/functions/tableViewSave.md)

