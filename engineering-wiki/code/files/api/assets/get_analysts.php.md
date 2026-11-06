# api/assets/get_analysts.php

> API endpoint - 54 lines

API Endpoint: the analysts who can be given an asset.

Assets could only be held by a REQUESTER until 2.5.0, and analysts are not
requesters - on a real install most of the desk has no `users` row at all, so
the existing people search could not find them. This is the analyst half of
the same picker.

🔑 NOT tenant-scoped, deliberately. Analysts are install-wide and every other
analyst picker in the product (ticket assignment, knowledge ownership, change
approval) shows the whole desk, so scoping this one would be the odd one out
without hiding anything the caller cannot already see elsewhere.

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_analysts.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET search` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

