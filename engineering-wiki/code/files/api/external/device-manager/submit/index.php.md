# api/external/device-manager/submit/index.php

> API endpoint - 186 lines

External API: Device Manager Ingest

Accepts a JSON POST with device manager data from the PowerShell
collection script and syncs the asset_devices table (delete + reinsert).

Auth: Authorization header with API key (validated against apikeys table).

## The endpoint

| | |
|---|---|
| URL | `api/external/device-manager/submit/index.php` |
| Protected by | Public - no sign-in check found (sign-in pages, webhooks and token links are meant to be; anything else, check the code) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 48 |  | read [apikeys](/code/tables/apikeys.md) |  |
| 52 |  | read [apikeys](/code/tables/apikeys.md) |  |
| 104 |  | read [assets](/code/tables/assets.md) |  |
| 143 |  | **delete** [asset_devices](/code/tables/asset_devices.md) |  |
| 144 |  | **insert** [asset_devices](/code/tables/asset_devices.md) (`asset_id`, `device_class`, `device_name`, `status`, `manufacturer`, `driver_version`, `driver_date`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

