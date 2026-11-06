# api/intune/get_intune_device.php

> API endpoint - 47 lines

API: return the cached intune_devices row for a given asset_id, if any. Used by the asset detail panel's InTune tab.

## The endpoint

| | |
|---|---|
| URL | `api/intune/get_intune_device.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET asset_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [intune_devices](/code/tables/intune_devices.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

