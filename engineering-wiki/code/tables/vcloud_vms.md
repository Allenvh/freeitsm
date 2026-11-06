# vcloud_vms

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `connection_id` | `INT NOT NULL` |  |
| `vm_uuid` | `VARCHAR(64) NOT NULL` |  |
| `name` | `VARCHAR(255) NULL` |  |
| `org_vdc` | `VARCHAR(255) NULL` |  |
| `vapp_name` | `VARCHAR(255) NULL` |  |
| `status` | `VARCHAR(20) NULL` |  |
| `vcpus` | `INT NULL` |  |
| `memory_mb` | `INT NULL` |  |
| `disk_gb` | `DECIMAL(10,2) NULL` |  |
| `ip_addresses` | `TEXT NULL` |  |
| `mac_addresses` | `TEXT NULL` |  |
| `details_json` | `LONGTEXT NULL` |  |
| `last_seen_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/vcloud_connections.php](/code/files/api/assets/vcloud_connections.php.md):46 |  | delete |  |  |
| [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md):250 | `vcdSyncConnection` | insert | `connection_id`, `vm_uuid`, `name`, `org_vdc`, `vapp_name`, `status`, `vcpus`, `memory_mb`, `disk_gb`, `ip_addresses` |  |
| [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md):277 | `vcdSyncConnection` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_servers.php](/code/files/api/assets/get_servers.php.md) | L62 |  |
| [api/assets/vcloud_vms.php](/code/files/api/assets/vcloud_vms.php.md) | L26 |  |
| [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md) | vcdSyncConnection L265 |  |

