# asset_tag_counters

Generated asset tag counters (PR #164) - ticket_number_counters' shape, and the same ⚠️: counter_key is the PK, declared in db_verify.php's `$primaryKeys` map, not here.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `counter_key` | `VARCHAR(64) NOT NULL` |  |
| `next_value` | `BIGINT NOT NULL DEFAULT 1` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md):221 | `AssetTagsService::windCounterTo` | insert | `counter_key`, `next_value` |  |
| [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md):235 | `AssetTagsService::claimNext` | insert | `counter_key`, `next_value` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md) | AssetTagsService::nextNumber L191 |  |

