# asset_import_profiles

Asset import (docs/design/flexible-asset-fields.md §6)

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(150) NOT NULL` |  |
| `target` | `VARCHAR(20) NOT NULL DEFAULT 'asset'` |  |
| `source_kind` | `VARCHAR(10) NOT NULL DEFAULT 'csv'` |  |
| `source_config` | `LONGTEXT NULL` |  |
| `match_keys` | `LONGTEXT NULL` |  |
| `on_missing` | `VARCHAR(20) NOT NULL DEFAULT 'ignore'` |  |
| `on_unknown_option` | `VARCHAR(10) NOT NULL DEFAULT 'reject'` |  |
| `write_mode` | `VARCHAR(10) NOT NULL DEFAULT 'fill'` |  |
| `default_asset_type_id` | `INT NULL` |  |
| `default_status_id` | `INT NULL` |  |
| `apply_field_set_id` | `INT NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `tenant_id` | `INT NULL` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

*No SQL in the scanned code names this table directly.*
