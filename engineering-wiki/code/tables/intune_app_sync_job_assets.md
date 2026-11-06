# intune_app_sync_job_assets

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `job_id` | `INT NOT NULL` |  |
| `asset_id` | `INT NOT NULL` |  |
| `status` | `VARCHAR(16) NOT NULL DEFAULT 'pending'` |  |
| `error_message` | `LONGTEXT NULL` |  |
| `synced_datetime` | `DATETIME NULL` |  |
| `app_count` | `INT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/intune.php](/code/files/includes/intune.php.md):700 | `intuneCreateAppSyncJob` | insert | `job_id`, `asset_id`, `status` |  |
| [includes/intune.php](/code/files/includes/intune.php.md):814 | `intuneRunAppSyncJob` | update | `status`, `error_message`, `synced_datetime`, `app_count` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/intune/app_sync_job_status.php](/code/files/api/intune/app_sync_job_status.php.md) | L51 |  |
| [includes/intune.php](/code/files/includes/intune.php.md) | intuneGetAppSyncCandidates L631, intuneCreateAppSyncJob L669, intuneRunAppSyncJob L834 |  |

