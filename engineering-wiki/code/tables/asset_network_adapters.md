# asset_network_adapters

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `asset_id` | `INT NOT NULL` |  |
| `name` | `VARCHAR(255) NULL` |  |
| `mac_address` | `VARCHAR(17) NULL` |  |
| `ip_address` | `VARCHAR(45) NULL` |  |
| `subnet_mask` | `VARCHAR(45) NULL` |  |
| `gateway` | `VARCHAR(45) NULL` |  |
| `dhcp_enabled` | `TINYINT(1) NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md):380 |  | delete |  |  |
| [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md):381 |  | insert | `asset_id`, `name`, `mac_address`, `ip_address`, `subnet_mask`, `gateway`, `dhcp_enabled` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetNetworkAdaptersList L571 |  |

