# api/intune/create_app_sync_job.php

> API endpoint - 73 lines

API: create a new InTune app-sync job for the next batch of candidate assets and spawn the CLI worker. Returns the job id (or the existing one if a job is already pending/running).

## The endpoint

| | |
|---|---|
| URL | `api/intune/create_app_sync_job.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_INTUNE (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 |  | **update** [intune_app_sync_jobs](/code/tables/intune_app_sync_jobs.md) (`status`, `message`, `finished_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/intune.php](/code/files/includes/intune.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [intuneCreateAppSyncJob()](/code/functions/intuneCreateAppSyncJob.md), [intuneGetSettings()](/code/functions/intuneGetSettings.md), [intuneSpawnAppWorker()](/code/functions/intuneSpawnAppWorker.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

