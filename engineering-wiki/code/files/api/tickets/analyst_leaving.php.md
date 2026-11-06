# api/tickets/analyst_leaving.php

> API endpoint - 71 lines

API Endpoint: when an analyst leaves (GH #178) - System -> Analysts.

GET  ?id=N
     What Deactivate, Anonymise and Delete would mean for them: their open work
     (by kind), what is set up to route to them, how much history carries their
     name, and whether Delete is possible - plus the active analysts their work
     could go to.

POST { id, action: 'deactivate' | 'anonymise' | 'delete', reassign: bool, reassign_to: id | null }
     Optionally hand their open work on first (reassign_to null = unassigned),
     then do the action. Each item is moved through its module's own service;
     any it could not move come back in `failed` and the action still runs,
     except Delete, which refuses while they hold work.

The rules all live in includes/analyst_lifecycle.php; this only adapts them.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/analyst_leaving.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md)

**Calls:** [analystAnonymise()](/code/functions/analystAnonymise.md), [analystDeactivate()](/code/functions/analystDeactivate.md), [analystDelete()](/code/functions/analystDelete.md), [analystFootprint()](/code/functions/analystFootprint.md), [analystLcAssertActionable()](/code/functions/analystLcAssertActionable.md), [analystReassignLive()](/code/functions/analystReassignLive.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

