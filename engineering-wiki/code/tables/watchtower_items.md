# watchtower_items

Watchtower settings. analyst_id 0 = the installation's setting; real ids are reserved for per-person overrides later. is_customised distinguishes "not configured" from "configured to nothing".

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL DEFAULT 0` |  |
| `item_key` | `VARCHAR(60) NOT NULL` |  |
| `is_visible` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `is_customised` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/watchtower/save_settings.php](/code/files/api/watchtower/save_settings.php.md):52 |  | insert | `analyst_id`, `item_key`, `is_visible`, `is_customised` |  |
| [api/watchtower/save_settings.php](/code/files/api/watchtower/save_settings.php.md):72 |  | insert | `analyst_id`, `item_key`, `is_visible`, `is_customised` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md) | wtVisibleCards L320, wtItemMembers L351 |  |

