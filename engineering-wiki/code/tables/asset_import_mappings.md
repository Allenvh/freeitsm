# asset_import_mappings

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `profile_id` | `INT NOT NULL` |  |
| `source_key` | `VARCHAR(255) NOT NULL` |  |
| `target_kind` | `VARCHAR(10) NOT NULL` |  |
| `target_key` | `VARCHAR(100) NOT NULL` |  |
| `transform` | `LONGTEXT NULL` |  |

*No SQL in the scanned code names this table directly.*
