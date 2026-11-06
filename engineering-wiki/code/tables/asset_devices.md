# asset_devices

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `asset_id` | `INT NOT NULL` |  |
| `device_class` | `VARCHAR(100) NULL` |  |
| `device_name` | `VARCHAR(255) NOT NULL` |  |
| `status` | `VARCHAR(20) NULL` |  |
| `manufacturer` | `VARCHAR(255) NULL` |  |
| `driver_version` | `VARCHAR(50) NULL` |  |
| `driver_date` | `DATE NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/external/device-manager/submit/index.php](/code/files/api/external/device-manager/submit/index.php.md):143 |  | delete |  |  |
| [api/external/device-manager/submit/index.php](/code/files/api/external/device-manager/submit/index.php.md):144 |  | insert | `asset_id`, `device_class`, `device_name`, `status`, `manufacturer`, `driver_version`, `driver_date` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_devices.php](/code/files/api/assets/get_asset_devices.php.md) | L34 |  |
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetDevicesList L595 |  |

