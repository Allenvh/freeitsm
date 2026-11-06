# watchtower_item_members

Which statuses/priorities feed one Watchtower item. Rows rather than a flag on the status, so an item can hold a set and each member can carry its own severity later. entity_type is polymorphic, so no foreign key is possible.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL DEFAULT 0` |  |
| `item_key` | `VARCHAR(60) NOT NULL` |  |
| `entity_type` | `VARCHAR(30) NOT NULL` |  |
| `entity_id` | `INT NOT NULL` |  |
| `severity` | `VARCHAR(10) NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/watchtower/save_settings.php](/code/files/api/watchtower/save_settings.php.md):78 |  | delete |  |  |
| [api/watchtower/save_settings.php](/code/files/api/watchtower/save_settings.php.md):96 |  | insert | `analyst_id`, `item_key`, `entity_type`, `entity_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md) | wtItemMembers L356 |  |

