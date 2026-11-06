# vcloud_edge_gateways

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `connection_id` | `INT NOT NULL` |  |
| `gateway_id` | `VARCHAR(64) NOT NULL` |  |
| `name` | `VARCHAR(255) NULL` |  |
| `org_vdc` | `VARCHAR(255) NULL` |  |
| `status` | `VARCHAR(20) NULL` |  |
| `uplink_ips` | `TEXT NULL` |  |
| `details_json` | `LONGTEXT NULL` |  |
| `last_seen_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/vcloud_connections.php](/code/files/api/assets/vcloud_connections.php.md):47 |  | delete |  |  |
| [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md):308 | `vcdSyncConnection` | insert | `connection_id`, `gateway_id`, `name`, `org_vdc`, `status`, `uplink_ips`, `details_json`, `last_seen_datetime` |  |
| [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md):332 | `vcdSyncConnection` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/vcloud_vms.php](/code/files/api/assets/vcloud_vms.php.md) | L31 |  |

