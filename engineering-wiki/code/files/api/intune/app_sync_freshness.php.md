# api/intune/app_sync_freshness.php

> API endpoint - 76 lines

API: per-asset freshness buckets for the InTune software-sync chart.

Scope: assets that are linked to an Intune managedDevice AND have NO
source='agent' rows in software_inventory_detail (i.e. the population
the Intune app sync actually targets).

Returns counts in 9 buckets: <1d, 1d, 2d, 3d, 4d, 5d, 6d, 7+d, never —
based on the most recent source='intune' last_seen for each asset.

## The endpoint

| | |
|---|---|
| URL | `api/intune/app_sync_freshness.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_INTUNE (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [software_inventory_detail](/code/tables/software_inventory_detail.md), read [assets](/code/tables/assets.md), read [intune_devices](/code/tables/intune_devices.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

