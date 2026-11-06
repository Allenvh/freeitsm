# vcloud_connections

VMware Cloud Director servers (several per install). Passwords are encrypted at rest.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `host` | `VARCHAR(255) NOT NULL` |  |
| `org` | `VARCHAR(100) NOT NULL` |  |
| `username` | `VARCHAR(100) NOT NULL` |  |
| `password_enc` | `LONGTEXT NULL` |  |
| `api_version` | `VARCHAR(10) NOT NULL DEFAULT '38.0'` |  |
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
| [api/assets/vcloud_connections.php](/code/files/api/assets/vcloud_connections.php.md):48 |  | delete |  |  |
| [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md):352 | `vcdSyncConnection` | update | `last_sync_datetime`, `last_sync_status`, `last_sync_message` |  |
| [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md):499 | `vcdSaveConnection` | update | `name`, `host`, `org`, `username`, `password_enc`, `api_version`, `verify_ssl`, `is_active`, `sync_interval_minutes` |  |
| [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md):505 | `vcdSaveConnection` | insert | `name`, `host`, `org`, `username`, `password_enc`, `api_version`, `verify_ssl`, `is_active`, `sync_interval_minutes` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_servers.php](/code/files/api/assets/get_servers.php.md) | L62 |  |
| [api/assets/vcloud_connections.php](/code/files/api/assets/vcloud_connections.php.md) | L27 |  |
| [cron/vcloud_sync.php](/code/files/cron/vcloud_sync.php.md) | L21 |  |
| [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md) | vcdLoadConnection L42, vcdSaveConnection L484 |  |

