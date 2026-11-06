# api/intune/dashboard_data.php

> API endpoint - 242 lines

API: Intune Dashboard Data Returns all the aggregations the reporting/intune dashboard needs in a single JSON payload — KPI numbers + every chart's data — so the page makes one request rather than ten.

## The endpoint

| | |
|---|---|
| URL | `api/intune/dashboard_data.php` |
| Protected by | Module access: reporting (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [intune_devices](/code/tables/intune_devices.md) |  |
| 28 |  | read [intune_devices](/code/tables/intune_devices.md) |  |
| 36 |  | read [intune_devices](/code/tables/intune_devices.md) |  |
| 42 |  | read [intune_devices](/code/tables/intune_devices.md) |  |
| 50 |  | read [intune_devices](/code/tables/intune_devices.md) |  |
| 70 |  | read [intune_devices](/code/tables/intune_devices.md) |  |
| 83 |  | read [intune_devices](/code/tables/intune_devices.md) |  |
| 100 |  | read [intune_devices](/code/tables/intune_devices.md) |  |
| 114 |  | read [intune_devices](/code/tables/intune_devices.md) |  |
| 135 |  | read [intune_devices](/code/tables/intune_devices.md) |  |
| 159 |  | read [intune_devices](/code/tables/intune_devices.md) |  |
| 184 |  | read [intune_devices](/code/tables/intune_devices.md) |  |
| 204 |  | read [intune_sync_jobs](/code/tables/intune_sync_jobs.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

