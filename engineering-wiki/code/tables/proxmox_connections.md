# proxmox_connections

Proxmox VE servers (several per install). Passwords are encrypted at rest.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `host` | `VARCHAR(255) NOT NULL` |  |
| `username` | `VARCHAR(100) NOT NULL` |  |
| `password_enc` | `LONGTEXT NULL` |  |
| `verify_ssl` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `sync_interval_minutes` | `INT NOT NULL DEFAULT 60` |  |
| `last_sync_datetime` | `DATETIME NULL` |  |
| `last_sync_status` | `VARCHAR(20) NULL` |  |
| `last_sync_message` | `TEXT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/proxmox_connections.php](/code/files/api/assets/proxmox_connections.php.md):48 |  | delete |  |  |
| [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md):293 | `proxmoxSyncConnection` | update | `last_sync_datetime`, `last_sync_status`, `last_sync_message` |  |
| [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md):519 | `proxmoxSaveConnection` | update | `name`, `host`, `username`, `password_enc`, `verify_ssl`, `is_active`, `sync_interval_minutes` |  |
| [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md):525 | `proxmoxSaveConnection` | insert | `name`, `host`, `username`, `password_enc`, `verify_ssl`, `is_active`, `sync_interval_minutes` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_servers.php](/code/files/api/assets/get_servers.php.md) | L34 |  |
| [api/assets/proxmox_connections.php](/code/files/api/assets/proxmox_connections.php.md) | L27 |  |
| [cron/proxmox_sync.php](/code/files/cron/proxmox_sync.php.md) | L24 |  |
| [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md) | proxmoxLoadConnection L38, proxmoxSaveConnection L504 |  |

