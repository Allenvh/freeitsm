# cron/vcloud_sync.php

> PHP - 37 lines

vCloud Director sync — scheduled entry point (CLI only).

php cron/vcloud_sync.php

Syncs every active vCloud Director server whose interval has passed since its last sync.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 21 |  | read [vcloud_connections](/code/tables/vcloud_connections.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [vcdSyncConnection()](/code/functions/vcdSyncConnection.md)

