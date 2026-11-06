# asset_import_runs

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `profile_id` | `INT NULL` |  |
| `mode` | `VARCHAR(10) NOT NULL DEFAULT 'live'` |  |
| `status` | `VARCHAR(12) NOT NULL DEFAULT 'running'` |  |
| `source_name` | `VARCHAR(255) NULL` |  |
| `stored_file` | `VARCHAR(255) NULL` |  |
| `started_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `finished_datetime` | `DATETIME NULL` |  |
| `seen_count` | `INT NOT NULL DEFAULT 0` |  |
| `created_count` | `INT NOT NULL DEFAULT 0` |  |
| `updated_count` | `INT NOT NULL DEFAULT 0` |  |
| `unchanged_count` | `INT NOT NULL DEFAULT 0` |  |
| `conflict_count` | `INT NOT NULL DEFAULT 0` |  |
| `skipped_count` | `INT NOT NULL DEFAULT 0` |  |
| `error_count` | `INT NOT NULL DEFAULT 0` |  |
| `message` | `TEXT NULL` |  |
| `triggered_by_analyst_id` | `INT NULL` |  |
| `tenant_id` | `INT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md):940 | `AssetImportService::startRun` | insert | `profile_id`, `mode`, `status`, `source_name`, `stored_file`, `triggered_by_analyst_id`, `tenant_id`, `started_datetime` |  |
| [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md):965 | `AssetImportService::finishRun` | update | `status`, `finished_datetime`, `seen_count`, `created_count`, `updated_count`, `unchanged_count`, `conflict_count`, `skipped_count`, `error_count` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md) | L309 |  |
| [api/assets/import_history.php](/code/files/api/assets/import_history.php.md) | L50, L60 |  |
| [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md) | AssetImportService::schemaReady L110, AssetImportService::handleMissing L819, AssetImportService::unresolved L889, AssetImportService::loadRun L985 |  |

