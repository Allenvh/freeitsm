# api/assets/proxmox_sync.php

> API endpoint - 47 lines

API Endpoint: test one Proxmox server, or sync it now. POST {id, action:'test'|'sync'} A sync runs in the request and returns its summary. The scheduled run is cron/proxmox_sync.php, which does the same for servers that are due.

## The endpoint

| | |
|---|---|
| URL | `api/assets/proxmox_sync.php` |
| Protected by | Permission: Cap::ASSETS_PROXMOX (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [proxmoxLoadConnection()](/code/functions/proxmoxLoadConnection.md), [proxmoxSyncConnection()](/code/functions/proxmoxSyncConnection.md), [proxmoxTestConnection()](/code/functions/proxmoxTestConnection.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

