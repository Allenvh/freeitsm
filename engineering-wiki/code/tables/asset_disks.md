# asset_disks

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `asset_id` | `INT NOT NULL` |  |
| `drive` | `VARCHAR(10) NULL` |  |
| `label` | `VARCHAR(100) NULL` |  |
| `file_system` | `VARCHAR(20) NULL` |  |
| `size_bytes` | `BIGINT NULL` |  |
| `free_bytes` | `BIGINT NULL` |  |
| `used_percent` | `DECIMAL(5,1) NULL` |  |
| `source` | `VARCHAR(20) NOT NULL DEFAULT 'agent'` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md):287 |  | delete |  |  |
| [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md):288 |  | insert | `asset_id`, `drive`, `label`, `file_system`, `size_bytes`, `free_bytes`, `used_percent`, `source` |  |
| [includes/intune.php](/code/files/includes/intune.php.md):410 | `intuneSyncDisks` | delete |  |  |
| [includes/intune.php](/code/files/includes/intune.php.md):412 | `intuneSyncDisks` | insert | `asset_id`, `drive`, `label`, `file_system`, `size_bytes`, `free_bytes`, `used_percent`, `source` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_disks.php](/code/files/api/assets/get_asset_disks.php.md) | L42 |  |
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetDisksList L510 |  |
| [includes/intune.php](/code/files/includes/intune.php.md) | intuneSyncDisks L395 |  |

