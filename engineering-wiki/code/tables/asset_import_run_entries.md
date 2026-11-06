# asset_import_run_entries

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `run_id` | `INT NOT NULL` |  |
| `row_number` | `INT NULL` |  |
| `action` | `VARCHAR(16) NOT NULL` |  |
| `asset_id` | `INT NULL` |  |
| `source_ref` | `VARCHAR(255) NULL` |  |
| `display_name` | `VARCHAR(255) NULL` |  |
| `detail` | `VARCHAR(1000) NULL` |  |
| `raw_row` | `LONGTEXT NULL` |  |
| `resolved_datetime` | `DATETIME NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md):918 | `AssetImportService::resolveEntry` | update | `resolved_datetime` |  |
| [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md):1039 | `AssetImportService::logEntry` | insert | `run_id`, `row_number`, `action`, `asset_id`, `source_ref`, `display_name`, `detail`, `raw_row` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md) | L309 |  |
| [api/assets/import_history.php](/code/files/api/assets/import_history.php.md) | L60 |  |
| [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md) | AssetImportService::handleMissing L819, AssetImportService::unresolved L889, AssetImportService::runEntries L1007 |  |

