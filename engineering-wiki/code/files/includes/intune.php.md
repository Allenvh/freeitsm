# includes/intune.php

> PHP - 937 lines

Microsoft InTune (Microsoft Graph) sync helpers.

Token + paged Graph GET, plus the upsert/link/stub-creation logic that
keeps intune_devices and assets in step. Designed to run from a detached
CLI worker so syncs don't block the web request.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [intuneGetSettings()](/code/functions/intuneGetSettings.md) | 16 | Load the InTune connection settings from system_settings, decrypting the sensitive values. Returns null if any required setting is missing. |
| [intuneGetToken()](/code/functions/intuneGetToken.md) | 53 | Acquire a Graph access token via client-credentials. Cached for the lifetime of the PHP process (token TTL is ~1h). |
| [intuneGraphGet()](/code/functions/intuneGraphGet.md) | 104 | GET a Graph URL, retrying on 429/5xx with Retry-After honoured. |
| [intuneIsoToMysql()](/code/functions/intuneIsoToMysql.md) | 166 | Convert a Graph ISO-8601 timestamp ("2025-04-30T08:14:22Z") to a MySQL DATETIME string ("2025-04-30 08:14:22"), in UTC. Returns null for empty input. |
| [intuneUpsertDevice()](/code/functions/intuneUpsertDevice.md) | 180 | Upsert a single Graph managedDevice into intune_devices. Keyed on intune_id. |
| [intuneLinkDevicesToAssets()](/code/functions/intuneLinkDevicesToAssets.md) | 290 | Link intune_devices to assets, then create stub assets for any device still unlinked. Returns counts so the sync job message can show what happened. |
| [intuneOsToDrive()](/code/functions/intuneOsToDrive.md) | 372 | Map an Intune operating_system string to a sensible "drive" label for asset_disks. Intune only gives us a single device-level total/free figure, so we fake a single drive entry whose name reflects the platform. |
| [intuneSyncDisks()](/code/functions/intuneSyncDisks.md) | 390 | Populate asset_disks with the single device-level total/free figure Intune provides — but only for assets that have NO agent-sourced rows. The PS inventory agent's per-drive data is more accurate, so we never overwrite it. |
| [intuneRunSync()](/code/functions/intuneRunSync.md) | 443 | Run the full sync for a given job id. Updates intune_sync_jobs as it goes so the UI progress bar can poll. Designed for CLI worker use. |
| [intuneFindPhpExe()](/code/functions/intuneFindPhpExe.md) | 544 | Locate a usable PHP CLI binary so the web request can spawn a worker. Tries: explicit setting → PHP_BINARY's directory → known WAMP layout → 'php' from PATH. Returns the path or throws if nothing works. |
| [intuneSpawnWorker()](/code/functions/intuneSpawnWorker.md) | 573 | Spawn the CLI worker so it survives the web request. Returns true on success. |
| [intuneSpawnGenericWorker()](/code/functions/intuneSpawnGenericWorker.md) | 584 | Generic spawn helper used by both the device sync and the app sync workers. |
| [intuneGetAppBatchSize()](/code/functions/intuneGetAppBatchSize.md) | 611 | Read the configured app sync batch size from system_settings, with a sane default and bounds (1..500). |
| [intuneGetAppSyncCandidates()](/code/functions/intuneGetAppSyncCandidates.md) | 630 | Pick the next `$limit` assets that are good candidates for an Intune app sync: - Have a linked intune_devices row (with a usable Graph id) - Have NO 'agent'-sourced rows in software_inventory_detail (agent wins) - Aren't already pending/running in another job Ordered: assets that have never been Intune-synced first, then oldest synced. |
| [intuneCreateAppSyncJob()](/code/functions/intuneCreateAppSyncJob.md) | 663 | Create a new app-sync job for the next batch of candidate assets. Returns ['job_id' => int, 'asset_count' => int, 'reused' => bool]. If a pending/running job already exists, returns it (reused=true). |
| [intuneFetchDeviceApps()](/code/functions/intuneFetchDeviceApps.md) | 720 | Fetch the detectedApps array for a single Intune-managed device. |
| [intuneUpsertSoftwareForAsset()](/code/functions/intuneUpsertSoftwareForAsset.md) | 734 | Wipe and re-write the source='intune' software rows for a single asset. Apps catalogue (software_inventory_apps) is shared with the agent — we dedupe on (display_name, publisher). |
| [intuneRunAppSyncJob()](/code/functions/intuneRunAppSyncJob.md) | 796 | Run an app-sync job: iterate its child rows, fetch detectedApps from Graph for each asset, upsert into software_inventory_*, and tick progress. Designed for CLI worker use. |
| [intuneSpawnAppWorker()](/code/functions/intuneSpawnAppWorker.md) | 934 | Spawn the CLI app-sync worker. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 18 | `intuneGetSettings` | read [system_settings](/code/tables/system_settings.md) |  |
| 183 | `intuneUpsertDevice` | **insert** [intune_devices](/code/tables/intune_devices.md) (`intune_id`, `device_name`, `user_principal_name`, `user_display_name`, `user_id`, `operating_system`, `os_version`, `compliance_state`, `management_state`, `managed_device_owner_type`, `device_enrollment_type`, `device_registration_state`) |  |
| 315 | `intuneLinkDevicesToAssets` | read [intune_devices](/code/tables/intune_devices.md), read [assets](/code/tables/assets.md) |  |
| 327 | `intuneLinkDevicesToAssets` | read [intune_devices](/code/tables/intune_devices.md) |  |
| 335 | `intuneLinkDevicesToAssets` | **update** [intune_devices](/code/tables/intune_devices.md) (`asset_id`) |  |
| 360 | `intuneLinkDevicesToAssets` | read [intune_devices](/code/tables/intune_devices.md) |  |
| 395 | `intuneSyncDisks` | read [intune_devices](/code/tables/intune_devices.md), read [asset_disks](/code/tables/asset_disks.md) |  |
| 410 | `intuneSyncDisks` | **delete** [asset_disks](/code/tables/asset_disks.md) |  |
| 412 | `intuneSyncDisks` | **insert** [asset_disks](/code/tables/asset_disks.md) (`asset_id`, `drive`, `label`, `file_system`, `size_bytes`, `free_bytes`, `used_percent`, `source`) |  |
| 455 | `intuneRunSync` | **update** [intune_sync_jobs](/code/tables/intune_sync_jobs.md) (`total`, `processed`, `status`, `finished_datetime`, `message`) |  |
| 612 | `intuneGetAppBatchSize` | read [system_settings](/code/tables/system_settings.md) |  |
| 631 | `intuneGetAppSyncCandidates` | read [software_inventory_detail](/code/tables/software_inventory_detail.md), read [assets](/code/tables/assets.md), read [intune_devices](/code/tables/intune_devices.md), read [intune_app_sync_job_assets](/code/tables/intune_app_sync_job_assets.md), read [intune_app_sync_jobs](/code/tables/intune_app_sync_jobs.md) |  |
| 666 | `intuneCreateAppSyncJob` | read [intune_app_sync_jobs](/code/tables/intune_app_sync_jobs.md) |  |
| 669 | `intuneCreateAppSyncJob` | read [intune_app_sync_job_assets](/code/tables/intune_app_sync_job_assets.md) |  |
| 676 | `intuneCreateAppSyncJob` | **update** [intune_app_sync_jobs](/code/tables/intune_app_sync_jobs.md) (`status`, `message`, `finished_datetime`) |  |
| 694 | `intuneCreateAppSyncJob` | **insert** [intune_app_sync_jobs](/code/tables/intune_app_sync_jobs.md) (`started_datetime`, `status`, `total`, `processed`, `failed`, `message`) |  |
| 700 | `intuneCreateAppSyncJob` | **insert** [intune_app_sync_job_assets](/code/tables/intune_app_sync_job_assets.md) (`job_id`, `asset_id`, `status`) |  |
| 736 | `intuneUpsertSoftwareForAsset` | **delete** [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |
| 743 | `intuneUpsertSoftwareForAsset` | read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |
| 747 | `intuneUpsertSoftwareForAsset` | **insert** [software_inventory_apps](/code/tables/software_inventory_apps.md) (`display_name`, `publisher`) |  |
| 750 | `intuneUpsertSoftwareForAsset` | **insert** [software_inventory_detail](/code/tables/software_inventory_detail.md) (`host_id`, `app_id`, `display_version`, `estimated_size`, `system_component`, `created_at`, `last_seen`, `source`) |  |
| 808 | `intuneRunAppSyncJob` | **update** [intune_app_sync_jobs](/code/tables/intune_app_sync_jobs.md) (`status`, `processed`, `failed`, `finished_datetime`, `message`) |  |
| 814 | `intuneRunAppSyncJob` | **update** [intune_app_sync_job_assets](/code/tables/intune_app_sync_job_assets.md) (`status`, `error_message`, `synced_datetime`, `app_count`) |  |
| 834 | `intuneRunAppSyncJob` | read [intune_app_sync_job_assets](/code/tables/intune_app_sync_job_assets.md), read [assets](/code/tables/assets.md), read [intune_devices](/code/tables/intune_devices.md) |  |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Included by:** [api/intune/create_app_sync_job.php](/code/files/api/intune/create_app_sync_job.php.md), [api/intune/sync.php](/code/files/api/intune/sync.php.md)

**Calls:** [decryptValue()](/code/functions/decryptValue.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

