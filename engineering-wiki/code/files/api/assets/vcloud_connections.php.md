# api/assets/vcloud_connections.php

> API endpoint - 62 lines

API Endpoint: VMware Cloud Director servers — list, add/edit, delete. GET → every server (no passwords) POST {action:'save', ...} → create (no id) or update (id); a blank password keeps the stored one POST {action:'delete', id} → remove a server and its synced VMs and edge gateways

## The endpoint

| | |
|---|---|
| URL | `api/assets/vcloud_connections.php` |
| Protected by | Permission: Cap::ASSETS_VCLOUD (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [vcloud_connections](/code/tables/vcloud_connections.md) |  |
| 46 |  | **delete** [vcloud_vms](/code/tables/vcloud_vms.md) |  |
| 47 |  | **delete** [vcloud_edge_gateways](/code/tables/vcloud_edge_gateways.md) |  |
| 48 |  | **delete** [vcloud_connections](/code/tables/vcloud_connections.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [vcdSaveConnection()](/code/functions/vcdSaveConnection.md)

