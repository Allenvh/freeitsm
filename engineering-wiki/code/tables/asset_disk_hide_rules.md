# asset_disk_hide_rules

Drives you have told FreeITSM you do not want to look at.  🔴 A RULE, NOT A FLAG ON THE DISK ROW. asset_physical_disks is cleared and rewritten on EVERY agent report, so a `hidden` column there would be gone by the next scheduled run — hours later, silently, and looking exactly like the button never worked. The same reasoning rules out remembering a disk's id: those are reissued on every report too (watched go 9/10 → 11/12 between two runs of the same machine).  🔑 MATCHED EXACTLY on model AND size, both NULL-safe, with no "any size" wildcard. Ed's rule about the drive serial applies here as well: a clever rule is one that behaves unpredictably on data nobody has seen yet. If a "Microsoft Virtual Disk" shows up at a different size it stays visible until somebody hides that one too, which is the answer you can predict.  asset_id NULL = every asset ("hide others like this"); set = this one only.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `tenant_id` | `INT NULL` |  |
| `asset_id` | `INT NULL` |  |
| `model` | `VARCHAR(255) NULL` |  |
| `size_bytes` | `BIGINT NULL` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/save_disk_hide_rule.php](/code/files/api/assets/save_disk_hide_rule.php.md):85 |  | insert | `tenant_id`, `asset_id`, `model`, `size_bytes`, `created_by_analyst_id`, `created_datetime` |  |
| [api/assets/save_disk_hide_rule.php](/code/files/api/assets/save_disk_hide_rule.php.md):106 |  | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/asset_disk_visibility.php](/code/files/includes/asset_disk_visibility.php.md) | assetDiskHideRulesFor L61 |  |

