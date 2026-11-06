# api/table-views/list.php

> API endpoint - 74 lines

API: saved views for one table (discussion #96). GET ?table_key=assets&q=

Returns the views this analyst can see — their own, ones shared with a team
they belong to, and public ones. Also returns the teams they could share
into, so the editor can offer them without a second request, and the id of
their default view for this table.

⚠️ NOT gated on one module. Four different modules run this table, so the
gate is the table_key itself: you are asking for views of a table you are
already looking at, and every view is filtered by who may see it. Gating on
'assets' would refuse somebody using the tasks table.

## The endpoint

| | |
|---|---|
| URL | `api/table-views/list.php` |
| Protected by | Module access: ? (requireModuleAccessJson) |
| Reads | `GET table_key`, `GET q` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 47 |  | read [teams](/code/tables/teams.md) |  |
| 58 |  | read [user_preferences](/code/tables/user_preferences.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/table_views.php](/code/files/includes/table_views.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [tableViewList()](/code/functions/tableViewList.md), [tableViewTeamIds()](/code/functions/tableViewTeamIds.md)

