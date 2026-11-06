# intune_app_sync_jobs

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `started_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `finished_datetime` | `DATETIME NULL` |  |
| `status` | `VARCHAR(16) NOT NULL DEFAULT 'pending'` |  |
| `total` | `INT NOT NULL DEFAULT 0` |  |
| `processed` | `INT NOT NULL DEFAULT 0` |  |
| `failed` | `INT NOT NULL DEFAULT 0` |  |
| `message` | `LONGTEXT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/intune/create_app_sync_job.php](/code/files/api/intune/create_app_sync_job.php.md):52 |  | update | `status`, `message`, `finished_datetime` |  |
| [includes/intune.php](/code/files/includes/intune.php.md):676 | `intuneCreateAppSyncJob` | update | `status`, `message`, `finished_datetime` |  |
| [includes/intune.php](/code/files/includes/intune.php.md):694 | `intuneCreateAppSyncJob` | insert | `started_datetime`, `status`, `total`, `processed`, `failed`, `message` |  |
| [includes/intune.php](/code/files/includes/intune.php.md):808 | `intuneRunAppSyncJob` | update | `status`, `processed`, `failed`, `finished_datetime`, `message` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/intune/app_sync_job_status.php](/code/files/api/intune/app_sync_job_status.php.md) | L30, L36 |  |
| [api/intune/list_app_sync_jobs.php](/code/files/api/intune/list_app_sync_jobs.php.md) | L25 |  |
| [includes/intune.php](/code/files/includes/intune.php.md) | intuneGetAppSyncCandidates L631, intuneCreateAppSyncJob L666 |  |

