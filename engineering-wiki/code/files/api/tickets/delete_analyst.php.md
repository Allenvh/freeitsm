# api/tickets/delete_analyst.php

> API endpoint - 40 lines

API Endpoint: Delete an analyst.

Kept for anything that still posts { id } here; the screen now uses
analyst_leaving.php, which can also move their work and offer Deactivate or
Anonymise. Both go through `analystDelete()` (includes/analyst_lifecycle.php),
which removes their personal settings with them and refuses - in words, not a
foreign-key error (GH #178) - when their name is on history or they still
hold open work.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_analyst.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md)

**Calls:** [analystDelete()](/code/functions/analystDelete.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

