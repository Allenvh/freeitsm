# api/assets/get_servers.php

> API endpoint - 146 lines

API Endpoint: Get servers list from database

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_servers.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [servers](/code/tables/servers.md) |  |
| 34 |  | read [proxmox_vms](/code/tables/proxmox_vms.md), read [proxmox_connections](/code/tables/proxmox_connections.md) |  |
| 62 |  | read [vcloud_vms](/code/tables/vcloud_vms.md), read [vcloud_connections](/code/tables/vcloud_connections.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

