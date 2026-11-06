# api/assets/vcloud_vms.php

> API endpoint - 38 lines

API Endpoint: the VMs and edge gateways synced from one vCloud Director server. GET ?connection_id=N

## The endpoint

| | |
|---|---|
| URL | `api/assets/vcloud_vms.php` |
| Protected by | Permission: Cap::ASSETS_VCLOUD (requireCapabilityJson) |
| Reads | `GET connection_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 |  | read [vcloud_vms](/code/tables/vcloud_vms.md) |  |
| 31 |  | read [vcloud_edge_gateways](/code/tables/vcloud_edge_gateways.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

