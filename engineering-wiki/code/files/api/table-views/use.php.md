# api/table-views/use.php

> API endpoint - 72 lines

API: record that a view was opened, and optionally make it the default.

POST { id }                       stamp last used
POST { table_key, default_id }    set (or clear, with null) the personal default

Two small writes rather than two endpoints, because the browser does them
together: applying a view stamps it, and ticking "use this by default" is the
same gesture one step later.

⚠️ The default is stored against the READER in user_preferences, not on the
view. Two people can have different defaults pointing at the same shared view,
and one person changing theirs must not change anybody else's.

## The endpoint

| | |
|---|---|
| URL | `api/table-views/use.php` |
| Protected by | Signed-in analyst (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 51 |  | **delete** [user_preferences](/code/tables/user_preferences.md) |  |
| 60 |  | **insert** [user_preferences](/code/tables/user_preferences.md) (`analyst_id`, `preference_key`, `preference_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/table_views.php](/code/files/includes/table_views.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [tableViewLoad()](/code/functions/tableViewLoad.md), [tableViewTouch()](/code/functions/tableViewTouch.md)

