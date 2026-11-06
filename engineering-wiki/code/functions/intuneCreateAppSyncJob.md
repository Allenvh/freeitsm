# intuneCreateAppSyncJob()

Create a new app-sync job for the next batch of candidate assets. Returns ['job_id' => int, 'asset_count' => int, 'reused' => bool]. If a pending/running job already exists, returns it (reused=true).

```php
function intuneCreateAppSyncJob(PDO $conn): array
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 663.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 666 | `intuneCreateAppSyncJob` | read [intune_app_sync_jobs](/code/tables/intune_app_sync_jobs.md) |  |
| 669 | `intuneCreateAppSyncJob` | read [intune_app_sync_job_assets](/code/tables/intune_app_sync_job_assets.md) |  |
| 676 | `intuneCreateAppSyncJob` | **update** [intune_app_sync_jobs](/code/tables/intune_app_sync_jobs.md) (`status`, `message`, `finished_datetime`) |  |
| 694 | `intuneCreateAppSyncJob` | **insert** [intune_app_sync_jobs](/code/tables/intune_app_sync_jobs.md) (`started_datetime`, `status`, `total`, `processed`, `failed`, `message`) |  |
| 700 | `intuneCreateAppSyncJob` | **insert** [intune_app_sync_job_assets](/code/tables/intune_app_sync_job_assets.md) (`job_id`, `asset_id`, `status`) |  |

## Calls

[intuneGetAppBatchSize()](/code/functions/intuneGetAppBatchSize.md), [intuneGetAppSyncCandidates()](/code/functions/intuneGetAppSyncCandidates.md)

## Called from

- [api/intune/create_app_sync_job.php](/code/files/api/intune/create_app_sync_job.php.md)

