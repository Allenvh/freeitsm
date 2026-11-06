# api/system/run_directory_sync.php

> API endpoint - 94 lines

API: run a directory sync, or preview one.

POST { provider_id: N, mode: 'preview' | 'live' }

A preview runs the identical code path and writes nothing to `users`, so what
it reports is what a live run would actually do — not a separate estimate that
can drift from the real thing.

Administrators only. A sync creates and deactivates people wholesale; that is
not a thing an ordinary analyst should be able to set off.

## The endpoint

| | |
|---|---|
| URL | `api/system/run_directory_sync.php` |
| Protected by | Signed-in analyst (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 43 |  | read [auth_providers](/code/tables/auth_providers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [cardDavSyncRun()](/code/functions/cardDavSyncRun.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [directorySyncRun()](/code/functions/directorySyncRun.md)

