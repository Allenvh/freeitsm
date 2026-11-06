# api/files/principals.php

> API endpoint - 26 lines

GET ?q= - analysts and teams to add in the Permissions window. Active only. Analysts are listed regardless of whether they can open Files: a grant for someone without the module simply does nothing until they are given it.

## The endpoint

| | |
|---|---|
| URL | `api/files/principals.php` |
| Protected by | Module access: files (requireModuleAccessJson) (via includes/files/api_bootstrap.php) |
| Reads | `GET q` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 13 |  | read [teams](/code/tables/teams.md) |  |
| 19 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [filesApiOk()](/code/functions/filesApiOk.md), [filesApiRun()](/code/functions/filesApiRun.md)

