# api/assets/proxmox_vms.php

> API endpoint - 37 lines

API Endpoint: the VMs, containers and nodes synced from one Proxmox server. GET ?connection_id=N

## The endpoint

| | |
|---|---|
| URL | `api/assets/proxmox_vms.php` |
| Protected by | Permission: Cap::ASSETS_PROXMOX (requireCapabilityJson) |
| Reads | `GET connection_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [proxmox_nodes](/code/tables/proxmox_nodes.md) |  |
| 28 |  | read [proxmox_vms](/code/tables/proxmox_vms.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

