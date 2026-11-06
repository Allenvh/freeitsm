# api/assets/proxmox_connections.php

> API endpoint - 63 lines

API Endpoint: Proxmox VE servers — list, add/edit, delete. GET → every server (no passwords) POST {action:'save', ...} → create (no id) or update (id); a blank password keeps the stored one POST {action:'delete', id} → remove a server and its synced VMs/nodes

## The endpoint

| | |
|---|---|
| URL | `api/assets/proxmox_connections.php` |
| Protected by | Permission: Cap::ASSETS_PROXMOX (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [proxmox_connections](/code/tables/proxmox_connections.md) |  |
| 46 |  | **delete** [proxmox_vms](/code/tables/proxmox_vms.md) |  |
| 47 |  | **delete** [proxmox_nodes](/code/tables/proxmox_nodes.md) |  |
| 48 |  | **delete** [proxmox_connections](/code/tables/proxmox_connections.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [proxmoxSaveConnection()](/code/functions/proxmoxSaveConnection.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

