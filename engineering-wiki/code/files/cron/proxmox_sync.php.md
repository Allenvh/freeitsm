# cron/proxmox_sync.php

> PHP - 41 lines

Proxmox VE sync — scheduled entry point (CLI only).

php cron/proxmox_sync.php

Syncs every active Proxmox server whose interval has passed since its last
sync. Run it every few minutes (for example from the host's cron, or a
container's loop); each server then runs on its own interval. A server that
fails does not stop the others.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 24 |  | read [proxmox_connections](/code/tables/proxmox_connections.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [proxmoxSyncConnection()](/code/functions/proxmoxSyncConnection.md)

