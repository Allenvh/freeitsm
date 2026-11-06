# proxmox_nodes

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `connection_id` | `INT NOT NULL` |  |
| `node_name` | `VARCHAR(100) NOT NULL` |  |
| `cluster_name` | `VARCHAR(100) NULL` |  |
| `status` | `VARCHAR(20) NULL` |  |
| `cpu_cores` | `INT NULL` |  |
| `memory_mb` | `INT NULL` |  |
| `last_seen_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/proxmox_connections.php](/code/files/api/assets/proxmox_connections.php.md):47 |  | delete |  |  |
| [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md):197 | `proxmoxSyncConnection` | insert | `connection_id`, `node_name`, `cluster_name`, `status`, `cpu_cores`, `memory_mb`, `last_seen_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/proxmox_vms.php](/code/files/api/assets/proxmox_vms.php.md) | L25 |  |

