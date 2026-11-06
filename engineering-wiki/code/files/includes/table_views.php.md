# includes/table_views.php

> PHP - 246 lines

Saved table views (discussion #96, dschipfel; library and sharing by Ed).

A view is a saved way of LOOKING at a table: which columns, in what order,
sorted how, filtered to what. It belongs to the shared data-table engine
(assets/js/data-table.js), so every table running that engine gets views for
free — assets, tasks, calendar and change management today.

⚠️ EVERY read goes through `viewVisibleClause()`. One place decides who can see
what, because there are three answers (mine, my team's, everyone's) and a
fourth that must never happen (somebody else's private view). Four endpoints
each writing their own clause is four chances to get that wrong.

🔑 A view is not data. It carries no tenant_id and needs none: its filters are
applied to rows the reader was already allowed to load, so a shared view
cannot show anybody another company's assets. It can name a value that means
nothing to the reader, which shows up as a filter matching nothing — an empty
table, not a leak.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [tableViewTeamIds()](/code/functions/tableViewTeamIds.md) | 26 | The teams this analyst belongs to. Empty array is a valid answer. |
| [tableViewVisibleClause()](/code/functions/tableViewVisibleClause.md) | 42 | The WHERE fragment and arguments that limit views to what this analyst may see: their own, ones shared with a team they are in, and public ones. |
| [tableViewSelect()](/code/functions/tableViewSelect.md) | 58 | One view row plus the names the library shows. |
| [tableViewShape()](/code/functions/tableViewShape.md) | 74 | Coerce the types the browser cares about. |
| [tableViewList()](/code/functions/tableViewList.md) | 94 | Views on one table that this analyst can see, newest-used first. |
| [tableViewLoad()](/code/functions/tableViewLoad.md) | 124 | One view, if this analyst may see it. |
| [tableViewSave()](/code/functions/tableViewSave.md) | 148 | Create or update a view. Returns its id. |
| [tableViewDelete()](/code/functions/tableViewDelete.md) | 221 | Delete a view. Owner only. |
| [tableViewTouch()](/code/functions/tableViewTouch.md) | 240 | Stamp a view as used. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 | `tableViewTeamIds` | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 60 | `tableViewSelect` | read [table_views](/code/tables/table_views.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md) |  |
| 191 | `tableViewSave` | read [table_views](/code/tables/table_views.md) |  |
| 198 | `tableViewSave` | **update** [table_views](/code/tables/table_views.md) (`name`, `description`, `visibility`, `team_id`, `config`, `updated_datetime`) |  |
| 207 | `tableViewSave` | **insert** [table_views](/code/tables/table_views.md) (`table_key`, `name`, `description`, `owner_id`, `visibility`, `team_id`, `config`, `created_datetime`, `updated_datetime`) |  |
| 223 | `tableViewDelete` | **delete** [table_views](/code/tables/table_views.md) |  |
| 243 | `tableViewTouch` | **update** [table_views](/code/tables/table_views.md) (`last_used_datetime`) |  |

## How it hangs together

**Included by:** [api/table-views/delete.php](/code/files/api/table-views/delete.php.md), [api/table-views/list.php](/code/files/api/table-views/list.php.md), [api/table-views/save.php](/code/files/api/table-views/save.php.md), [api/table-views/use.php](/code/files/api/table-views/use.php.md)

