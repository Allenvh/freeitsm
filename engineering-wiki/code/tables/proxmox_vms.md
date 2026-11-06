# proxmox_vms

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `connection_id` | `INT NOT NULL` |  |
| `vmid` | `INT NOT NULL` |  |
| `vm_type` | `VARCHAR(10) NOT NULL` |  |
| `node_name` | `VARCHAR(100) NULL` |  |
| `cluster_name` | `VARCHAR(100) NULL` |  |
| `name` | `VARCHAR(255) NULL` |  |
| `status` | `VARCHAR(20) NULL` |  |
| `vcpus` | `INT NULL` |  |
| `memory_mb` | `INT NULL` |  |
| `disk_gb` | `DECIMAL(10,2) NULL` |  |
| `os_type` | `VARCHAR(50) NULL` |  |
| `ip_addresses` | `TEXT NULL` |  |
| `mac_addresses` | `TEXT NULL` |  |
| `details_json` | `LONGTEXT NULL` |  |
| `last_seen_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/proxmox_connections.php](/code/files/api/assets/proxmox_connections.php.md):46 |  | delete |  |  |
| [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md):229 | `proxmoxSyncConnection` | insert | `connection_id`, `vmid`, `vm_type`, `node_name`, `cluster_name`, `name`, `status`, `vcpus`, `memory_mb`, `disk_gb` |  |
| [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md):273 | `proxmoxSyncConnection` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_servers.php](/code/files/api/assets/get_servers.php.md) | L34 |  |
| [api/assets/proxmox_vms.php](/code/files/api/assets/proxmox_vms.php.md) | L28 |  |
| [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md) | proxmoxSyncConnection L255 |  |

