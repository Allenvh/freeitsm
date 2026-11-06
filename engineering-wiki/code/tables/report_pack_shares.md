# report_pack_shares

Who else may open a pack. target_type is 'analyst' / 'team' (target_id) or 'department' (target_value = the free-text analysts.department, which has no table of its own). can_edit 0 = open and export, 1 = also change it.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `pack_id` | `INT NOT NULL` |  |
| `target_type` | `VARCHAR(20) NOT NULL` |  |
| `target_id` | `INT NULL` |  |
| `target_value` | `VARCHAR(255) NULL` |  |
| `can_edit` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md):237 | `rpSaveShares` | delete |  |  |
| [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md):238 | `rpSaveShares` | insert | `pack_id`, `target_type`, `target_id`, `target_value`, `can_edit` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md) | rpRole L106, rpListPacks L134, rpListShares L178 |  |

