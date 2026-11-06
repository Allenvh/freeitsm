# api/system/get_directory_sync_log.php

> API endpoint - 81 lines

API: the directory sync log.

GET ?provider_id=N            recent runs for a provider
GET ?run_id=N[&action=create] what one run did, person by person

"47 updated" is a number. This is the answer to "updated how, and who?" —
the only version anybody can act on, and the reason the entries table exists
alongside the counts.

## The endpoint

| | |
|---|---|
| URL | `api/system/get_directory_sync_log.php` |
| Protected by | Signed-in analyst (session check) |
| Reads | `GET run_id`, `GET action`, `GET provider_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [directory_sync_entries](/code/tables/directory_sync_entries.md) |  |
| 54 |  | read [directory_sync_entries](/code/tables/directory_sync_entries.md) |  |
| 67 |  | read [directory_sync_runs](/code/tables/directory_sync_runs.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

