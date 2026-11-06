# api/assets/get_asset_disks.php

> API endpoint - 91 lines

API Endpoint: Get disks for a specific asset

Returns two lists, because a machine has two kinds of disk and they answer
different questions:
  `disks`           — the lettered volumes, with size, free space and usage
  `physical_disks`  — the drives themselves, with model, SERIAL and interface

The physical list is what discussion #97 asked for: the serial you quote on a
warranty claim or a disposal certificate. The agent has always sent it; there
was simply nowhere to put it until asset_physical_disks existed.

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_disks.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET asset_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [asset_disks](/code/tables/asset_disks.md) |  |
| 58 |  | read [asset_physical_disks](/code/tables/asset_physical_disks.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/asset_disk_visibility.php](/code/files/includes/asset_disk_visibility.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [assetDiskHideRulesFor()](/code/functions/assetDiskHideRulesFor.md), [assetDiskIsHidden()](/code/functions/assetDiskIsHidden.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

