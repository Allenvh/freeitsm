# api/table-views/delete.php

> API endpoint - 41 lines

API: delete a saved view (discussion #96). POST { id }

Owner only. Deleting a view removes a way of looking at the table and nothing
else — no rows go with it.

If it was somebody's personal default, their preference is left pointing at a
view that no longer exists. That is handled where it is read: a default that
cannot be loaded falls back to the table's own defaults, which is the same
thing that happens the first time anybody opens the table.

## The endpoint

| | |
|---|---|
| URL | `api/table-views/delete.php` |
| Protected by | Signed-in analyst (session check) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/table_views.php](/code/files/includes/table_views.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [tableViewDelete()](/code/functions/tableViewDelete.md)

