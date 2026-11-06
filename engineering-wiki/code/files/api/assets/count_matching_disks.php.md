# api/assets/count_matching_disks.php

> API endpoint - 59 lines

API: how many drives across the estate look like this one (#97).

GET ?asset_id=123&model=Microsoft+Virtual+Disk&size_bytes=32901120
  -> { success, drives, assets, others }

Answers the question the Hide dialog puts to the user before they commit:
"50 drives on 50 machines look like this one — hide those too?" A count is
the whole point of the dialog, so it is fetched when the button is pressed
rather than for every drive on every asset screen.

`size_bytes` is sent as a STRING and passed through as one. It is a BIGINT
and PHP ints are signed 64-bit, so casting is safe today, but the matching
helper compares NULL-safely and a string keeps "absent" and "zero" apart —
and zero is not hypothetical here, it is the virtual disk this exists for.

## The endpoint

| | |
|---|---|
| URL | `api/assets/count_matching_disks.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET asset_id`, `GET model`, `GET size_bytes` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/asset_disk_visibility.php](/code/files/includes/asset_disk_visibility.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [assetDiskMatchCount()](/code/functions/assetDiskMatchCount.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

