# includes/asset_disk_visibility.php

> PHP - 123 lines

Hiding physical drives you do not want to look at (discussion #97).

WHY THIS EXISTS. Windows reports mounted virtual disks through the same WMI
class as real hardware, so most machines carry a ~31 MB "Microsoft Virtual
Disk" beside their actual drive. One of those is a curiosity; six hundred of
them is a Drives panel nobody reads. Hiding is a display choice, so the rows
stay in the database and stay in the REST API — what changes is whether the
asset screen draws them.

🔴 A RULE, NEVER A FLAG ON THE ROW. api/external/system-info/submit/ clears
and rewrites asset_physical_disks on EVERY agent report, so a `hidden` column
on that table would be wiped by the next scheduled run — hours later, with no
error, looking exactly like the button never worked. The row ids are reissued
too (watched go 9/10 → 11/12 between two runs of one machine), so remembering
a disk by id fails the same way. The rule has to describe the DRIVE, not the
row.

🔑 MATCHED EXACTLY, on model AND size, both NULL-safe, with no "any size"
wildcard. Ed's reasoning about not normalising drive serials applies here
too: a clever rule is one that behaves unpredictably on data nobody has seen
yet. If a "Microsoft Virtual Disk" turns up at a different size it stays
visible until somebody hides that one as well — which is the answer you can
predict from the button you pressed.

SCOPE. asset_id NULL is the estate-wide rule ("hide others like this");
asset_id set hides the drive on that machine only. tenant_id follows the
asset config convention: NULL is the Default company's, which also sees
NULL-tenant rows.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [assetDiskHideSchemaReady()](/code/functions/assetDiskHideSchemaReady.md) | 40 | Has Database Verification created the table yet? Everything here answers "nothing is hidden" when it has not, so an install that has pulled the update and not yet verified shows its drives rather than an error. |
| [assetDiskHideRulesFor()](/code/functions/assetDiskHideRulesFor.md) | 58 | The rules that could hide a drive on this asset: its own, plus every estate-wide one, scoped to the analyst's active company. |
| [assetDiskIsHidden()](/code/functions/assetDiskIsHidden.md) | 85 | Does any of `$rules` hide this drive? |
| [assetDiskMatchCount()](/code/functions/assetDiskMatchCount.md) | 108 | How many drives across the estate look like this one, and on how many assets. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 61 | `assetDiskHideRulesFor` | read [asset_disk_hide_rules](/code/tables/asset_disk_hide_rules.md) |  |
| 110 | `assetDiskMatchCount` | read [asset_physical_disks](/code/tables/asset_physical_disks.md), read [assets](/code/tables/assets.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Included by:** [api/assets/count_matching_disks.php](/code/files/api/assets/count_matching_disks.php.md), [api/assets/get_asset_disks.php](/code/files/api/assets/get_asset_disks.php.md), [api/assets/save_disk_hide_rule.php](/code/files/api/assets/save_disk_hide_rule.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md)

