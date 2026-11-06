# servers

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `vm_id` | `VARCHAR(100) NOT NULL` |  |
| `name` | `VARCHAR(255) NULL` |  |
| `power_state` | `VARCHAR(20) NULL` |  |
| `memory_gb` | `DECIMAL(10,2) NULL` |  |
| `num_cpu` | `INT NULL` |  |
| `ip_address` | `VARCHAR(50) NULL` |  |
| `hard_disk_size_gb` | `DECIMAL(10,2) NULL` |  |
| `host` | `VARCHAR(255) NULL` |  |
| `cluster` | `VARCHAR(255) NULL` |  |
| `guest_os` | `VARCHAR(255) NULL` |  |
| `last_synced` | `DATETIME NULL` |  |
| `raw_data` | `LONGTEXT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/get_vcenter.php](/code/files/api/assets/get_vcenter.php.md):213 |  | update | `name`, `power_state`, `memory_gb`, `num_cpu`, `ip_address`, `hard_disk_size_gb`, `host`, `cluster`, `guest_os`, `raw_data` |  |
| [api/assets/get_vcenter.php](/code/files/api/assets/get_vcenter.php.md):222 |  | insert | `vm_id`, `name`, `power_state`, `memory_gb`, `num_cpu`, `ip_address`, `hard_disk_size_gb`, `host`, `cluster`, `guest_os` |  |
| [api/assets/get_vcenter.php](/code/files/api/assets/get_vcenter.php.md):236 |  | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_servers.php](/code/files/api/assets/get_servers.php.md) | L20 |  |
| [api/assets/get_vcenter.php](/code/files/api/assets/get_vcenter.php.md) | L208 |  |

